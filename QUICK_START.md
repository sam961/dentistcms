# Quick Start - Cloud Deployment

## After pushing to cloud server via SSH:

### Step 1: Run Migrations
```bash
php artisan migrate --force
```

### Step 2: Create Super Admin (Required)
```bash
php artisan setup:superadmin
```
- Follow prompts to enter email, name, and password
- Or use flags: `--email=admin@example.com --password=SecurePass123 --name="Admin"`
- Login at: `https://yourdomain.com/admin/login`

### Step 3: Setup Demo Account (Optional)
```bash
php artisan setup:demo
```
- Creates demo tenant with seeded data
- Auto-resets every hour
- Login at: `demo.yourdomain.com`
- Credentials: demo@dentistcms.com / demo123456 / 2FA: 123456

### Step 4: Setup Cron (Required for scheduled tasks)
```bash
crontab -e
```
Add this line:
```
* * * * * cd /path/to/dentist_cms && php artisan schedule:run >> /dev/null 2>&1
```

## That's it! 🎉

Your Dental CMS is now ready to use.

---

### Quick Commands Reference

| Task | Command |
|------|---------|
| Create super admin | `php artisan setup:superadmin` |
| Setup demo account | `php artisan setup:demo` |
| Reset demo data | `php artisan demo:reset --force` |
| Clear caches | `php artisan optimize:clear` |
| View scheduled tasks | `php artisan schedule:list` |

For detailed deployment guide, see [DEPLOYMENT.md](DEPLOYMENT.md)
