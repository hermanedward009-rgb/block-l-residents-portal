<?php

use App\Http\Controllers\Admin\AdminAdviceController;
use App\Http\Controllers\Admin\AdminAnnouncementController;
use App\Http\Controllers\Admin\AdminCleaningController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminElectricityController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\AdviceController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CleaningController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ElectricityController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PendingApprovalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/language/{locale}', LocaleController::class)->name('language.switch');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/pending-approval', PendingApprovalController::class)->name('pending-approval');
});

Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/electricity', [ElectricityController::class, 'index'])->name('electricity.index');
    Route::get('/electricity/submit', [ElectricityController::class, 'create'])->name('electricity.create');
    Route::post('/electricity', [ElectricityController::class, 'store'])->name('electricity.store');

    Route::get('/advice', [AdviceController::class, 'index'])->name('advice.index');
    Route::post('/advice', [AdviceController::class, 'store'])->name('advice.store');
    Route::post('/advice/{post}/comment', [AdviceController::class, 'comment'])->name('advice.comment');
    Route::post('/advice/{post}/react', [AdviceController::class, 'react'])->name('advice.react');

    Route::get('/cleaning', [CleaningController::class, 'index'])->name('cleaning.index');
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

    Route::post('/push-subscriptions', [PushSubscriptionController::class, 'store'])->name('push.store');
    Route::get('/push-subscriptions/vapid-public-key', [PushSubscriptionController::class, 'vapidPublicKey'])->name('push.vapid');
});

Route::middleware(['auth', 'approved', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [AdminUserController::class, 'reject'])->name('users.reject');
    Route::post('/users/{user}/deactivate', [AdminUserController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{user}/reactivate', [AdminUserController::class, 'reactivate'])->name('users.reactivate');
    Route::put('/users/{user}/room', [AdminUserController::class, 'updateRoom'])->name('users.room');
    Route::post('/residents/reset', [AdminUserController::class, 'resetResidents'])->name('residents.reset');
    Route::put('/rooms/{room}/label', [AdminUserController::class, 'updateRoomLabel'])->name('rooms.label');

    Route::post('/electricity/{payment}/verify', [AdminElectricityController::class, 'verify'])->name('electricity.verify');
    Route::post('/electricity/{payment}/reject', [AdminElectricityController::class, 'reject'])->name('electricity.reject');
    Route::get('/electricity/periods', [AdminElectricityController::class, 'periods'])->name('electricity.periods');
    Route::post('/electricity/periods', [AdminElectricityController::class, 'storePeriod'])->name('electricity.periods.store');
    Route::post('/electricity/periods/{period}/activate', [AdminElectricityController::class, 'activatePeriod'])->name('electricity.periods.activate');

    Route::get('/cleaning', [AdminCleaningController::class, 'index'])->name('cleaning.index');
    Route::post('/cleaning', [AdminCleaningController::class, 'store'])->name('cleaning.store');
    Route::put('/cleaning/{schedule}', [AdminCleaningController::class, 'update'])->name('cleaning.update');
    Route::post('/cleaning/{schedule}/complete', [AdminCleaningController::class, 'complete'])->name('cleaning.complete');
    Route::delete('/cleaning/{schedule}', [AdminCleaningController::class, 'destroy'])->name('cleaning.destroy');

    Route::get('/announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AdminAnnouncementController::class, 'store'])->name('announcements.store');
    Route::delete('/announcements/{announcement}', [AdminAnnouncementController::class, 'destroy'])->name('announcements.destroy');

    Route::get('/advice', [AdminAdviceController::class, 'index'])->name('advice.index');
    Route::post('/advice/{post}/remove', [AdminAdviceController::class, 'remove'])->name('advice.remove');
    Route::post('/advice/{post}/restore', [AdminAdviceController::class, 'restore'])->name('advice.restore');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/pdf', [AdminReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/reports/export/excel', [AdminReportController::class, 'exportExcel'])->name('reports.excel');
});