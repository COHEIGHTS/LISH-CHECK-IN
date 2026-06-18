# Lish Check-In System

A modern QR Code-Based Attendance Management System built with Laravel 12, Tailwind CSS, and MySQL.

The system enables organizations to manage Staff and Attachee attendance through QR code check-ins, user approval workflows, profile completion, and attendance reporting.

## Features

* User Registration & Login
* Admin Approval Workflow
* Mandatory Profile Completion
* QR Code Attendance Check-In
* Attendance History Tracking
* Attendance Reports & Analytics
* PDF Report Export
* Role-Based Access Control (Admin, Staff, Attachee)
* User Management Dashboard

## Tech Stack

* Laravel 12
* PHP 8.2+
* MySQL
* Tailwind CSS
* Laravel Breeze
* DomPDF
* Simple QrCode

## Quick Setup

```bash
git clone <repository-url>
cd lish-checkin

composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate --seed

npm run build

php artisan serve
```

Visit:

```text
http://127.0.0.1:8000
```

## System Workflow

```text
Register
↓
Admin Approval
↓
Login
↓
Complete Profile
↓
Dashboard Access
↓
QR Attendance Check-In
```

## User Roles

### Admin

* Approve or Reject Users
* Generate Daily QR Codes
* Manage Users
* View Attendance Reports
* Export Reports

### Staff

* Complete Profile
* Scan QR Attendance
* View Attendance History
* Update Profile

### Attachee

* Complete Profile
* Scan QR Attendance
* View Attendance History
* Update Profile

## Developed By

**Maithya Ndavi**
collinsheights@gmail.com/ +254112225426
Software Engineer, Lish AI Labs

Building intelligent, scalable, and impactful software solutions.
