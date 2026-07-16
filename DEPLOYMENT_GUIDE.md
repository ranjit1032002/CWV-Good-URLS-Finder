# 🚀 Deployment Guide - Render.com (Free Tier)

This guide will walk you through deploying the CWV Good URL Finder application on Render.com's free tier.

## 📋 Prerequisites

1. A GitHub account
2. A Render.com account (free) - Sign up at [https://render.com](https://render.com)
3. Git installed on your local machine

## 🔧 Preparation Steps

### Step 1: Push Your Code to GitHub

1. **Initialize Git repository** (if not already done):
   ```bash
   cd /Users/ranjitsahoo/files/good-urls
   git init
   ```

2. **Add all files**:
   ```bash
   git add .
   ```

3. **Commit your changes**:
   ```bash
   git commit -m "Initial commit - CWV URL Finder with authentication"
   ```

4. **Create a new repository on GitHub**:
   - Go to [https://github.com/new](https://github.com/new)
   - Name it: `cwv-url-finder` (or any name you prefer)
   - Do NOT initialize with README, .gitignore, or license
   - Click "Create repository"

5. **Push to GitHub**:
   ```bash
   git remote add origin https://github.com/YOUR_USERNAME/cwv-url-finder.git
   git branch -M main
   git push -u origin main
   ```

## 🌐 Deploy on Render

### Step 2: Create New Web Service on Render

1. **Login to Render**:
   - Go to [https://dashboard.render.com](https://dashboard.render.com)
   - Sign in with your account

2. **Connect GitHub**:
   - Click "New +" button in the top right
   - Select "Web Service"
   - Click "Connect account" next to GitHub (if not already connected)
   - Authorize Render to access your GitHub repositories

3. **Select Repository**:
   - Find and select your `cwv-url-finder` repository
   - Click "Connect"

### Step 3: Configure Web Service

Fill in the following settings:

**Basic Settings:**
- **Name**: `cwv-url-finder` (or your preferred name)
- **Region**: Choose closest to your location (e.g., Oregon, Frankfurt, Singapore)
- **Branch**: `main`
- **Runtime**: `Docker`

**Instance Type:**
- **Plan**: Select `Free` (automatically selected)

**Advanced Settings** (scroll down):
- Render will automatically detect the `Dockerfile` and `render.yaml`
- No additional configuration needed

### Step 4: Deploy

1. Click **"Create Web Service"** button at the bottom
2. Render will start building and deploying your application
3. Wait for the deployment to complete (usually 3-5 minutes)

### Step 5: Monitor Deployment

You can watch the build logs in real-time:
- Look for messages like:
  - "Building image..."
  - "Pushing image..."
  - "Starting service..."
  - "Live" (deployment complete!)

## ✅ Access Your Application

Once deployed successfully:

1. **Find Your URL**:
   - Your app will be available at: `https://cwv-url-finder.onrender.com` (or your custom name)
   - The URL is shown at the top of your service dashboard

2. **Test the Application**:
   - Open the URL in your browser
   - You should see the login page
   - Login with credentials:
     - **Username**: `ranjit`
     - **Password**: `ranjit`

## 🔐 Login Credentials

**Default credentials:**
- Username: `ranjit`
- Password: `ranjit`

**⚠️ Important Security Note**: After deployment, consider changing these credentials in the `index.php` file (line 16) and redeploy.

## 📊 Features Available After Deployment

- ✅ Secure authentication
- ✅ Upload sitemap.xml files
- ✅ Manual URL entry
- ✅ Batch processing of URLs
- ✅ Core Web Vitals analysis (LCP, INP, CLS, FCP, TTFB)
- ✅ Filter results (Good URLs, Bad URLs, All URLs)
- ✅ Export to CSV
- ✅ Copy URLs to clipboard
- ✅ User session management

## 🛠️ Troubleshooting

### Issue: Build Fails

**Solution**: Check the build logs for errors. Common issues:
- Dockerfile syntax errors
- Missing files
- Network issues (try deploying again)

### Issue: Application Not Loading

**Solution**:
1. Check if the service is "Live" in Render dashboard
2. View the logs in Render dashboard
3. Ensure port 80 is exposed in Dockerfile

### Issue: Session Not Working

**Solution**:
- Sessions are configured to use a local directory
- Render's ephemeral filesystem works fine for sessions on free tier
- Sessions will reset when the service restarts (free tier limitation)

### Issue: Free Tier Limitations

**Important**: Render's free tier has these limitations:
- Apps spin down after 15 minutes of inactivity
- First request after spin-down will be slow (30-60 seconds)
- 750 hours of runtime per month
- Automatic restarts every month

## 🔄 Updating Your Application

To deploy updates:

1. Make changes to your local files
2. Commit and push to GitHub:
   ```bash
   git add .
   git commit -m "Description of changes"
   git push origin main
   ```
3. Render will automatically detect changes and redeploy (if auto-deploy is enabled)
4. Monitor the deployment in Render dashboard

## 🔒 Production Security Tips

1. **Change Default Credentials**:
   ```php
   // In index.php, line 16
   if ($username === 'YOUR_NEW_USERNAME' && $password === 'YOUR_NEW_PASSWORD') {
   ```

2. **Use Environment Variables** (Optional for better security):
   - Go to your service in Render dashboard
   - Navigate to "Environment" tab
   - Add environment variables for username and password
   - Update code to use `$_ENV['USERNAME']` and `$_ENV['PASSWORD']`

3. **Add HTTPS** (Already enabled by Render automatically)

## 📈 Monitoring Your Application

In Render Dashboard, you can:
- View real-time logs
- Monitor CPU and memory usage
- Check deployment history
- Set up custom domains (paid feature)
- Configure environment variables

## 💰 Cost Information

**Free Tier Includes:**
- 750 hours/month of runtime
- Automatic SSL certificates
- GitHub integration
- Automatic deployments
- Basic monitoring

**No credit card required for free tier!**

## 📞 Support

- **Render Documentation**: [https://render.com/docs](https://render.com/docs)
- **Render Community**: [https://community.render.com](https://community.render.com)

## 🎉 Congratulations!

Your CWV Good URL Finder is now live and accessible from anywhere in the world!

**Your Live URL**: `https://your-service-name.onrender.com`

---

**Need Help?** Check the Render logs or GitHub repository for additional support.
