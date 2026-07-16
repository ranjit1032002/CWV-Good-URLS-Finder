# ⚡ Quick Deploy Checklist

## 📋 Pre-Deployment Checklist

- [x] Authentication implemented (username: ranjit, password: ranjit)
- [x] Dockerfile created
- [x] render.yaml configured
- [x] .gitignore added
- [x] Session management configured
- [x] All files ready for deployment

## 🚀 Deploy in 5 Minutes

### Step 1: Push to GitHub (2 minutes)
```bash
# Run the setup script
./deploy-setup.sh

# OR manually:
git init
git add .
git commit -m "Initial deployment"
git remote add origin https://github.com/YOUR_USERNAME/cwv-url-finder.git
git branch -M main
git push -u origin main
```

### Step 2: Deploy on Render (3 minutes)
1. Go to https://dashboard.render.com
2. Click **"New +"** → **"Web Service"**
3. Connect your **GitHub** account
4. Select your **cwv-url-finder** repository
5. Configure:
   - **Name**: cwv-url-finder
   - **Runtime**: Docker (auto-detected)
   - **Plan**: Free
6. Click **"Create Web Service"**
7. Wait 3-5 minutes for deployment

### Step 3: Access Your App
- URL: `https://cwv-url-finder.onrender.com`
- Username: `ranjit`
- Password: `ranjit`

## 🔧 Configuration Files

| File | Purpose |
|------|---------|
| `Dockerfile` | Docker container configuration |
| `render.yaml` | Render.com deployment settings |
| `.gitignore` | Files to exclude from Git |
| `.dockerignore` | Files to exclude from Docker |
| `index.php` | Main application with auth |
| `process.php` | Backend API processor |

## 🎯 What's Deployed

✅ Secure authentication  
✅ Sitemap XML upload  
✅ Manual URL entry  
✅ Batch processing  
✅ Core Web Vitals analysis  
✅ Smart filtering (Good/Bad/All)  
✅ CSV export  
✅ Copy to clipboard  
✅ Session management  

## ⚠️ Important Notes

1. **Free Tier Limitations**:
   - App sleeps after 15 min of inactivity
   - First load takes 30-60 seconds after sleep
   - 750 hours/month runtime limit

2. **API Key Required**:
   - Get free key from: https://developers.google.com/speed/docs/insights/v5/get-started
   - Enter in the app after login

3. **Security**:
   - Change default password in production
   - Edit line 16 in `index.php`

## 🔄 Update Deployment

```bash
# Make your changes
git add .
git commit -m "Your update message"
git push origin main

# Render auto-deploys (if enabled)
# Or manually trigger deploy in Render dashboard
```

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Build fails | Check Render logs, verify Dockerfile |
| App not loading | Wait for "Live" status, check logs |
| Session issues | Sessions reset on free tier restarts |
| Slow first load | Normal for free tier after inactivity |

## 📞 Help & Resources

- **Detailed Guide**: See `DEPLOYMENT_GUIDE.md`
- **Render Docs**: https://render.com/docs
- **GitHub Repo**: Your repository URL

## 🎉 Success Checklist

After deployment, verify:

- [ ] Login page loads
- [ ] Can login with credentials
- [ ] Can upload sitemap OR enter URLs manually
- [ ] Can enter API key
- [ ] Can analyze URLs
- [ ] Results display correctly
- [ ] Can filter Good/Bad/All URLs
- [ ] Can export CSV
- [ ] Can copy URLs
- [ ] Logout works

## 💡 Pro Tips

1. **Custom Domain**: Available on paid plans
2. **Environment Variables**: Store API key securely
3. **Monitoring**: Check Render dashboard for logs
4. **Auto-Deploy**: Enable in Render settings
5. **Backups**: Keep local copy of your code

---

**Ready to deploy?** Run `./deploy-setup.sh` to get started! 🚀
