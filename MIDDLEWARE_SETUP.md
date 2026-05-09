# Middleware Setup Instructions

## AdminMiddleware Registration

The `AdminMiddleware` has been created at `app/Http/Middleware/AdminMiddleware.php`

### Step 1: Register in app/Http/Kernel.php

Open `app/Http/Kernel.php` and add the following to the `$routeMiddleware` array:

```php
protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
    'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
    'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    
    // 👇 ADD THIS LINE:
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

### Step 2: Verify Kernel.php Updated

Your `app/Http/Kernel.php` should now include the admin middleware in the route middleware array.

### Step 3: Test

After updating the kernel:

```bash
# Clear route cache
php artisan route:cache

# Test authentication
php artisan tinker
# In tinker, create a test user or check existing user roles
```

## What the Middleware Does

1. **Checks Authentication**: Redirects to login if user not authenticated
2. **Checks Admin Role**: Aborts with 403 Forbidden if user role is not 'admin'
3. **Called on Every Admin Route**: `/admin/*` routes all require this middleware

## Routes Protected by AdminMiddleware

All routes under `/admin/*` require:
- `auth` middleware - User must be logged in
- `admin` middleware - User must have role = 'admin'

Protected routes:
```
/admin                          (Dashboard)
/admin/scenes                   (List, create, edit, delete, reorder)
/admin/hotspots                 (List, create, edit, delete)
/admin/artifacts                (List, create, edit, delete)
/admin/categories               (List, create, edit, delete)
```

## User Model Requirements

The `User` model must have:

1. **`role` field** in database - values: 'admin' or 'user'
2. **`isAdmin()` method** - returns true if role === 'admin'

Already implemented in `app/Models/User.php`:

```php
public function isAdmin(): bool
{
    return $this->role === 'admin';
}
```

## Testing

To test the middleware works:

```bash
# Create a test admin user
php artisan tinker
User::create([
    'name' => 'Admin',
    'email' => 'admin@test.com',
    'password' => bcrypt('password'),
    'role' => 'admin',
]);

# Create a test non-admin user
User::create([
    'name' => 'User',
    'email' => 'user@test.com',
    'password' => bcrypt('password'),
    'role' => 'user',
]);
```

Then:
1. Login as admin → Can access `/admin`
2. Login as user → Redirected to login or shown 403 error
3. Not logged in → Redirected to login

## Troubleshooting

**404 on admin routes?**
- Run `php artisan route:clear`
- Run `php artisan route:cache`

**403 Forbidden?**
- Check user `role` field in database
- Verify `User::isAdmin()` returns true for admin users

**Redirects to login on admin access?**
- Ensure user is authenticated
- Check session

**Unknown middleware: 'admin'?**
- Verify AdminMiddleware is added to `$routeMiddleware` in Kernel.php
- Run `php artisan route:clear` && `php artisan cache:clear`

---

**Status**: ✅ AdminMiddleware created and ready to register
**Next Step**: Add the middleware registration line to `app/Http/Kernel.php`
