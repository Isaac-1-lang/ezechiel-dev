# 🚀 Deploying to Render

This guide will help you deploy your PHP portfolio to Render using Docker.

## Prerequisites

1. A [Render account](https://render.com) (free tier available)
2. Your code pushed to a Git repository (GitHub, GitLab, or Bitbucket)

## Deployment Steps

### Step 1: Push Your Code to GitHub

```bash
git init
git add .
git commit -m "Initial commit - Portfolio ready for deployment"
git branch -M main
git remote add origin YOUR_GITHUB_REPO_URL
git push -u origin main
```

### Step 2: Create New Web Service on Render

1. Go to [Render Dashboard](https://dashboard.render.com/)
2. Click **"New +"** button
3. Select **"Web Service"**
4. Connect your GitHub/GitLab repository
5. Select your repository

### Step 3: Configure Service Settings

Render will auto-detect the `render.yaml` file, but verify these settings:

- **Name**: `ezechiel-portfolio` (or your preferred name)
- **Runtime**: Docker
- **Branch**: `main`
- **Plan**: Free (or choose paid plan for better performance)

### Step 4: Deploy

1. Click **"Create Web Service"**
2. Render will automatically:
   - Build the Docker image
   - Install PHP 8.0 with Apache
   - Set up SQLite database
   - Deploy your application

### Step 5: Initialize Database

Once deployed, visit:
```
https://your-app-name.onrender.com/setup.php
```

Click **"Initialize Database Now"** to create tables and seed data.

### Step 6: Access Your Portfolio

Your portfolio will be live at:
```
https://your-app-name.onrender.com
```

Admin panel:
```
https://your-app-name.onrender.com/admin/login.php
```

Default credentials:
- Username: `admin`
- Password: `admin123`

## Important Notes

### Free Tier Limitations

- Render free tier spins down after 15 minutes of inactivity
- First request after spin-down may take 30-60 seconds
- 750 hours/month free (enough for one service)

### Database Storage

- The app uses SQLite for database storage
- SQLite file is stored in `/var/www/html/database/`
- **WARNING**: On free tier, data may be lost on redeployments
- For persistent data, consider:
  - Upgrading to a paid plan with persistent disk
  - Using Render PostgreSQL database (requires code changes)
  - Using external MySQL database

### Environment Variables (Optional)

If you want to use MySQL instead of SQLite, add these environment variables in Render:

```
DB_HOST=your-mysql-host
DB_PORT=3306
DB_USER=your-username
DB_PASS=your-password
DB_NAME=portfolio_db
```

## Troubleshooting

### Build Fails

- Check Render build logs
- Ensure Dockerfile is in repository root
- Verify all files are committed to Git

### Database Not Working

- Visit `/setup.php` to initialize database
- Check file permissions in logs
- Verify SQLite is installed (it's included in Dockerfile)

### 404 Errors

- Ensure `.htaccess` file is present
- Check Apache rewrite module is enabled
- Verify file paths are correct

## Custom Domain

To use your own domain:

1. Go to your service settings on Render
2. Click "Custom Domains"
3. Add your domain
4. Update DNS records as instructed by Render

## Updating Your Site

After making changes:

```bash
git add .
git commit -m "Update portfolio content"
git push origin main
```

Render will automatically redeploy your changes.

## Support

- [Render Documentation](https://render.com/docs)
- [Render Community](https://community.render.com/)

---

**Need Help?** Check the Render logs in your dashboard for detailed error messages.
