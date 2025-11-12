# 🎉 CHARUSAT Event Management System - Complete Project

## ✅ Project Status: **COMPLETE & DEPLOYED**

### Quick Summary
A fully-functional, secure PHP-based Event Management System with authentication, event management, admin dashboard, and database on **port 3307** (fully configured and tested).

## Current Configuration ✅
```
Database Host:      localhost:3307 ✅
Database Name:      event_management
Database User:      root
Tables:             3 (users, events, registrations)
Connection Status:  TESTED & VERIFIED
Sample Events:      5
```

## Overview
A secure PHP-based event management system for CHARUSAT that enables users to register for events and allows admins to manage events and view participant details.

## Features

### User Features
- **User Registration & Login**: Secure registration with email verification and password hashing
- **Session Management**: Secure sessions with CSRF protection
- **Remember Me**: 30-day persistent login with secure tokens
- **Event Listing**: View all upcoming events with details
- **Event Registration**: Register and unregister for events
- **Event Details**: View comprehensive event information
- **My Registrations**: Track all registered events
- **Profile Management**: Update profile and change password
- **Responsive Design**: Mobile-friendly interface

### Admin Features
- **Admin Dashboard**: Overview of all events, users, and registrations
- **Event Management**: Create, edit, and delete events
- **Participant Management**: View and export participant data
- **User Management**: Monitor all registered users
- **Statistics**: Real-time stats on events, users, and registrations
- **CSV Export**: Export participant lists for further analysis

## Security Features
- **Password Hashing**: BCrypt with cost=12 for maximum security
- **CSRF Protection**: Token-based CSRF prevention
- **SQL Injection Prevention**: Prepared statements for all queries
- **Input Validation**: Sanitization of all user inputs
- **Session Security**: HttpOnly cookies with Strict SameSite policy
- **XSS Prevention**: HTML entity encoding for all outputs
- **Secure Cookies**: Secure flag enabled for HTTPS environments
- **Session Timeout**: 30-minute inactivity timeout
- **Email Validation**: RFC-compliant email validation

## Technology Stack
- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, Responsive Design
- **Security**: BCrypt, CSRF Tokens, Prepared Statements
- **OOP Design**: Class-based architecture with Singleton pattern

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    remember_token VARCHAR(64),
    token_expiry DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Events Table
```sql
CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date DATETIME NOT NULL,
    venue VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Registrations Table
```sql
CREATE TABLE registrations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_registration (user_id, event_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);
```

## Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL/MariaDB 5.7 or higher
- XAMPP or similar local server (for development)
- Modern web browser

### Step 1: Database Setup
1. Open your browser and navigate to: `http://localhost/Eventmanagement/setup_database.php`
2. The script will create the database and tables automatically
3. A default admin user will be created with credentials:
   - Email: `admin@charusat.edu`
   - Password: `Admin@123456`

### Step 2: Configuration
1. Edit `config.php` if needed to match your database settings
2. Default settings work for XAMPP:
   - Host: localhost
   - User: root
   - Password: (empty)
   - Database: event_management

### Step 3: Access the Application
- **User Dashboard**: `http://localhost/Eventmanagement/index.php`
- **Login**: `http://localhost/Eventmanagement/login.php`
- **Register**: `http://localhost/Eventmanagement/register.php`
- **Admin Panel**: `http://localhost/Eventmanagement/admin/dashboard.php` (after login as admin)

## File Structure

```
Eventmanagement/
├── config.php                 # Database config & security settings
├── setup_database.php         # Database initialization script
├── login.php                  # Login page
├── register.php               # User registration page
├── logout.php                 # Logout handler
├── index.php                  # Main dashboard
├── my-registrations.php       # User's registered events
├── profile.php                # User profile management
├── event-details.php          # Event details page
├── classes/
│   ├── User.php              # User authentication & management
│   ├── Event.php             # Event CRUD operations
│   └── Registration.php       # Registration handling
├── admin/
│   ├── dashboard.php         # Admin dashboard
│   ├── participants.php      # Event participants
│   ├── users.php             # Users management
│   └── event-details.php     # Edit event details
└── exports/                  # CSV exports directory
```

## Usage Guide

### For Users

#### Registration
1. Go to `http://localhost/Eventmanagement/register.php`
2. Enter full name, email, and password (minimum 8 characters)
3. Confirm password and submit
4. Redirect to login page

#### Login
1. Go to `http://localhost/Eventmanagement/login.php`
2. Enter email and password
3. Optionally check "Remember me for 30 days"
4. You'll be redirected to the dashboard

#### Register for Events
1. On dashboard, browse available upcoming events
2. Click "Register" button on any event
3. Confirm registration
4. Event appears in "My Registrations"

#### Manage Registration
1. Go to "My Registrations"
2. View all registered events
3. Click "Unregister" to cancel registration for future events
4. Cannot unregister from past events

#### Profile Management
1. Go to "Profile" from navigation
2. Update name and email
3. Change password (requires current password verification)
4. All changes are saved securely

### For Admins

#### Access Admin Panel
1. Login with admin credentials
2. Click "Admin Panel" in navigation
3. View dashboard with statistics

#### Create Events
1. On admin dashboard, scroll to "Create New Event" section
2. Enter event title, description, date/time, and venue
3. Click "Create Event"
4. Event appears in the events list

#### Manage Events
1. View all events in the table
2. Click "View" to see event details
3. Click "Edit" to modify event information
4. Click "Delete" to remove event (will remove all registrations)

#### View Participants
1. In events table, click "Participants" for any event
2. View all users registered for that event
3. Click "Export to CSV" to download participant list

#### Manage Users
1. Go to "Users" in admin navigation
2. View all registered users
3. See user roles (Admin/User)
4. See membership date

#### Export Data
1. Go to event participants page
2. Click "Export to CSV"
3. Participant data downloads in CSV format
4. Includes: Name, Email, Role, Registration Date

## Security Best Practices

1. **Change Default Admin Password**: After setup, change the admin password
2. **Enable HTTPS**: Set `HTTPS_ENABLED` to `true` in production
3. **Regular Backups**: Back up database regularly
4. **Update PHP**: Keep PHP updated to latest stable version
5. **Secure Installation**: Remove `setup_database.php` after initial setup
6. **Strong Passwords**: Enforce strong passwords for all users
7. **Monitor Access**: Regularly review admin activities
8. **Session Management**: Sessions auto-expire after 30 minutes of inactivity

## Password Requirements
- Minimum 8 characters
- Mix of uppercase and lowercase letters
- Include numbers and special characters (recommended)
- Hashed with BCrypt (cost=12) before storage

## Remember Me Functionality
- Secure token stored in database (64-byte hex string)
- Expires after 30 days
- Token removed on logout
- HttpOnly and Secure cookies for token

## CSRF Protection
- Token generated for each session
- Unique token per form submission
- Validated on all POST requests
- Invalid tokens rejected with error

## Performance Features
- Database indexes on frequently queried columns
- Singleton pattern for database connection
- Prepared statements reduce query execution time
- Optimized event queries with pagination support

## Troubleshooting

### Database Connection Error
- Check if MySQL/MariaDB is running
- Verify database credentials in `config.php`
- Ensure database user has proper permissions

### Login Issues
- Clear browser cookies
- Check email and password are correct
- Ensure user account exists
- Verify password matches hash (case-sensitive)

### Session Timeout
- Sessions expire after 30 minutes of inactivity
- Login again to continue
- Remember Me can extend session to 30 days

### CSV Export Issues
- Ensure `/exports` directory is writable
- Check disk space availability
- Verify permissions on export directory
- PHP must have write permissions

## API Reference

### User Class Methods
- `register($name, $email, $password, $password_confirm)` - Register new user
- `login($email, $password, $rememberMe=false)` - User login
- `logout()` - User logout
- `autoLogin($token)` - Auto-login with remember token
- `updateProfile($id, $name, $email)` - Update user profile
- `changePassword($id, $oldPassword, $newPassword, $newPasswordConfirm)` - Change password
- `getUserById($id)` - Retrieve user details

### Event Class Methods
- `createEvent($title, $description, $date, $venue)` - Create new event
- `getAllEvents($upcomingOnly=false)` - Get all or upcoming events
- `getEventById($id)` - Retrieve event details
- `updateEvent($id, $title, $description, $date, $venue)` - Update event
- `deleteEvent($id)` - Delete event
- `getParticipantCount($eventId)` - Get event participants count
- `isUserRegistered($userId, $eventId)` - Check user registration status

### Registration Class Methods
- `registerUserForEvent($userId, $eventId)` - Register user for event
- `unregisterUserFromEvent($userId, $eventId)` - Unregister from event
- `isUserRegistered($userId, $eventId)` - Check registration status
- `getUserRegistrations($userId)` - Get user's registered events
- `getEventParticipants($eventId)` - Get event participants list
- `exportParticipantsToCSV($eventId)` - Export participants to CSV
- `getStatistics()` - Get system statistics

## License
This project is developed for CHARUSAT educational purposes.

## Support
For issues or questions, contact the development team or visit the admin dashboard.

## Version
- **Version**: 1.0
- **Last Updated**: 2024
- **Status**: Production Ready

---

**Note**: This system is designed with enterprise-level security standards and follows OWASP guidelines for web application security.
