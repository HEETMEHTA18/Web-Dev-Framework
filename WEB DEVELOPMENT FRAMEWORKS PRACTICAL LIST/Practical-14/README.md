# Practical-14: Admin Dashboard - User Management System

## Problem Definition
Develop an admin dashboard to view/manage users with role-based access control.

## Key Questions Addressed
1. ✅ **Are users listed dynamically from the DB?** - Yes, users are fetched from MySQL database and displayed in real-time
2. ✅ **Are delete/update actions working?** - Yes, AJAX-powered delete and status toggle functionality
3. ✅ **Is access restricted to the admin?** - Yes, role-based authentication with session management

## Features Implemented

### Core Features
- **Admin Authentication**: Secure login with role verification
- **Dynamic User Listing**: Real-time user data from database
- **User Status Management**: Active/Inactive toggle with AJAX
- **User Deletion**: Secure delete functionality (admins protected)
- **Statistics Dashboard**: Live counts of total, active, inactive users

### Advanced Features (Intermediate/Advanced Extensions)
- **Status Toggle**: Click-to-toggle active/inactive status with database update
- **Session Role Management**: Complete session-based role management
- **Dynamic Menu Loading**: Role-based UI elements and permissions
- **AJAX Operations**: Seamless user interactions without page reload

## Files Structure
```
Practical-14/
├── config.php          # Database connection & session management
├── database.sql         # Database schema & sample data
├── login.php           # Admin login page
├── dashboard.php       # Main admin dashboard
├── logout.php          # Session cleanup
└── README.md           # This documentation
```

## Database Schema
- **users table**: id, username, email, password, full_name, role, status, timestamps
- **Sample Data**: 1 admin user + 4 regular users for testing

## Setup Instructions

### 1. Database Setup
```sql
-- Import database.sql into MySQL
mysql -u root -p < database.sql
```

### 2. Configuration
Update `config.php` with your database credentials:
```php
$host = 'localhost';
$dbname = 'admin_dashboard';
$username = 'root';
$password = 'your_password';
```

### 3. Demo Credentials
- **Admin Username**: admin
- **Admin Password**: admin123

## Key Skills Demonstrated
- **Admin Logic**: Role-based access control and permissions
- **Database Operations**: CRUD operations with PDO
- **AJAX Integration**: Seamless user interactions
- **Security**: Password hashing, input sanitization, session management
- **UI/UX**: Clean white background design with responsive layout

## Applications
- **Content Management**: User moderation and content control
- **User Administration**: Account management and user lifecycle
- **System Administration**: Role-based system access control

## Learning Outcomes
- ✅ **CO1**: Build role-based admin UI with proper authentication
- ✅ **CO4**: Implement database-driven user management system
- ✅ **CO5**: Create secure admin dashboard with AJAX functionality

## Tools/Technology Used
- **Backend**: PHP 7.4+, MySQL 8.0+
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Security**: PDO prepared statements, password hashing
- **UI Framework**: Custom CSS with responsive design

## Testing Data
The system includes pre-populated test data:
- 1 Admin user (admin/admin123)
- 4 Regular users with varying statuses
- Comprehensive user scenarios for testing

## Total Implementation Hours
- **Development**: 4 hours
- **Testing & Documentation**: 2 hours
- **Total Engagement**: 6 hours

## Post Lab Demo
1. Login as admin
2. View user statistics dashboard
3. Toggle user status (active/inactive)
4. Delete non-admin users
5. Verify access restrictions

## Evaluation Strategy
- **Access Control Validation**: ✅ Only admins can access dashboard
- **Dynamic Data Loading**: ✅ Users loaded from database
- **CRUD Operations**: ✅ Create, Read, Update, Delete functionality
- **Security Testing**: ✅ Session management and role verification
- **UI/UX Testing**: ✅ Responsive design and user interactions

## Advanced Extensions Implemented
### Intermediate Level
- ✅ Active/inactive status toggle with real-time database updates
- ✅ AJAX-powered operations without page reload
- ✅ Real-time statistics updates

### Advanced Level
- ✅ Complete session role management system
- ✅ Dynamic menu loading based on user roles
- ✅ Comprehensive security measures (CSRF protection ready)
- ✅ Scalable architecture for additional features

## Quick Start
1. Import `database.sql` into MySQL
2. Update database credentials in `config.php`
3. Start PHP server: `php -S localhost:8000`
4. Open: `http://localhost:8000/login.php`
5. Login with admin/admin123
6. Explore the dashboard features!

## Security Features
- Password hashing with PHP's password_hash()
- SQL injection prevention with PDO prepared statements
- XSS protection with htmlspecialchars()
- Session-based authentication
- Role-based access control
- Admin user deletion protection