<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\InvestmentPlanController;
use Illuminate\Support\Facades\Route;

// Public Route
Route::get('/', function () {
    return view('welcome');
});

// Member Dashboard Route (Verified only)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Member Routes (Authenticated)
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Wallet
    Route::get('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/deposit', [WalletController::class, 'depositPost'])->name('wallet.deposit.post');
    Route::get('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdrawPost'])->name('wallet.withdraw.post');
    Route::get('/wallet/transfer', [WalletController::class, 'transfer'])->name('wallet.transfer');
    Route::post('/wallet/transfer', [WalletController::class, 'transferPost'])->name('wallet.transfer.post');

    // KYC
    Route::get('/kyc', [KycController::class, 'index'])->name('kyc.index');
    Route::post('/kyc', [KycController::class, 'store'])->name('kyc.store');

    // Investment
    Route::get('/investment', [InvestmentController::class, 'index'])->name('investment.index');
    Route::post('/investment', [InvestmentController::class, 'invest'])->name('investment.invest');
    Route::get('/investment/history', [InvestmentController::class, 'history'])->name('investment.history');

    // Leave Impersonate (Must be accessible while impersonating a regular user)
    Route::get('/admin/leave-impersonate', [AdminController::class, 'leaveImpersonate'])->name('admin.leave_impersonate');
});

// Admin Routes (Authenticated + IsAdmin)
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    
    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::post('/users/{id}/update', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{id}/balance', [AdminController::class, 'adjustBalance'])->name('users.balance');
    Route::post('/users/{id}/reset-password', [AdminController::class, 'resetPassword'])->name('users.reset_password');
    Route::post('/users/{id}/toggle-withdraw', [AdminController::class, 'toggleWithdraw'])->name('users.toggle_withdraw');
    Route::get('/users/{id}/impersonate', [AdminController::class, 'impersonate'])->name('users.impersonate');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // KYC management
    Route::get('/kyc', [AdminController::class, 'kyc'])->name('kyc');
    Route::post('/kyc/{id}/approve', [AdminController::class, 'approveKyc'])->name('kyc.approve');
    Route::post('/kyc/{id}/reject', [AdminController::class, 'rejectKyc'])->name('kyc.reject');

    // Wallet transaction management
    Route::get('/wallet', [AdminController::class, 'wallet'])->name('wallet');
    Route::post('/wallet/{id}/approve', [AdminController::class, 'approveTx'])->name('wallet.approve');
    Route::post('/wallet/{id}/reject', [AdminController::class, 'rejectTx'])->name('wallet.reject');

    // Investment Plan CRUD
    Route::resource('plans', InvestmentPlanController::class)->except(['show']);
});

require __DIR__.'/auth.php';
