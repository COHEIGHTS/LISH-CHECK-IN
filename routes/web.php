<?php

use App\Http\Controllers\AdminApprovalController;
use App\Http\Controllers\AdminLeaveController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileSetupController;
use App\Http\Controllers\QRCodeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'check.status', 'role.redirect'])->name('dashboard');

// Role-specific dashboard routes
Route::get('/dashboard/admin', function () {
    return view('dashboard.admin');
})->middleware(['auth', 'verified'])->name('dashboard.admin');

Route::get('/dashboard/staff', [DashboardController::class, 'staff'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.staff');

Route::get('/dashboard/attachee', [DashboardController::class, 'attachee'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.attachee');

Route::get('/attendance/my-attendance', [DashboardController::class, 'myAttendance'])
    ->middleware(['auth', 'verified'])
    ->name('attendance.my');

Route::get('/attendance/history', [DashboardController::class, 'history'])
    ->middleware(['auth', 'verified'])
    ->name('attendance.history');

Route::get('/pending-approval', function () {
    return view('pending-approval');
})->middleware(['auth'])->name('pending.approval');

Route::middleware('auth')->group(function () {
    // Profile Setup Routes
    Route::get('/profile/setup', [ProfileSetupController::class, 'show'])->name('profile.setup');
    Route::post('/profile/setup', [ProfileSetupController::class, 'store'])->name('profile.setup.store');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Approval Routes
    Route::get('/admin/approval', [AdminApprovalController::class, 'index'])
        ->middleware(['auth'])
        ->name('admin.approval');
    Route::get('/admin/users', [AdminApprovalController::class, 'users'])
        ->middleware(['auth'])
        ->name('admin.users');
    Route::post('/admin/approve/{user}', [AdminApprovalController::class, 'approve'])
        ->middleware(['auth'])
        ->name('admin.approve');
    Route::post('/admin/reject/{user}', [AdminApprovalController::class, 'reject'])
        ->middleware(['auth'])
        ->name('admin.reject');
    Route::post('/admin/suspend/{user}', [AdminApprovalController::class, 'suspend'])
        ->middleware(['auth'])
        ->name('admin.suspend');
    Route::post('/admin/activate/{user}', [AdminApprovalController::class, 'activate'])
        ->middleware(['auth'])
        ->name('admin.activate');
    Route::post('/admin/delete/{user}', [AdminApprovalController::class, 'deleteUser'])
        ->middleware(['auth'])
        ->name('admin.delete');
    Route::get('/admin/attendance', [AdminApprovalController::class, 'attendance'])
        ->middleware(['auth'])
        ->name('admin.attendance');
    Route::get('/admin/reports', [AdminApprovalController::class, 'reports'])
        ->middleware(['auth'])
        ->name('admin.reports');
    Route::get('/admin/reports/pdf', [AdminApprovalController::class, 'downloadPDF'])
        ->middleware(['auth'])
        ->name('admin.reports.pdf');

    // QR Code Routes
    Route::get('/qr/generate', [QRCodeController::class, 'index'])
        ->middleware(['auth'])
        ->name('qr.generate');
    Route::get('/qr/scan', [QRCodeController::class, 'scan'])
        ->middleware(['auth'])
        ->name('qr.scan');
    Route::post('/qr/verify', [QRCodeController::class, 'verify'])
        ->middleware(['auth'])
        ->name('qr.verify');

    // Leave Management Routes (User)
    Route::get('/leave', [LeaveController::class, 'index'])
        ->middleware(['auth'])
        ->name('leave.index');
    Route::get('/leave/create', [LeaveController::class, 'create'])
        ->middleware(['auth'])
        ->name('leave.create');
    Route::post('/leave', [LeaveController::class, 'store'])
        ->middleware(['auth'])
        ->name('leave.store');
    Route::get('/leave/{leave}', [LeaveController::class, 'show'])
        ->middleware(['auth'])
        ->name('leave.show');
    Route::delete('/leave/{leave}', [LeaveController::class, 'destroy'])
        ->middleware(['auth'])
        ->name('leave.destroy');

    // Leave Management Routes (Admin)
    Route::get('/admin/leave', [AdminLeaveController::class, 'index'])
        ->middleware(['auth'])
        ->name('admin.leave.index');
    Route::get('/admin/leave/{leave}', [AdminLeaveController::class, 'show'])
        ->middleware(['auth'])
        ->name('admin.leave.show');
    Route::post('/admin/leave/{leave}/approve', [AdminLeaveController::class, 'approve'])
        ->middleware(['auth'])
        ->name('admin.leave.approve');
    Route::post('/admin/leave/{leave}/reject', [AdminLeaveController::class, 'reject'])
        ->middleware(['auth'])
        ->name('admin.leave.reject');
    Route::delete('/admin/leave/{leave}', [AdminLeaveController::class, 'destroy'])
        ->middleware(['auth'])
        ->name('admin.leave.destroy');
});

require __DIR__.'/auth.php';
