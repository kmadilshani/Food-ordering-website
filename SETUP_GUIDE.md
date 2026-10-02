# Speedy Eats Food Ordering System - Setup Guide

## 🚀 Quick Start

This is a complete food ordering web application with dynamic content, user authentication, shopping cart, and admin panel.

## 📋 Prerequisites

- **XAMPP** (Apache + MySQL + PHP) installed and running
- **Web Browser** (Chrome, Firefox, Safari, Edge)
- **Text Editor** (VS Code, Sublime Text, etc.)

## 🔧 Installation Steps

### Step 1: Start XAMPP Services

1. Open **XAMPP Control Panel**
2. Start **Apache** service
3. Start **MySQL** service
4. Both should show green "Running" status

### Step 2: Database Setup

#### Option A: Automatic Setup (Recommended)
1. Open your web browser
2. Navigate to: `http://localhost/Food_ordering_web-main/setup.php`
3. The script will automatically:
   - Create the database
   - Set up tables
   - Insert sample data
   - Create admin and demo users

#### Option B: Manual Setup
1. Open **phpMyAdmin**: `http://localhost/phpmyadmin`
2. Create a new database named: `speedy_eats`
3. Import the schema: `backend/php/schema.sql`
4. Run the seed script: `backend/php/seed.php`

### Step 3: Access the Application

1. **Main Website**: `http://localhost/Food_ordering_web-main/`
2. **Admin Panel**: `http://localhost/Food_ordering_web-main/admin/`

## 👤 Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | secret123 |
| Demo User | demo@example.com | secret123 |

## 🎯 Features

### For Customers:
- ✅ Browse food categories
- ✅ Search for foods
- ✅ Add items to cart
- ✅ User registration/login
- ✅ Place orders
- ✅ View order history

### For Admins:
- ✅ Manage food categories
- ✅ Add/edit/delete foods
- ✅ View all orders
- ✅ User management

## 🔍 Search Functionality

The search feature has been **FIXED** and now works properly:
- Search by food name or description
- Real-time results
- Works across all pages

## 🛠️ Troubleshooting

### Database Connection Issues
If you get database connection errors:

1. **Check MySQL Service**: Ensure MySQL is running in XAMPP
2. **Check Credentials**: The app tries common XAMPP passwords automatically
3. **Manual Fix**: Edit `backend/php/config/database.php` with your MySQL credentials

### Search Not Working
If search still doesn't work:
1. Ensure database is properly set up
2. Check browser console for JavaScript errors
3. Verify API endpoints are accessible

### Images Not Loading
- All image paths have been verified and fixed
- Images are located in the `img/` directory
- Category images are in `img/category/`
- Food images are in `img/food/`

## 📁 Project Structure

```
Food_ordering_web-main/
├── admin/                 # Admin panel
├── backend/              # Backend API
│   ├── php/             # PHP backend
│   │   ├── api/         # API endpoints
│   │   ├── config/      # Database config
│   │   ├── models/      # Data models
│   │   └── helpers/     # Helper functions
│   └── models/          # Node.js models (alternative)
├── css/                  # Stylesheets
├── js/                   # JavaScript files
├── img/                  # Images
├── *.html               # Main pages
└── setup.php            # Database setup script
```

## 🔧 Configuration

### Database Configuration
Edit `backend/php/config/database.php` if you need custom database settings:

```php
private $host = 'localhost';
private $db_name = 'speedy_eats';
private $username = 'root';
private $password = 'root'; // Adjust as needed
```

### API Base URL
The frontend automatically detects the correct API path. If you need to change it, edit `js/dynamic.js`:

```javascript
const API_BASE = "backend/php/api/";
```

## 🚀 Production Deployment

For production deployment:

1. **Remove Setup Files**: Delete `setup.php` and `backend/php/install.php`
2. **Update Database Config**: Use production database credentials
3. **Enable HTTPS**: Configure SSL certificate
4. **Optimize Images**: Compress images for faster loading
5. **Set Proper Permissions**: Configure file permissions

## 📞 Support

If you encounter any issues:

1. Check this setup guide
2. Verify XAMPP services are running
3. Check browser console for errors
4. Ensure database is properly configured

## 🎉 You're All Set!

Your Speedy Eats Food Ordering System is now ready to use. Enjoy your fully functional food delivery website!

---

**Note**: This project includes both PHP and Node.js backend options. The PHP backend is configured by default and is recommended for XAMPP environments.

