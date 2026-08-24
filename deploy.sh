#!/bin/bash

# Deployment script for Render
# Make this executable: chmod +x deploy.sh

echo "🚀 Preparing deployment to Render..."

# Check if git is initialized
if [ ! -d .git ]; then
    echo "📦 Initializing Git repository..."
    git init
    git branch -M main
fi

# Add all files
echo "📝 Adding files to Git..."
git add .

# Commit changes
echo "💾 Creating commit..."
read -p "Enter commit message (default: 'Deploy to Render'): " commit_msg
commit_msg=${commit_msg:-"Deploy to Render"}
git commit -m "$commit_msg"

# Ask for remote URL if not set
if ! git remote | grep -q origin; then
    echo "🔗 No remote repository found."
    read -p "Enter your GitHub repository URL (e.g., https://github.com/username/repo.git): " repo_url
    git remote add origin "$repo_url"
fi

# Push to GitHub
echo "⬆️  Pushing to GitHub..."
git push -u origin main

echo ""
echo "✅ Code pushed to GitHub successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Go to https://dashboard.render.com/"
echo "2. Click 'New +' → 'Web Service'"
echo "3. Connect your GitHub repository"
echo "4. Render will auto-detect render.yaml"
echo "5. Click 'Create Web Service'"
echo "6. After deployment, visit: https://your-app.onrender.com/setup.php"
echo ""
echo "🎉 Your portfolio will be live in a few minutes!"
