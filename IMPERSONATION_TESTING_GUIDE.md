# Impersonation Security Fix - Testing Guide

## Quick Verification Checklist

### Setup Test Users
Create or use existing test accounts:
- **Admin:** User with `admin` role
- **Student:** User with `student` role  
- **Teacher:** User with `teacher` role

### Test 1: Permission Enforcement During Impersonation

**Scenario:** Admin impersonates a Student

**Steps:**
1. Login as admin
2. Navigate to Admin > Impersonate Users
3. Select a student user and click "Impersonate"
4. Verify student dashboard loads correctly
5. **Critical Test:** Try to navigate to admin-only pages:
   - `/admin` or admin dashboard
   - `/roles` (Role management)
   - `/permissions` (Permission management)
   - `/settings` (System settings)

**Expected Result:**
- ✅ Student dashboard works
- ✅ Student can see only their data
- ❌ Admin pages return **403 Forbidden** error
- ❌ Cannot access `/roles`, `/permissions`, `/settings`

**Before Fix:** Admin could access these pages
**After Fix:** Proper 403 Forbidden errors are shown

---

### Test 2: Role-Based Access Control

**Scenario:** Admin impersonates a Teacher

**Steps:**
1. Impersonate a teacher
2. Try to access student payments page (`/student/payments`)
3. Try to access teacher reports (should work)
4. Try to access director dashboard (should fail)

**Expected Result:**
- ✅ Teacher dashboard works
- ✅ Can view teacher reports
- ❌ Student pages return 403 Forbidden
- ❌ Director pages return 403 Forbidden

---

### Test 3: Attendance Permission

**Scenario:** Admin impersonates a Teacher, then tries to access a group they don't teach

**Steps:**
1. Impersonate a teacher
2. Try to view attendance for a group they teach → should work
3. Try to view attendance for a group they DON'T teach → should fail

**Expected Result:**
- ✅ Can view/update attendance for their groups
- ❌ Cannot access other teachers' groups (403 Forbidden)

---

### Test 4: Audit Trail Verification

**Steps:**
1. Admin impersonates a user
2. Check database: `SELECT * FROM impersonation_logs WHERE ended_at IS NULL;`
3. Verify the current impersonation is logged with:
   - ✅ Correct admin_user_id
   - ✅ Correct impersonated_user_id
   - ✅ started_at timestamp
   - ✅ IP address is captured
   - ✅ user_agent is captured
   - ✅ ended_at is NULL

4. Click "Leave Impersonation"
5. Query again: `SELECT * FROM impersonation_logs ORDER BY started_at DESC LIMIT 1;`
6. Verify:
   - ✅ ended_at is now populated
   - ✅ Duration can be calculated
   - ✅ Returned to admin account

---

### Test 5: Restricted Resource Access

**Scenario:** Admin impersonates Student, tries to access another student's data

**Steps:**
1. Impersonate Student A
2. Try to access Student B's profile/data/grades
3. Try to access parent/guardian information for other students

**Expected Result:**
- ✅ Can only see Student A's data
- ❌ Cannot see Student B's data (403 Forbidden)
- ❌ Cannot access other student information (403 Forbidden)

---

### Test 6: Impersonation Session Persistence

**Steps:**
1. Impersonate a user
2. Navigate to different pages
3. Verify impersonation remains active
4. Check page shows "Impersonating as: [User Name]" banner
5. Click "Leave Impersonation"
6. Verify returned to admin account with success message
7. Check impersonation_logs entry is complete

**Expected Result:**
- ✅ Session remains active across page navigation
- ✅ Visual indicator of impersonation shown
- ✅ Clean return to admin account
- ✅ Audit trail properly recorded

---

## SQL Queries for Manual Verification

### Check Current Active Impersonations
```sql
SELECT 
    il.id,
    a.surname as admin_name,
    u.surname as target_name,
    il.started_at,
    il.ip_address,
    il.user_agent
FROM impersonation_logs il
JOIN users a ON il.admin_user_id = a.id
JOIN users u ON il.impersonated_user_id = u.id
WHERE il.ended_at IS NULL
ORDER BY il.started_at DESC;
```

### Check Impersonation History
```sql
SELECT 
    il.id,
    a.surname as admin_name,
    u.surname as target_name,
    il.started_at,
    il.ended_at,
    TIMESTAMPDIFF(MINUTE, il.started_at, il.ended_at) as duration_minutes,
    il.ip_address
FROM impersonation_logs il
JOIN users a ON il.admin_user_id = a.id
JOIN users u ON il.impersonated_user_id = u.id
ORDER BY il.started_at DESC
LIMIT 50;
```

### Check Specific Admin's Impersonations
```sql
SELECT 
    u.surname as target_name,
    il.started_at,
    il.ended_at,
    TIMESTAMPDIFF(MINUTE, il.started_at, il.ended_at) as duration_minutes
FROM impersonation_logs il
JOIN users u ON il.impersonated_user_id = u.id
WHERE il.admin_user_id = [ADMIN_ID]
ORDER BY il.started_at DESC;
```

---

## Expected Error Messages

When permission is denied during impersonation:

**Route/Permission Check:**
```
403 - Sizda bu sahifaga kirish huquqi yo'q!
(403 - You don't have permission to access this page!)
```

**Role-Based Access:**
```
403 - Bu sahifaga faqat [role] kirishlari mumkin.
(403 - Only [role] users can access this page.)
```

**Policy Authorization:**
```
403 - Sizda bu operatsiyani bajarish huquqi yo'q!
(403 - You don't have permission to perform this action!)
```

---

## Troubleshooting

### Issue: Still able to access admin pages while impersonating
- Clear application cache: `php artisan config:cache`
- Clear views: `php artisan view:clear`
- Verify middleware is loaded in `app/Http/Kernel.php`
- Check if CheckPermission middleware is in route group

### Issue: Impersonation log not being created
- Verify migration ran: `php artisan migrate:status`
- Check ImpersonationLog model exists
- Verify ImpersonateController has correct imports
- Check Laravel logs for errors

### Issue: Cannot impersonate certain users
- Verify user is not an admin (admins cannot be impersonated)
- Verify user is active (`is_active = 1`)
- Verify user is not soft-deleted (`deleted_at IS NULL`)
- Check admin has impersonation permission

---

## Performance Notes

- Impersonation logging has minimal impact
- Indexes on `impersonation_logs` ensure quick lookups
- Regular permission checks use in-memory role cache
- No additional database queries during request handling

---

## Security Best Practices

1. **Review impersonation logs regularly**
2. **Alert on suspicious impersonation patterns**
3. **Limit impersonation to necessary admins**
4. **Document reason for impersonation in logs** (optional enhancement)
5. **Set maximum impersonation duration** (optional enhancement)
6. **Notify affected user of impersonation** (optional enhancement)
