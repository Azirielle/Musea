# Musea Admin System Guide

## Overview
 The Musea Admin System is a custom-built administration panel using:
- **Framework**: Laravel 12 (Backend) + Inertia.js (Frontend) + Vue.js
- **Authentication**: Separate `admin` guard using the `admins` table.
- **Routes**: Prefixed with `/admin`.

## 1. Accessing the Admin Panel
You can access the admin panel at:
- **Login URL**: `/admin/login` (e.g., `http://localhost:8000/admin/login`)
- **Dashboard**: `/admin`

## 2. Authentication & Security
The system uses a completely separate authentication guard from regular users.
- **Table**: `admins` (Columns: `id`, `name`, `email`, `password`, ...)
- **Model**: `App\Models\Admin`
- **Controller**: `App\Http\Controllers\Admin\Auth\AdminAuthController`
- **Middleware**: `auth:admin` protects all dashboard routes.

## 3. Creating an Admin Account
Since there is no public registration for admins, you must create accounts via the database or command line.

### Option A: Using Artisan Tinker
1. Run `php artisan tinker` in your terminal.
2. Execute the following command:
   ```php
   \App\Models\Admin::create([
       'name' => 'Super Admin',
       'email' => 'admin@musea.com',
       'password' => bcrypt('password123')
   ]);
   ```

### Option B: Existing Accounts
Based on the database inspection, there is an existing admin account:
- **Email**: `tempadmin@musea.com`
*(If you do not know the password, use Option A or reset the password via Tinker)*

## 4. Key Features & Routes
The admin panel includes the following features (defined in `routes/web.php`):
- **User Management**: `/admin/users` (Toggle active/featured status)
- **Artwork Approvals**: `/admin/approvals` (Approve/Reject artwork)
- **Sales & Orders**: `/admin/sales`
- **Reports**: `/admin/reports` (Handle user/content reports)
- **Settings**: `/admin/settings` (Platform configuration)
- **Coupons**: `/admin/coupons`
- **Withdrawals**: `/admin/withdrawals` (Artist payout requests)
- **Verifications**: `/admin/verifications` (Artist verification requests)

## 5. Development Notes
- **Routes**: Located in `routes/web.php` inside the `config('app.type') !== 'public'` block.
- **Controllers**: Located in `app/Http/Controllers/Admin/`.
- **Views**: frontend pages are in `resources/js/Pages/Admin/`.
