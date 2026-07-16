# 🌐 Core Web Vitals - Good URL Finder

A powerful web application to analyze and identify URLs that pass Core Web Vitals thresholds using Google's PageSpeed Insights API.

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.2-purple.svg)

## ✨ Features

### 🔐 Secure Authentication
- Login system to protect access
- Session-based authentication
- Secure logout functionality

### 📊 URL Analysis
- **Batch Processing**: Analyze multiple URLs simultaneously
- **Two Input Methods**:
  - Upload sitemap.xml files
  - Manual URL entry (one per line)
- **Core Web Vitals Tracking**:
  - LCP (Largest Contentful Paint) ≤ 2.5s
  - INP (Interaction to Next Paint) ≤ 200ms
  - CLS (Cumulative Layout Shift) ≤ 0.10
  - FCP (First Contentful Paint) ≤ 1.8s
  - TTFB (Time to First Byte) ≤ 800ms

### 🎯 Smart Filtering
- **Good URLs**: View only URLs passing all thresholds
- **Bad URLs**: View URLs that need optimization
- **All URLs**: Complete overview of all results

### 📤 Export Options
- Export to CSV (filtered by current view)
- Copy URLs to clipboard
- Download results for offline analysis

### 📱 Modern UI
- Dark theme with gradient accents
- Responsive design
- Real-time progress tracking
- Color-coded metrics (green/yellow/red)
- Interactive sorting and filtering

## 🚀 Quick Start

### Local Development

1. **Prerequisites**:
   - PHP 8.0 or higher
   - Google PageSpeed Insights API key ([Get one here](https://developers.google.com/speed/docs/insights/v5/get-started))

2. **Clone the repository**:
   ```bash
   git clone https://github.com/YOUR_USERNAME/cwv-url-finder.git
   cd cwv-url-finder
   ```

3. **Start the development server**:
   ```bash
   php -S localhost:8888
   ```

4. **Open in browser**:
   ```
   http://localhost:8888/index.php
   ```

5. **Login**:
   - Username: `ranjit`
   - Password: `ranjit`

## 🌍 Deploy to Render.com (Free)

See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) for detailed step-by-step instructions to deploy on Render.com's free tier.

**Quick Deploy Steps**:
1. Push code to GitHub
2. Connect GitHub to Render
3. Create new Web Service
4. Select your repository
5. Deploy! 🎉

## 📋 Login Credentials

**Default credentials:**
- Username: ``
- Password: ``

**⚠️ Security**: Change these credentials in production by editing `index.php` line 16.

## 🛠️ Configuration

### Get PageSpeed Insights API Key

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project (or select existing)
3. Enable PageSpeed Insights API
4. Create credentials (API Key)
5. Copy your API key
6. Enter it in the application when prompted

### Customize Thresholds

Edit the thresholds in `index.php` (around line 540):

```javascript
const THRESH = { 
  lcp: 2500,   // milliseconds
  inp: 200,    // milliseconds
  cls: 0.1,    // score
  fcp: 1800,   // milliseconds
  ttfb: 800    // milliseconds
};
```

### Batch Size Configuration

- Small batches (3-5): Slower but more reliable
- Large batches (10+): Faster but may hit rate limits

## 📁 Project Structure

```
cwv-url-finder/
├── index.php           # Main application with UI and authentication
├── process.php         # Backend API processing
├── Dockerfile          # Docker configuration for deployment
├── render.yaml         # Render.com deployment config
├── .gitignore          # Git ignore rules
├── .dockerignore       # Docker ignore rules
├── README.md           # This file
└── DEPLOYMENT_GUIDE.md # Detailed deployment instructions
```

## 🔧 Technologies Used

- **Backend**: PHP 8.2
- **Frontend**: Vanilla JavaScript, HTML5, CSS3
- **API**: Google PageSpeed Insights API v5
- **Deployment**: Docker, Render.com
- **Session Management**: PHP native sessions

## 📊 How It Works

1. **Input**: User uploads sitemap.xml or enters URLs manually
2. **Processing**: URLs are processed in configurable batches
3. **Analysis**: Each URL is checked via PageSpeed Insights API
4. **Filtering**: Results are categorized as good or bad based on thresholds
5. **Output**: View, filter, and export results in various formats

## 🎨 Screenshots

### Login Page
Modern, secure login interface with gradient design.

### Dashboard
Real-time progress tracking with batch processing visualization.

### Results
Color-coded metrics table with filtering and export options.

## 🐛 Troubleshooting

### API Rate Limits
- Use smaller batch sizes (3-5)
- Add delays between requests (configured in process.php)
- Use your own API key (not shared keys)

### Session Issues
- Clear browser cookies
- Check PHP session configuration
- Ensure sessions directory is writable

### Performance
- Render free tier: First load after inactivity takes 30-60 seconds
- Large sitemaps: Process in smaller batches
- Optimize batch size based on API quota

## 🤝 Contributing

Contributions are welcome! Feel free to:
- Report bugs
- Suggest features
- Submit pull requests

## 📄 License

This project is licensed under the MIT License.

## 🙏 Acknowledgments

- Google PageSpeed Insights API
- Render.com for free hosting
- Modern CSS gradients and dark themes

## 📞 Support

For issues, questions, or suggestions:
- Open an issue on GitHub
- Check [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
- Review Render.com documentation

## 🔄 Version History

### v1.0.0 (Current)
- ✅ Authentication system
- ✅ Dual input methods (sitemap/manual)
- ✅ Core Web Vitals analysis
- ✅ Smart filtering (good/bad/all)
- ✅ CSV export functionality
- ✅ Render.com deployment ready
- ✅ Docker containerization

---

**Made with ❤️ for better web performance**

Start optimizing your website's Core Web Vitals today! 🚀
