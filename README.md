# Gemba Finding (Digital Finding Management System)

A modern, responsive Web Application for reporting, tracking, and managing factory/workplace findings effectively (Gemba Walk).

## 🔐 Demo / Local Access Credentials

Below are the default seeded accounts you can use to log into the application based on different roles. All accounts use the same password.

**Default Password for all accounts:** `password`

| Role | Username / Email | Access Level |
|---|---|---|
| **Super Admin** | `admin@gemba.com` | Full access (System Settings, Users, Categories, Manage All Findings) |
| **Manager Produksi** | `manager@gemba.com` | Manage & Verify findings, view reports |
| **PIC Maintenance** | `pic@gemba.com` | Resolve assigned findings, update progress |
| **Supervisor** | `verifier@gemba.com` | Review and verify resolved findings |
| **VP / Viewer** | `viewer@gemba.com` | View-only access to dashboard and reports |

## 🚀 Setup Instructions

1. Run `composer install`
2. Run `npm install && npm run build`
3. Copy `.env.example` to `.env` and configure database (SQLite/MySQL)
4. Run `php artisan key:generate`
5. Run `php artisan migrate:fresh --seed`
6. Run `php artisan serve`
