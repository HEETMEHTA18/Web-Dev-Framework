# Practical-10 - Secure Login System

## Security Notice
This practical demonstrates secure credential handling by separating sensitive data from source code.

## Setup Instructions

1. **Copy credentials template:**
   ```bash
   cp credentials_template.php credentials.php
   ```

2. **Edit credentials.php with your desired usernames/passwords:**
   - This file is ignored by git for security
   - Never commit actual passwords to version control

## Files Structure
- `login.php` - Main login script (safe to commit)
- `credentials_template.php` - Template for credentials (safe to commit)
- `credentials.php` - Actual credentials (IGNORED by git)

## Security Best Practices Implemented
✅ Hardcoded credentials removed from source code  
✅ Credentials stored in separate file  
✅ Credentials file ignored by git  
✅ Fallback demo credentials for development  
✅ Error logging for missing credentials  

## Default Test Credentials (from template)
After setup, you can use:
- Username: `admin` | Password: `your_secure_admin_password`
- Username: `student1` | Password: `your_secure_password_1`
- Username: `student2` | Password: `your_secure_password_2`

**Remember:** Update these to secure passwords in your credentials.php file!