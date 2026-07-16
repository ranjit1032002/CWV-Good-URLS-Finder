<?php
session_start();

// Handle logout
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: index.php');
  exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  
  if ($username === 'ranjit' && $password === 'Ranjit@9062') {
    $_SESSION['authenticated'] = true;
    $_SESSION['username'] = $username;
    header('Location: index.php');
    exit;
  } else {
    $loginError = 'Invalid username or password';
  }
}

// Check if user is authenticated
$isAuthenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>CWV Good URL Finder</title>
<style>
/* ── Reset & Base ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --bg:        #0f1117;
  --surface:   #1a1d27;
  --surface2:  #22263a;
  --border:    #2e3350;
  --accent:    #4f8ef7;
  --accent2:   #7c3aed;
  --good:      #22c55e;
  --warn:      #f59e0b;
  --bad:       #ef4444;
  --text:      #e2e8f0;
  --muted:     #64748b;
  --radius:    14px;
  --shadow:    0 4px 32px rgba(0,0,0,.45);
}
body {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  background: var(--bg); color: var(--text);
  min-height: 100vh; line-height: 1.5;
}

/* ── Header ── */
.header {
  background: linear-gradient(135deg, #1e2a4a 0%, #12172b 100%);
  border-bottom: 1px solid var(--border);
  padding: 2.5rem 2rem 2rem;
  text-align: center;
  position: relative; overflow: hidden;
}
.header::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse at 50% -20%, rgba(79,142,247,.18) 0%, transparent 70%);
  pointer-events: none;
}
.header h1 {
  font-size: clamp(1.5rem, 3vw, 2.2rem);
  font-weight: 800; letter-spacing: -.02em;
  background: linear-gradient(135deg, #fff 30%, #4f8ef7);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.header p { color: var(--muted); margin-top: .5rem; font-size: .95rem; }

/* ── Badges in header ── */
.metric-badges {
  display: flex; flex-wrap: wrap; justify-content: center;
  gap: .6rem; margin-top: 1.2rem;
}
.metric-badge {
  display: flex; align-items: center; gap: .4rem;
  padding: .35rem .9rem; border-radius: 999px;
  font-size: .78rem; font-weight: 600; border: 1px solid;
}
.metric-badge.lcp  { background: rgba(79,142,247,.12); border-color: rgba(79,142,247,.3);  color: #7eb8ff; }
.metric-badge.inp  { background: rgba(124,58,237,.12);  border-color: rgba(124,58,237,.3); color: #b093ff; }
.metric-badge.cls  { background: rgba(34,197,94,.12);   border-color: rgba(34,197,94,.3);  color: #4ade80; }
.metric-badge.fcp  { background: rgba(245,158,11,.12);  border-color: rgba(245,158,11,.3); color: #fbbf24; }
.metric-badge.ttfb { background: rgba(239,68,68,.12);   border-color: rgba(239,68,68,.3);  color: #f87171; }

/* ── Layout ── */
main { max-width: 1100px; margin: 0 auto; padding: 2rem 1.2rem 5rem; }

/* ── Card ── */
.card {
  background: var(--surface); border: 1px solid var(--border);
  border-radius: var(--radius); box-shadow: var(--shadow);
  padding: 2rem; margin-bottom: 1.8rem;
}
.card-title {
  font-size: .8rem; font-weight: 700; letter-spacing: .1em;
  text-transform: uppercase; color: var(--accent); margin-bottom: 1.4rem;
}

/* ── Input mode selector ── */
input[type="radio"] {
  accent-color: var(--accent);
  width: 1.1rem;
  height: 1.1rem;
}

/* ── Upload form ── */
.upload-zone {
  border: 2px dashed var(--border); border-radius: var(--radius);
  padding: 2.5rem 2rem; text-align: center; cursor: pointer;
  transition: border-color .2s, background .2s;
  background: var(--surface2);
  position: relative;
}
.upload-zone:hover, .upload-zone.drag-over {
  border-color: var(--accent); background: rgba(79,142,247,.06);
}
.upload-zone input[type="file"] {
  position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.upload-icon { font-size: 2.5rem; margin-bottom: .8rem; opacity: .7; }
.upload-zone h3 { font-size: 1rem; font-weight: 600; color: var(--text); }
.upload-zone p  { font-size: .85rem; color: var(--muted); margin-top: .3rem; }
.upload-zone .filename {
  margin-top: .8rem; display: inline-block;
  background: rgba(79,142,247,.15); border: 1px solid rgba(79,142,247,.3);
  border-radius: 6px; padding: .3rem .8rem; font-size: .82rem; color: var(--accent);
}

.form-row { display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 1.4rem; align-items: flex-end; }
.form-group { display: flex; flex-direction: column; gap: .4rem; }
.form-group label { font-size: .8rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .06em; }

select {
  padding: .65rem 1rem; background: var(--surface2); border: 1px solid var(--border);
  border-radius: 8px; color: var(--text); font-size: .9rem; cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right .75rem center;
  padding-right: 2.2rem;
}
select:focus { outline: none; border-color: var(--accent); }

.input-field {
  padding: .65rem 1rem; background: var(--surface2); border: 1px solid var(--border);
  border-radius: 8px; color: var(--text); font-size: .9rem; width: 100%;
}
.input-field:focus { outline: none; border-color: var(--accent); }

.btn-primary {
  padding: .72rem 2rem; background: linear-gradient(135deg, var(--accent), var(--accent2));
  color: #fff; border: none; border-radius: 8px;
  font-size: .95rem; font-weight: 700; cursor: pointer;
  transition: opacity .2s, transform .1s; letter-spacing: .01em;
  display: flex; align-items: center; gap: .5rem;
}
.btn-primary:hover { opacity: .9; }
.btn-primary:active { transform: scale(.98); }
.btn-primary:disabled { opacity: .4; cursor: not-allowed; }

/* ── Progress ── */
#progress-section { display: none; }
.progress-header {
  display: flex; justify-content: space-between; align-items: center; margin-bottom: .8rem;
}
.progress-label { font-size: .9rem; font-weight: 600; }
.progress-count  { font-size: .85rem; color: var(--muted); }
.progress-bar-wrap {
  height: 10px; background: var(--surface2); border-radius: 999px; overflow: hidden;
  border: 1px solid var(--border);
}
.progress-bar-fill {
  height: 100%; border-radius: 999px; width: 0%;
  background: linear-gradient(90deg, var(--accent), var(--accent2));
  transition: width .3s ease;
}
.progress-status { margin-top: .7rem; font-size: .82rem; color: var(--muted); min-height: 1.2em; }

.batch-grid {
  display: flex; flex-wrap: wrap; gap: .5rem; margin-top: 1rem;
}
.batch-pill {
  padding: .25rem .7rem; border-radius: 999px; font-size: .75rem; font-weight: 600;
  border: 1px solid var(--border); background: var(--surface2); color: var(--muted);
  transition: all .2s;
}
.batch-pill.running { border-color: var(--accent); background: rgba(79,142,247,.1); color: var(--accent); }
.batch-pill.done    { border-color: var(--good);   background: rgba(34,197,94,.1);  color: var(--good);  }
.batch-pill.error   { border-color: var(--bad);    background: rgba(239,68,68,.1);  color: var(--bad);   }

/* ── Summary stats ── */
#summary-section { display: none; }
.stats-grid {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem;
  margin-bottom: 1.5rem;
}
.stat-card {
  background: var(--surface2); border: 1px solid var(--border);
  border-radius: 10px; padding: 1.2rem; text-align: center;
}
.stat-card .num  { font-size: 2.2rem; font-weight: 800; line-height: 1; }
.stat-card .lbl  { font-size: .75rem; color: var(--muted); margin-top: .4rem; text-transform: uppercase; letter-spacing: .06em; }
.stat-card.c-total .num  { color: var(--accent); }
.stat-card.c-good  .num  { color: var(--good); }
.stat-card.c-fail  .num  { color: var(--bad); }
.stat-card.c-rate  .num  { color: var(--warn); }

/* ── Alert ── */
.alert {
  display: flex; align-items: flex-start; gap: .8rem;
  padding: 1rem 1.2rem; border-radius: 10px; font-size: .88rem; margin-bottom: 1rem;
}
.alert-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: .05rem; }
.alert-info    { background: rgba(79,142,247,.1);  border: 1px solid rgba(79,142,247,.25); }
.alert-success { background: rgba(34,197,94,.1);   border: 1px solid rgba(34,197,94,.25); }
.alert-danger  { background: rgba(239,68,68,.1);   border: 1px solid rgba(239,68,68,.25); }

/* ── Toolbar ── */
.toolbar {
  display: flex; flex-wrap: wrap; gap: .8rem; align-items: center;
  margin-bottom: 1.2rem;
}
.toolbar-search {
  flex: 1; min-width: 200px;
  padding: .55rem 1rem; background: var(--surface2); border: 1px solid var(--border);
  border-radius: 8px; color: var(--text); font-size: .88rem;
}
.toolbar-search:focus { outline: none; border-color: var(--accent); }
.btn-export {
  padding: .55rem 1.2rem; background: rgba(34,197,94,.15);
  border: 1px solid rgba(34,197,94,.3); color: var(--good);
  border-radius: 8px; font-size: .85rem; font-weight: 600; cursor: pointer;
  transition: background .2s;
}
.btn-export:hover { background: rgba(34,197,94,.25); }

/* ── Filter buttons ── */
.filter-btn {
  padding: .6rem 1.3rem; background: var(--surface2);
  border: 2px solid var(--border); color: var(--muted);
  border-radius: 8px; font-size: .88rem; font-weight: 600; cursor: pointer;
  transition: all .2s; display: inline-flex; align-items: center; gap: .5rem;
}
.filter-btn:hover { border-color: var(--accent); color: var(--text); }
.filter-btn.active {
  background: linear-gradient(135deg, var(--accent), var(--accent2));
  border-color: var(--accent); color: #fff;
}
.filter-btn span { font-size: 1.1rem; }

/* ── Table ── */
.table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid var(--border); }
table {
  width: 100%; border-collapse: collapse; font-size: .84rem;
  min-width: 800px;
}
thead th {
  background: var(--surface2); padding: .85rem 1rem;
  text-align: left; font-weight: 700; font-size: .75rem;
  text-transform: uppercase; letter-spacing: .07em; color: var(--muted);
  border-bottom: 1px solid var(--border); white-space: nowrap;
  cursor: pointer; user-select: none;
}
thead th:hover { color: var(--text); }
thead th .sort-arrow { opacity: .4; margin-left: .3rem; }
thead th.sorted .sort-arrow { opacity: 1; color: var(--accent); }
tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: rgba(79,142,247,.06); }
tbody td { padding: .75rem 1rem; vertical-align: middle; }
.url-cell { max-width: 360px; }
.url-cell a {
  color: var(--accent); text-decoration: none; font-size: .83rem;
  display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.url-cell a:hover { text-decoration: underline; }

/* ── Metric chips ── */
.chip {
  display: inline-flex; align-items: center; gap: .3rem;
  padding: .28rem .7rem; border-radius: 999px;
  font-size: .78rem; font-weight: 700; white-space: nowrap;
}
.chip-good { background: rgba(34,197,94,.15);  border: 1px solid rgba(34,197,94,.3);  color: #4ade80; }
.chip-warn { background: rgba(245,158,11,.12); border: 1px solid rgba(245,158,11,.3); color: #fbbf24; }
.chip-bad  { background: rgba(239,68,68,.12);  border: 1px solid rgba(239,68,68,.3);  color: #f87171; }
.chip-na   { background: rgba(100,116,139,.1); border: 1px solid rgba(100,116,139,.2); color: var(--muted); }

.row-num { color: var(--muted); font-size: .8rem; }

/* ── Loading spinner ── */
@keyframes spin { to { transform: rotate(360deg); } }
.spinner {
  width: 16px; height: 16px; border: 2px solid rgba(255,255,255,.3);
  border-top-color: #fff; border-radius: 50%;
  animation: spin .7s linear infinite; display: inline-block;
}

/* ── Scrollbar ── */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: var(--surface); }
::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

/* ── Responsive ── */
@media (max-width: 600px) {
  .header { padding: 1.5rem 1rem 1.2rem; }
  main { padding: 1.2rem .8rem 4rem; }
  .card { padding: 1.2rem; }
}

/* ── Login Page ── */
.login-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  background: linear-gradient(135deg, #1e2a4a 0%, #0f1117 100%);
}
.login-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 3rem;
  width: 100%;
  max-width: 420px;
}
.login-header {
  text-align: center;
  margin-bottom: 2.5rem;
}
.login-header h1 {
  font-size: 1.8rem;
  font-weight: 800;
  background: linear-gradient(135deg, #fff 30%, #4f8ef7);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: .5rem;
}
.login-header p {
  color: var(--muted);
  font-size: .9rem;
}
.login-form .form-group {
  margin-bottom: 1.5rem;
}
.login-form label {
  display: block;
  font-size: .85rem;
  font-weight: 600;
  color: var(--text);
  margin-bottom: .5rem;
}
.login-form input[type="text"],
.login-form input[type="password"] {
  width: 100%;
  padding: .8rem 1rem;
  background: var(--surface2);
  border: 1px solid var(--border);
  border-radius: 8px;
  color: var(--text);
  font-size: .95rem;
  transition: border-color .2s;
}
.login-form input[type="text"]:focus,
.login-form input[type="password"]:focus {
  outline: none;
  border-color: var(--accent);
}
.login-form .btn-login {
  width: 100%;
  padding: .9rem 2rem;
  background: linear-gradient(135deg, var(--accent), var(--accent2));
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
  transition: opacity .2s, transform .1s;
  margin-top: .5rem;
}
.login-form .btn-login:hover {
  opacity: .9;
}
.login-form .btn-login:active {
  transform: scale(.98);
}
.login-error {
  background: rgba(239,68,68,.1);
  border: 1px solid rgba(239,68,68,.3);
  color: #f87171;
  padding: .8rem 1rem;
  border-radius: 8px;
  font-size: .88rem;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: .6rem;
}
.logout-btn {
  position: absolute;
  top: 1rem;
  right: 1rem;
  padding: .5rem 1.2rem;
  background: rgba(239,68,68,.15);
  border: 1px solid rgba(239,68,68,.3);
  color: #f87171;
  border-radius: 8px;
  font-size: .82rem;
  font-weight: 600;
  cursor: pointer;
  transition: background .2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  z-index: 10;
}
.logout-btn:hover {
  background: rgba(239,68,68,.25);
}
.user-info {
  position: absolute;
  top: 1rem;
  right: 8rem;
  padding: .5rem 1rem;
  background: rgba(79,142,247,.15);
  border: 1px solid rgba(79,142,247,.3);
  color: var(--accent);
  border-radius: 8px;
  font-size: .82rem;
  font-weight: 600;
  z-index: 10;
  display: inline-flex;
  align-items: center;
  gap: .4rem;
}
</style>
</head>
<body>

<?php if (!$isAuthenticated): ?>
<!-- ── Login Page ── -->
<div class="login-container">
  <div class="login-card">
    <div class="login-header">
      <h1>&#9889; CWV Good URL Finder</h1>
      <p>Please login to continue</p>
    </div>
    
    <?php if (isset($loginError)): ?>
    <div class="login-error">
      <span>&#9888;</span>
      <span><?php echo htmlspecialchars($loginError); ?></span>
    </div>
    <?php endif; ?>
    
    <form method="POST" class="login-form">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus>
      </div>
      
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      
      <button type="submit" name="login" class="btn-login">
        Login
      </button>
    </form>
  </div>
</div>

<?php else: ?>
<!-- ── Authenticated Content ── -->

<!-- ── Header ── -->
<div class="header">
  <span class="user-info">
    <span>&#128100;</span>
    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
  </span>
  <a href="?logout=1" class="logout-btn">
    <span>&#128682;</span>
    <span>Logout</span>
  </a>
  
  <h1>&#9889; Core Web Vitals &mdash; Good URL Finder</h1>
  <p>Upload sitemap or enter URLs manually · batch-check every URL · filter &amp; export results</p>
  <div class="metric-badges">
    <span class="metric-badge lcp">LCP &le; 2.5 s</span>
    <span class="metric-badge inp">INP &le; 200 ms</span>
    <span class="metric-badge cls">CLS &le; 0.10</span>
    <span class="metric-badge fcp">FCP &le; 1.8 s</span>
    <span class="metric-badge ttfb">TTFB &le; 800 ms</span>
  </div>
</div>

<main>

  <!-- ── Upload Card ── -->
  <div class="card">
    <div class="card-title">&#128196; Configuration</div>
    <form id="upload-form">

      <!-- Input Mode Selector -->
      <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
        <label style="display: flex; align-items: center; gap: .5rem; cursor: pointer;">
          <input type="radio" name="input-mode" value="sitemap" checked style="cursor: pointer;">
          <span style="font-weight: 600; font-size: .9rem;">Upload Sitemap</span>
        </label>
        <label style="display: flex; align-items: center; gap: .5rem; cursor: pointer;">
          <input type="radio" name="input-mode" value="manual" style="cursor: pointer;">
          <span style="font-weight: 600; font-size: .9rem;">Enter URLs Manually</span>
        </label>
      </div>

      <!-- Sitemap Upload Section -->
      <div id="sitemap-section">
        <div class="upload-zone" id="drop-zone">
          <input type="file" id="sitemap-file" accept=".xml,text/xml,application/xml">
          <div class="upload-icon">&#128196;</div>
          <h3>Drop your sitemap.xml here</h3>
          <p>or click to browse — supports regular sitemaps &amp; sitemap index files</p>
          <div class="filename" id="filename-display" style="display:none"></div>
        </div>
      </div>

      <!-- Manual URL Entry Section -->
      <div id="manual-section" style="display: none;">
        <div style="border: 2px solid var(--border); border-radius: var(--radius); padding: 1.5rem; background: var(--surface2);">
          <label style="display: block; font-size: .8rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .06em; margin-bottom: .6rem;">
            Enter URLs (one per line)
          </label>
          <textarea id="manual-urls" class="input-field" rows="8" 
                    placeholder="https://example.com/page1&#10;https://example.com/page2&#10;https://example.com/page3"
                    style="resize: vertical; font-family: 'Monaco', 'Courier New', monospace; font-size: .85rem;"></textarea>
          <p style="margin-top: .6rem; font-size: .82rem; color: var(--muted);">
            &#128161; Tip: Enter one URL per line. You can paste multiple URLs at once.
          </p>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Device Strategy</label>
          <select id="strategy">
            <option value="mobile">&#128241; Mobile</option>
            <option value="desktop">&#128187; Desktop</option>
          </select>
        </div>
        <div class="form-group">
          <label>Batch Size</label>
          <select id="batch-size">
            <option value="3">3 URLs / batch</option>
            <option value="5" selected>5 URLs / batch</option>
            <option value="10">10 URLs / batch</option>
          </select>
        </div>
        <div class="form-group" style="flex:1;min-width:220px">
          <label>PageSpeed Insights API Key</label>
          <input type="text" id="api-key" class="input-field"
                 placeholder="AIza..." autocomplete="off">
        </div>
        <div class="form-group" style="align-self:flex-end">
          <button type="submit" class="btn-primary" id="run-btn">
            <span id="btn-icon">&#9654;</span>
            <span id="btn-label">Analyse URLs</span>
          </button>
        </div>
      </div>

    </form>
  </div>

  <!-- ── Progress Card ── -->
  <div class="card" id="progress-section">
    <div class="card-title">&#9881; Processing</div>
    <div class="progress-header">
      <span class="progress-label" id="progress-label">Starting&hellip;</span>
      <span class="progress-count" id="progress-count">0 / 0</span>
    </div>
    <div class="progress-bar-wrap">
      <div class="progress-bar-fill" id="progress-bar"></div>
    </div>
    <div class="progress-status" id="progress-status">&nbsp;</div>
    <div class="batch-grid" id="batch-pills"></div>
  </div>

  <!-- ── Results Card ── -->
  <div class="card" id="summary-section">
    <div class="card-title">&#9989; Results</div>

    <div class="stats-grid" id="stats-grid"></div>

    <div id="alerts-area"></div>

    <!-- Filter Toggle Buttons -->
    <div style="display: flex; gap: .8rem; margin-bottom: 1.2rem; flex-wrap: wrap;">
      <button class="filter-btn active" data-filter="good" onclick="toggleFilter('good')">
        <span>&#9989;</span> Good URLs
      </button>
      <button class="filter-btn" data-filter="bad" onclick="toggleFilter('bad')">
        <span>&#10060;</span> Bad URLs
      </button>
      <button class="filter-btn" data-filter="all" onclick="toggleFilter('all')">
        <span>&#128202;</span> All URLs
      </button>
    </div>

    <div class="toolbar">
      <input type="text" class="toolbar-search" id="search-box" placeholder="&#128269;  Filter URLs&hellip;" oninput="filterTable()">
      <button class="btn-export" id="export-csv-btn" onclick="exportCSV()">&#8615; Export CSV</button>
      <button class="btn-export" style="background:rgba(79,142,247,.15);border-color:rgba(79,142,247,.3);color:var(--accent)" id="copy-urls-btn" onclick="copyUrls()">&#128203; Copy URLs</button>
    </div>

    <div class="table-wrap">
      <table id="results-table">
        <thead>
          <tr>
            <th onclick="sortTable(0)">#<span class="sort-arrow">&#8597;</span></th>
            <th onclick="sortTable(1)">URL<span class="sort-arrow">&#8597;</span></th>
            <th onclick="sortTable(2)">LCP<span class="sort-arrow">&#8597;</span></th>
            <th onclick="sortTable(3)">INP<span class="sort-arrow">&#8597;</span></th>
            <th onclick="sortTable(4)">CLS<span class="sort-arrow">&#8597;</span></th>
            <th onclick="sortTable(5)">FCP<span class="sort-arrow">&#8597;</span></th>
            <th onclick="sortTable(6)">TTFB<span class="sort-arrow">&#8597;</span></th>
          </tr>
        </thead>
        <tbody id="results-body"></tbody>
      </table>
    </div>
    <p id="no-results" style="display:none;text-align:center;padding:2rem;color:var(--muted)">
      No URLs passed all five CWV thresholds for this strategy.
    </p>
  </div>

</main>

<script>
// ── Thresholds ────────────────────────────────────────────────────────────────
const THRESH = { lcp:2500, inp:200, cls:0.1, fcp:1800, ttfb:800 };
const UNITS  = { lcp:'ms',  inp:'ms', cls:'',  fcp:'ms', ttfb:'ms' };
const METRIC_KEYS = ['lcp','inp','cls','fcp','ttfb'];

// ── State ─────────────────────────────────────────────────────────────────────
let goodResults = [];         // URLs that pass all CWV thresholds
let badResults  = [];         // URLs that fail any CWV threshold
let allResults  = [];         // All processed URLs
let totalUrls   = 0;
let doneUrls    = 0;
let sortCol     = -1;
let sortAsc     = true;
let currentFilter = 'good';   // Current filter: 'good', 'bad', or 'all'

// ── Input mode switching ──────────────────────────────────────────────────────
const sitemapSection = document.getElementById('sitemap-section');
const manualSection = document.getElementById('manual-section');
const modeRadios = document.querySelectorAll('input[name="input-mode"]');

modeRadios.forEach(radio => {
  radio.addEventListener('change', e => {
    if (e.target.value === 'sitemap') {
      sitemapSection.style.display = 'block';
      manualSection.style.display = 'none';
    } else {
      sitemapSection.style.display = 'none';
      manualSection.style.display = 'block';
    }
  });
});

// ── File / drag-drop ──────────────────────────────────────────────────────────
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('sitemap-file');
const filenameDisplay = document.getElementById('filename-display');

fileInput.addEventListener('change', () => showFilename(fileInput.files[0]));
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
dropZone.addEventListener('drop', e => {
  e.preventDefault(); dropZone.classList.remove('drag-over');
  if (e.dataTransfer.files[0]) { fileInput.files = e.dataTransfer.files; showFilename(e.dataTransfer.files[0]); }
});

function showFilename(file) {
  if (!file) return;
  filenameDisplay.textContent = file.name + '  (' + (file.size/1024).toFixed(1) + ' KB)';
  filenameDisplay.style.display = 'inline-block';
}

// ── XML parsing (client-side) ─────────────────────────────────────────────────
function parseXML(xmlStr) {
  const parser = new DOMParser();
  const doc    = parser.parseFromString(xmlStr, 'application/xml');
  const locs   = [...doc.querySelectorAll('url > loc, sitemap > loc')];
  return [...new Set(locs.map(l => l.textContent.trim()).filter(u => u.startsWith('http')))];
}

// ── Form submit ───────────────────────────────────────────────────────────────
document.getElementById('upload-form').addEventListener('submit', async e => {
  e.preventDefault();

  const inputMode = document.querySelector('input[name="input-mode"]:checked').value;
  const apiKey    = document.getElementById('api-key').value.trim();
  const strat     = document.getElementById('strategy').value;
  const batchSz   = parseInt(document.getElementById('batch-size').value, 10);

  if (!apiKey) return alert('Please enter your PageSpeed Insights API key.');

  // Reset state
  goodResults = []; badResults = []; allResults = [];
  totalUrls = 0; doneUrls = 0; currentFilter = 'good'; rowCounter = 0;
  document.getElementById('results-body').innerHTML = '';
  document.getElementById('alerts-area').innerHTML  = '';
  document.getElementById('batch-pills').innerHTML  = '';
  document.getElementById('summary-section').style.display = 'none';
  document.getElementById('no-results').style.display = 'none';
  
  // Reset filter buttons
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.filter === 'good');
  });

  let urls = [];

  // Get URLs based on input mode
  if (inputMode === 'sitemap') {
    const file = fileInput.files[0];
    if (!file) return alert('Please select a sitemap.xml file.');
    const xmlStr = await file.text();
    urls = parseXML(xmlStr);
  } else {
    // Manual URL entry
    const manualInput = document.getElementById('manual-urls').value.trim();
    if (!manualInput) return alert('Please enter at least one URL.');
    
    // Parse URLs from textarea (one per line)
    urls = manualInput.split('\n')
      .map(url => url.trim())
      .filter(url => url && url.startsWith('http'));
    
    // Remove duplicates
    urls = [...new Set(urls)];
  }

  if (urls.length === 0) {
    return showAlert('danger','&#9888;','No valid URLs found. Please check your input.');
  }

  totalUrls = urls.length;
  setProgress(0, totalUrls, 'Preparing batches&hellip;');
  document.getElementById('progress-section').style.display = 'block';
  document.getElementById('summary-section').style.display  = 'block';
  renderStats();

  setRunBtn(true);

  // Build batches
  const batches = [];
  for (let i = 0; i < urls.length; i += batchSz) {
    batches.push(urls.slice(i, i + batchSz));
  }

  // Create batch pills
  batches.forEach((_, i) => {
    const pill = document.createElement('span');
    pill.className = 'batch-pill';
    pill.id = `pill-${i}`;
    pill.textContent = `Batch ${i+1}`;
    document.getElementById('batch-pills').appendChild(pill);
  });

  // Process batches sequentially (each batch fires parallel requests server-side)
  for (let i = 0; i < batches.length; i++) {
    const pill = document.getElementById(`pill-${i}`);
    pill.classList.add('running');
    setProgressStatus(`Running batch ${i+1} of ${batches.length} — checking ${batches[i].length} URL(s)&hellip;`);

    try {
      const res = await fetch('process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ urls: batches[i], strategy: strat, api_key: apiKey }),
      });

      // Read body as text first so we can show it in the error if it isn't JSON
      const bodyText = await res.text();
      let data;
      try {
        data = JSON.parse(bodyText);
      } catch (_) {
        // PHP returned HTML (fatal error / wrong path). Show the raw preview.
        const preview = bodyText.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim().slice(0, 200);
        throw new Error(`Server returned non-JSON: ${preview}`);
      }

      if (!res.ok || data.error) throw new Error(data.error || `HTTP ${res.status}`);

      data.results.forEach(r => {
        doneUrls++;
        allResults.push(r);
        if (isGood(r)) {
          goodResults.push(r);
          if (currentFilter === 'good' || currentFilter === 'all') renderRow(r, 'good');
        } else {
          badResults.push(r);
          if (currentFilter === 'bad' || currentFilter === 'all') renderRow(r, 'bad');
        }
      });

      pill.classList.remove('running');
      pill.classList.add('done');

    } catch (err) {
      pill.classList.remove('running');
      pill.classList.add('error');
      doneUrls += batches[i].length;
      showAlert('danger', '&#9888;', `Batch ${i+1} failed: ${err.message}`);
    }

    setProgress(doneUrls, totalUrls, `Processed ${doneUrls} of ${totalUrls} URLs`);
    renderStats();
  }

  // Done
  setProgressStatus('&#9989; All batches complete!');
  updateNoResults();
  setRunBtn(false);
});

// ── Good check ────────────────────────────────────────────────────────────────
function isGood(r) {
  for (const k of METRIC_KEYS) {
    if (r[k] !== null && r[k] !== undefined) {
      if (k === 'cls' && r[k] > THRESH[k]) return false;
      if (k !== 'cls' && r[k] > THRESH[k]) return false;
    }
  }
  return true;
}

// ── Render a table row ────────────────────────────────────────────────────────
function chipHtml(key, val) {
  if (val === null || val === undefined) return '<span class="chip chip-na">N/A</span>';
  const display = key === 'cls' ? (+val).toFixed(3) : Math.round(val).toLocaleString();
  const cls     = val <= THRESH[key] ? 'chip-good' : (val <= THRESH[key]*1.5 ? 'chip-warn' : 'chip-bad');
  const unit    = UNITS[key];
  return `<span class="chip ${cls}">${display}${unit ? ' '+unit : ''}</span>`;
}

let rowCounter = 0;
function renderRow(r, status = 'good') {
  rowCounter++;
  const tr = document.createElement('tr');
  tr.setAttribute('data-url', r.url.toLowerCase());
  tr.setAttribute('data-status', status);
  tr.innerHTML = `
    <td class="row-num">${rowCounter}</td>
    <td class="url-cell"><a href="${escHtml(r.url)}" target="_blank" rel="noopener" title="${escHtml(r.url)}">${escHtml(r.url)}</a></td>
    <td>${chipHtml('lcp', r.lcp)}</td>
    <td>${chipHtml('inp', r.inp)}</td>
    <td>${chipHtml('cls', r.cls)}</td>
    <td>${chipHtml('fcp', r.fcp)}</td>
    <td>${chipHtml('ttfb', r.ttfb)}</td>`;
  document.getElementById('results-body').appendChild(tr);
}

// ── Stats ─────────────────────────────────────────────────────────────────────
function renderStats() {
  const good = goodResults.length;
  const fail = badResults.length;
  const rate = doneUrls > 0 ? Math.round(good / doneUrls * 100) : 0;
  document.getElementById('stats-grid').innerHTML = `
    <div class="stat-card c-total"><div class="num">${totalUrls}</div><div class="lbl">Total URLs</div></div>
    <div class="stat-card c-good"><div class="num">${good}</div><div class="lbl">Good CWV</div></div>
    <div class="stat-card c-fail"><div class="num">${fail}</div><div class="lbl">Need Work</div></div>
    <div class="stat-card c-rate"><div class="num">${rate}%</div><div class="lbl">Pass Rate</div></div>
    <div class="stat-card c-total"><div class="num">${doneUrls}</div><div class="lbl">Checked</div></div>`;
}

// ── Progress helpers ──────────────────────────────────────────────────────────
function setProgress(done, total, label) {
  const pct = total > 0 ? Math.round(done / total * 100) : 0;
  document.getElementById('progress-bar').style.width   = pct + '%';
  document.getElementById('progress-count').textContent = `${done} / ${total}`;
  document.getElementById('progress-label').textContent = label;
}
function setProgressStatus(html) {
  document.getElementById('progress-status').innerHTML = html;
}
function setRunBtn(loading) {
  const btn  = document.getElementById('run-btn');
  const icon = document.getElementById('btn-icon');
  const lbl  = document.getElementById('btn-label');
  btn.disabled   = loading;
  icon.innerHTML = loading ? '<span class="spinner"></span>' : '&#9654;';
  lbl.textContent = loading ? 'Analysing…' : 'Analyse URLs';
}

// ── Alert ─────────────────────────────────────────────────────────────────────
function showAlert(type, icon, msg) {
  const div = document.createElement('div');
  div.className = `alert alert-${type}`;
  div.innerHTML = `<span class="alert-icon">${icon}</span><span>${msg}</span>`;
  document.getElementById('alerts-area').appendChild(div);
}

// ── Toggle Filter ─────────────────────────────────────────────────────────────
function toggleFilter(filter) {
  currentFilter = filter;
  
  // Update button states
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.filter === filter);
  });
  
  // Re-render table
  document.getElementById('results-body').innerHTML = '';
  rowCounter = 0;
  
  if (filter === 'good') {
    goodResults.forEach(r => renderRow(r, 'good'));
  } else if (filter === 'bad') {
    badResults.forEach(r => renderRow(r, 'bad'));
  } else {
    // All URLs
    allResults.forEach(r => renderRow(r, isGood(r) ? 'good' : 'bad'));
  }
  
  updateNoResults();
  filterTable(); // Re-apply search filter if any
}

// ── Update no results message ─────────────────────────────────────────────────
function updateNoResults() {
  const noResultsEl = document.getElementById('no-results');
  let shouldShow = false;
  let message = '';
  
  if (currentFilter === 'good' && goodResults.length === 0) {
    shouldShow = true;
    message = 'No URLs passed all five CWV thresholds for this strategy.';
  } else if (currentFilter === 'bad' && badResults.length === 0) {
    shouldShow = true;
    message = 'Great! All URLs passed the CWV thresholds.';
  } else if (currentFilter === 'all' && allResults.length === 0) {
    shouldShow = true;
    message = 'No results available yet.';
  }
  
  noResultsEl.textContent = message;
  noResultsEl.style.display = shouldShow ? 'block' : 'none';
}

// ── Filter ────────────────────────────────────────────────────────────────────
function filterTable() {
  const q = document.getElementById('search-box').value.toLowerCase();
  document.querySelectorAll('#results-body tr').forEach(tr => {
    tr.style.display = tr.dataset.url.includes(q) ? '' : 'none';
  });
}

// ── Sort ──────────────────────────────────────────────────────────────────────
function sortTable(col) {
  sortAsc = sortCol === col ? !sortAsc : true;
  sortCol = col;
  document.querySelectorAll('thead th').forEach((th, i) => {
    th.classList.toggle('sorted', i === col);
    const arr = th.querySelector('.sort-arrow');
    if (arr) arr.textContent = i === col ? (sortAsc ? ' ↑' : ' ↓') : ' ↕';
  });
  const tbody  = document.getElementById('results-body');
  const rows   = [...tbody.querySelectorAll('tr')];
  rows.sort((a, b) => {
    const av = a.cells[col]?.textContent.replace(/[^0-9.]/g, '') || '';
    const bv = b.cells[col]?.textContent.replace(/[^0-9.]/g, '') || '';
    const an = parseFloat(av) || 0, bn = parseFloat(bv) || 0;
    const cmp = col <= 1 ? av.localeCompare(bv) : an - bn;
    return sortAsc ? cmp : -cmp;
  });
  rows.forEach(r => tbody.appendChild(r));
}

// ── Export CSV ────────────────────────────────────────────────────────────────
function exportCSV() {
  const data = currentFilter === 'good' ? goodResults : 
               currentFilter === 'bad' ? badResults : allResults;
  
  if (data.length === 0) {
    alert('No URLs to export for the current filter.');
    return;
  }
  
  const header = ['#','URL','LCP (ms)','INP (ms)','CLS','FCP (ms)','TTFB (ms)'];
  const rows = data.map((r, i) => [
    i+1, r.url,
    r.lcp  ?? 'N/A', r.inp  ?? 'N/A',
    r.cls !== null ? (+r.cls).toFixed(3) : 'N/A',
    r.fcp  ?? 'N/A', r.ttfb ?? 'N/A',
  ]);
  const csv = [header, ...rows].map(r => r.map(c => `"${String(c).replace(/"/g,'""')}"`).join(',')).join('\n');
  
  const filename = currentFilter === 'good' ? 'good-cwv-urls.csv' : 
                   currentFilter === 'bad' ? 'bad-cwv-urls.csv' : 'all-cwv-urls.csv';
  dl(filename, 'text/csv', csv);
}

// ── Copy URLs ─────────────────────────────────────────────────────────────────
function copyUrls() {
  const data = currentFilter === 'good' ? goodResults : 
               currentFilter === 'bad' ? badResults : allResults;
  
  if (data.length === 0) {
    alert('No URLs to copy for the current filter.');
    return;
  }
  
  navigator.clipboard.writeText(data.map(r => r.url).join('\n'))
    .then(() => alert(`Copied ${data.length} URL(s) to clipboard.`));
}

function dl(name, mime, content) {
  const a = document.createElement('a');
  a.href = `data:${mime};charset=utf-8,` + encodeURIComponent(content);
  a.download = name; a.click();
}
function escHtml(s) {
  return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>

<?php endif; ?>

</body>
</html>
