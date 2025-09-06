# LGU1 Authentication System Integration Guide

## Overview

The LGU1 Public Facilities Reservation System has been integrated with the official LGU1 authentication system. This replaces the previous Laravel-based admin login with a secure token-based authentication system.

## Authentication Flow

### 1. **LGU Portal Access**
- Admins and staff must access the system through the official LGU1 portal
- Direct login via email/password is no longer available for admin/staff accounts
- Citizens continue to use the separate citizen portal

### 2. **Token-Based Authentication**
- Users are redirected from the LGU portal with authentication parameters:
  ```
  https://your-domain.com/admin/auth?user_id=123&token=base64_encoded_username
  ```
- The system validates these parameters against the LGU API
- Successful validation logs the user into the Laravel system

### 3. **Session Management**
- **Session Timeout**: 1 hour (3600 seconds) of inactivity
- **Automatic Logout**: Users are logged out and redirected to login after timeout
- **Session Data**: LGU user information is stored in Laravel sessions

## API Integration

### **LGU Authentication API**
- **Endpoint**: `https://local-government-unit-1-ph.com/api/route.php?path=facilities-users`
- **Security**: SSRF protection with allowed hosts validation
- **Method**: cURL with timeout and SSL verification

### **User Data Mapping**
LGU user data is mapped to Laravel users:
- `LGU username` → `lgu_username` field
- `LGU user ID` → `lgu_user_id` field  
- `LGU role` → Laravel `role` (admin/staff)
- `LGU full name` → Laravel `name`

## Security Features

### **Input Validation**
- All user inputs are validated and sanitized
- Integer validation for user IDs
- String sanitization for text fields

### **SSRF Protection**
- API calls restricted to allowed hosts only
- URL validation before making requests
- cURL configured with security settings

### **Session Security**
- Automatic session timeout
- Session data cleanup on logout
- Failed authentication attempt logging

## File Structure

### **New Files Created**
```
app/Http/Controllers/Auth/LGUAuthController.php     # Main authentication controller
app/Http/Middleware/LGUSessionTimeout.php          # Session timeout middleware
resources/views/auth/lgu-login.blade.php           # New admin login page
database/migrations/*_add_lgu_fields_to_users_table.php  # Database migration
```

### **Modified Files**
```
app/Models/User.php                                 # Added LGU fields
routes/web.php                                      # Updated authentication routes
bootstrap/app.php                                   # Registered middleware
```

## Testing the System

### **Test Scenario 1: Direct Access**
1. Go to `/admin/login`
2. Should see LGU authentication instructions
3. No direct login form available

### **Test Scenario 2: Token Authentication**
1. Access: `/admin/auth?user_id=123&token=base64_encoded_username`
2. System validates against LGU API
3. Creates/updates Laravel user account
4. Redirects to appropriate dashboard

### **Test Scenario 3: Session Timeout**
1. Login successfully
2. Wait 1 hour (or modify timeout for testing)
3. Access any admin/staff page
4. Should be redirected to login with timeout message

### **Test Scenario 4: Logout**
1. Access any admin/staff page while logged in
2. Click logout
3. Should clear all LGU session data
4. Redirect to admin login page

## Role Mapping

| LGU Role | Laravel Role | Dashboard Redirect |
|----------|--------------|-------------------|
| Administrator | admin | `/admin/dashboard` |
| Admin | admin | `/admin/dashboard` |
| Administrative & Records Staff | staff | `/staff/dashboard` |
| Staff | staff | `/staff/dashboard` |

## Error Handling

### **Common Error Scenarios**
- **Missing Parameters**: Redirects to login with error message
- **Invalid Token**: Validation failure, redirects to login
- **API Unavailable**: Graceful error handling with user-friendly message
- **User Creation Failed**: Database error handling
- **Session Timeout**: Automatic logout with notification

### **Logging**
All authentication attempts, errors, and security events are logged:
- Successful authentications
- Failed authentication attempts
- Session timeouts
- API communication errors
- Security violations

## Maintenance

### **Session Cleanup**
- Sessions are automatically cleaned on logout
- Timeout sessions are cleared by middleware
- LGU-specific session data is properly removed

### **Database**
- New fields: `lgu_user_id`, `lgu_username` in users table
- Existing user accounts can be linked to LGU accounts
- Migration supports existing data

### **API Monitoring**
- Monitor LGU API availability
- Log API response times and errors
- Implement fallback procedures if needed

## Security Notes

1. **No Direct Passwords**: Admin/staff accounts don't use Laravel passwords
2. **Token Validation**: All tokens are validated against LGU API
3. **Session Security**: 1-hour timeout with activity tracking
4. **Input Sanitization**: All inputs are validated and sanitized
5. **HTTPS Required**: All API communications use SSL/TLS
6. **Error Disclosure**: Generic error messages prevent information leakage

## Configuration

### **Allowed Hosts**
Update allowed hosts in `LGUAuthController.php`:
```php
$allowedHosts = ['local-government-unit-1-ph.com'];
```

### **Session Timeout**
Modify timeout in `LGUSessionTimeout.php`:
```php
$sessionTimeout = 3600; // 1 hour in seconds
```

### **API Endpoint**
Update API URL if needed:
```php
$apiUrl = 'https://local-government-unit-1-ph.com/api/route.php?path=facilities-users';
```

---

**Implementation Status**: ✅ Complete  
**Last Updated**: {{ date('Y-m-d') }}  
**Version**: 1.0
