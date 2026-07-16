<?php
/**
 * process.php — Batch CWV checker
 *
 * Accepts POST JSON: { "urls": [...], "strategy": "mobile|desktop", "api_key": "..." }
 * Returns JSON:      { "results": [ { url, lcp, inp, cls, fcp, ttfb } ] }
 *
 * Uses curl_multi to fire all PSI requests in the batch in PARALLEL.
 * Compatible with PHP 7.2+.
 */

// Buffer all output so stray PHP warnings/errors never corrupt the JSON response
ob_start();

// ── Bootstrap ─────────────────────────────────────────────────────────────────
set_time_limit(120);
ini_set('display_errors', '0');   // never leak HTML errors into the JSON stream
error_reporting(E_ALL);

// ── Constants (define() works in all PHP versions) ────────────────────────────
define('PSI_ENDPOINT',     'https://www.googleapis.com/pagespeedonline/v5/runPagespeed');
define('PER_BATCH_TIMEOUT', 60);

$THRESHOLDS = [
    'lcp'  => 2500,
    'inp'  => 200,
    'cls'  => 0.1,
    'fcp'  => 1800,
    'ttfb' => 800,
];

// ── Main (wrapped so we can always emit JSON even on exception) ───────────────
try {
    main_handler($THRESHOLDS);
} catch (Throwable $e) {
    ob_end_clean();
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}

// ── Handler ───────────────────────────────────────────────────────────────────
function main_handler(array $THRESHOLDS)
{
    // ── Read & validate input ─────────────────────────────────────────────────
    $raw  = file_get_contents('php://input');
    $body = json_decode($raw, true);

    if (!$body || !isset($body['urls'], $body['strategy'], $body['api_key'])) {
        send_error('Invalid request body — expected JSON with urls, strategy, api_key.');
    }

    $urls = [];
    foreach ((array)$body['urls'] as $u) {
        $u = trim((string)$u);
        if (filter_var($u, FILTER_VALIDATE_URL)) {
            $urls[] = $u;
        }
    }
    $urls     = array_values(array_unique($urls));
    $strategy = ($body['strategy'] === 'desktop') ? 'desktop' : 'mobile';
    $api_key  = trim((string)$body['api_key']);

    if ($api_key === '')  send_error('API key is required.');
    if (empty($urls))     send_error('No valid URLs in batch.');
    if (count($urls) > 20) $urls = array_slice($urls, 0, 20); // safety cap

    // ── Build PSI request URLs ────────────────────────────────────────────────
    $psi_map = [];  // original_url => psi_request_url
    foreach ($urls as $url) {
        $psi_map[$url] = PSI_ENDPOINT . '?' . http_build_query([
            'url'      => $url,
            'strategy' => $strategy,
            'category' => 'performance',
            'key'      => $api_key,
        ]);
    }

    // ── Parallel curl_multi execution ─────────────────────────────────────────
    $mh      = curl_multi_init();
    $handles = [];  // original_url => curl handle

    foreach ($psi_map as $url => $psi_url) {
        $ch = curl_init($psi_url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => PER_BATCH_TIMEOUT,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 3,
            CURLOPT_ENCODING       => '',
            CURLOPT_USERAGENT      => 'CWV-Finder/2.0',
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        curl_multi_add_handle($mh, $ch);
        $handles[$url] = $ch;
    }

    $running = null;
    do {
        $status = curl_multi_exec($mh, $running);
        if ($running) {
            curl_multi_select($mh, 0.5);
        }
    } while ($running > 0 && $status === CURLM_OK);

    // ── Collect & parse results ───────────────────────────────────────────────
    $results = [];

    foreach ($handles as $url => $ch) {
        $response  = curl_multi_getcontent($ch);
        $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_err  = curl_error($ch);
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);

        $cwv       = extract_cwv($response, $curl_err, $http_code);
        $results[] = array_merge(['url' => $url], $cwv);
    }

    curl_multi_close($mh);

    // ── Discard any buffered stray output, then send JSON ─────────────────────
    ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    echo json_encode(
        ['results' => $results],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );
}

// ── Metric extraction ─────────────────────────────────────────────────────────

/**
 * Parse a PSI API response body and return the five CWV metric values.
 * Any unavailable metric is returned as null.
 */
function extract_cwv($raw, $curl_err, $http_code)
{
    $empty = ['lcp' => null, 'inp' => null, 'cls' => null, 'fcp' => null, 'ttfb' => null];

    if ($curl_err !== '' || $http_code !== 200 || !$raw) {
        return $empty;
    }

    $data = json_decode($raw, true);
    if (!is_array($data)) {
        return $empty;
    }

    // Prefer URL-level CrUX data, fall back to origin-level
    $m = isset($data['loadingExperience']['metrics'])
       ? $data['loadingExperience']['metrics']
       : null;

    if (empty($m) && isset($data['originLoadingExperience']['metrics'])) {
        $m = $data['originLoadingExperience']['metrics'];
    }

    if (empty($m)) {
        return $empty;
    }

    // LCP — milliseconds
    $lcp = isset($m['LARGEST_CONTENTFUL_PAINT_MS']['percentile'])
         ? (int) $m['LARGEST_CONTENTFUL_PAINT_MS']['percentile']
         : null;

    // INP — milliseconds (replaced FID)
    $inp = isset($m['INTERACTION_TO_NEXT_PAINT']['percentile'])
         ? (int) $m['INTERACTION_TO_NEXT_PAINT']['percentile']
         : null;

    // CLS — PSI stores it multiplied by 100 (e.g. 10 = 0.10)
    $cls = isset($m['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile'])
         ? round((float) $m['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile'] / 100, 4)
         : null;

    // FCP — milliseconds
    $fcp = isset($m['FIRST_CONTENTFUL_PAINT_MS']['percentile'])
         ? (int) $m['FIRST_CONTENTFUL_PAINT_MS']['percentile']
         : null;

    // TTFB — milliseconds
    $ttfb = isset($m['EXPERIMENTAL_TIME_TO_FIRST_BYTE']['percentile'])
          ? (int) $m['EXPERIMENTAL_TIME_TO_FIRST_BYTE']['percentile']
          : null;

    return compact('lcp', 'inp', 'cls', 'fcp', 'ttfb');
}

// ── Error helper ──────────────────────────────────────────────────────────────
function send_error($msg)
{
    ob_end_clean();
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => $msg]);
    exit;
}
