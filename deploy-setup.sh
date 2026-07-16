#!/bin/bash

# CWV URL Finder - Deployment Setup Script
# This script helps prepare your application for deployment on Render.com

echo "🚀 CWV URL Finder - Deployment Setup"
echo "===================================="
echo ""

# Check if git is installed
if ! command -v git &> /dev/null; then
    echo "❌ Git is not installed. Please install Git first."
    exit 1
fi

echo "✅ Git is installed"
echo ""

# Check if already initialized
if [ -d .git ]; then
    echo "✅ Git repository already initialized"
else
    echo "📦 Initializing Git repository..."
    git init
    echo "✅ Git repository initialized"
fi

echo ""
echo "📝 Current Git Status:"
echo "--------------------"
git status --short
echo ""

# Add all files
echo "📦 Adding files to Git..."
git add .
echo "✅ Files added"
echo ""

# Check if there are changes to commit
if git diff --cached --quiet; then
    echo "ℹ️  No changes to commit"
else
    # Commit
    echo "💾 Committing changes..."
    git commit -m "Prepare for Render deployment - Added Docker config and authentication"
    echo "✅ Changes committed"
fi

echo ""
echo "======================================"
echo "✅ Setup Complete!"
echo "======================================"
echo ""
echo "📋 Next Steps:"
echo ""
echo "1. Create a GitHub repository:"
echo "   Go to https://github.com/new"
echo ""
echo "2. Name it: cwv-url-finder (or your choice)"
echo ""
echo "3. Run these commands (replace YOUR_USERNAME):"
echo ""
echo "   git remote add origin https://github.com/YOUR_USERNAME/cwv-url-finder.git"
echo "   git branch -M main"
echo "   git push -u origin main"
echo ""
echo "4. Deploy on Render:"
echo "   - Go to https://dashboard.render.com"
echo "   - Click 'New +' → 'Web Service'"
echo "   - Connect your GitHub repository"
echo "   - Select 'Docker' runtime"
echo "   - Click 'Create Web Service'"
echo ""
echo "5. Access your app at:"
echo "   https://your-service-name.onrender.com"
echo ""
echo "🔐 Login Credentials:"
echo "   Username: ranjit"
echo "   Password: ranjit"
echo ""
echo "📖 For detailed instructions, see DEPLOYMENT_GUIDE.md"
echo ""
