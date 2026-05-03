# Impersonation Permission Fix - Security Enhancement

## Overview
Fixed a critical permission bypass vulnerability in the impersonation feature that allowed admins to access pages and actions restricted to the impersonated user.

## Issues Identified

### 1. **Permission Bypass in CheckPermission Middleware**
**File:** `app/Http/Middleware/CheckPermission.php`

**Problem:** The `permissionUser()` method was checking the original admin's permissions during impersonation instead of the impersonated user's permissions.

```php
// BEFORE (VULNERABLE)
private function permissionUser(): User
{
    if (session('impersonate.active') && session('impersonate.original_id')) {
        $originalUser = User::find(session('impersonate.original_id'));
        
        if ($originalUser && $originalUser->hasRole('admin')) {
            return $originalUser;  // ❌ BUG: Returns admin, bypassing restrictions
        }
    }
    return Auth::user();
}
```

**Impact:** An admin could impersonate a regular user (student, teacher, etc.) and still access admin-only pages.

### 2. **Permission Bypass in AttendancePolicy**
**File:** `app/Policies/AttendancePolicy.php`

**Problem:** Similar issue in authorization policies - checking original admin instead of impersonated user.

```php
// BEFORE (VULNERABLE)
public function view(User $user, Group $group): bool
{
    if ($user->hasRole('admin')) {
        return true;
    }
    
    // Bypass during impersonation
    if (session('impersonate.active')) {
        $originalUser = User::find(session('impersonate.original_id'));
        if ($originalUser && $originalUser->hasRole('admin')) {
            return true;  // ❌ BUG: Allows admin access while impersonating
        }
    }
    
    return $user->hasRole('teacher') && ...;
}
```

## Solutions Implemented

### 1. **Fixed CheckPermission Middleware**
Always use the currently authenticated user (impersonated user) for permission checks:

```php
// AFTER (FIXED)
private function permissionUser(): User
{
    // Always return the currently authenticated user
    // During impersonation, this is the impersonated user
    return Auth::user();
}
```

### 2. **Fixed AttendancePolicy**
Removed all logic that checks the original admin during impersonation:

```php
// AFTER (FIXED)
public function view(User $user, Group $group): bool
{
    if ($user->hasRole('admin')) {
        return true;
    }
    
    // Only check current user's role
    return $user->hasRole('teacher') && $group->teachers()->where('user_id', $user->id)->exists();
}
```

### 3. **Added Impersonation Audit Logging**
Created a comprehensive audit trail for all impersonation events:

**New Model:** `app/Models/ImpersonationLog.php`
- Tracks admin who performed impersonation
- Records impersonated user
- Logs start and end times
- Captures IP address and user agent
- Provides scopes for active and completed sessions

**New Migration:** `create_impersonation_logs_table.php`
- Creates `impersonation_logs` table with proper indexes
- Allows quick lookups by admin, target user, and time range

**Updated Controller:** `ImpersonateController.php`
- Logs when impersonation starts
- Logs when impersonation ends
- Includes IP address and user agent for security tracking

## Security Implications

### Before Fix
```
Admin logs in as: admin@school.edu
Admin impersonates: student@school.edu
Admin can access:
  ❌ Routes requiring admin role
  ❌ Admin-only pages
  ❌ System settings
  ❌ All restricted sections
```

### After Fix
```
Admin logs in as: admin@school.edu
Admin impersonates: student@school.edu
Admin can access:
  ✅ Only pages student@school.edu can access
  ✅ Only actions student@school.edu can perform
  ✅ Student dashboard, personal courses, grades
  ✅ Strictly enforced role restrictions
  ❌ Admin pages (403 Forbidden)
  ❌ System settings (403 Forbidden)
  ❌ Other user data (403 Forbidden)
```

## Audit Trail Benefits

1. **Accountability:** Every impersonation is logged with admin ID
2. **Investigation:** IP address and user agent help detect abuse
3. **Timeline:** Know exactly when impersonation started and ended
4. **Compliance:** Supports regulatory requirements for access logging

## Testing Recommendations

1. **Role Enforcement:**
   - Impersonate a student, verify student dashboard works
   - Verify admin pages return 403 Forbidden
   - Verify teacher-only actions are restricted

2. **Permission Enforcement:**
   - Impersonate a teacher, verify can only access their groups
   - Verify cannot access other teachers' data
   - Verify cannot modify system settings

3. **Audit Trail:**
   - Check `impersonation_logs` table for entries
   - Verify start and end times are recorded
   - Verify IP addresses are captured

## Database Query Examples

```sql
-- View active impersonations
SELECT * FROM impersonation_logs WHERE ended_at IS NULL;

-- Find impersonations by a specific admin
SELECT * FROM impersonation_logs 
WHERE admin_user_id = ? 
ORDER BY started_at DESC;

-- Find impersonations of a specific user
SELECT * FROM impersonation_logs 
WHERE impersonated_user_id = ? 
ORDER BY started_at DESC;

-- Duration of impersonation sessions
SELECT 
    admin_user_id,
    impersonated_user_id,
    TIMESTAMPDIFF(MINUTE, started_at, ended_at) as duration_minutes,
    ip_address
FROM impersonation_logs
WHERE ended_at IS NOT NULL
ORDER BY started_at DESC;
```

## Related Files Reviewed

- `app/Http/Middleware/EnsureDirector.php` - ✅ Already correct
- `app/Policies/PaymentPolicy.php` - ✅ Already correct
- `app/Services/Payment/PaymentService.php` - ✅ Already correct
- `app/Http/Controllers/Settings/SettingsController.php` - ✅ Already correct

## Breaking Changes

None. This fix closes a security vulnerability without changing the intended API or user experience.

## Future Recommendations

1. **Admin Dashboard Widget:** Add a widget showing recent impersonations
2. **Alerts:** Notify original admin when impersonation ends
3. **Activity Logging:** Log specific actions taken during impersonation
4. **Time Limits:** Optional maximum impersonation duration
5. **Rate Limiting:** Prevent rapid impersonation switching
