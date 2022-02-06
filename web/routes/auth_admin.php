<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminNewPasswordController;
use App\Http\Controllers\Auth\AdminVerifyEmailController;
use App\Http\Controllers\Auth\AdminRegisteredUserController;
use App\Http\Controllers\Auth\AdminExpiredPasswordController;
use App\Http\Controllers\Auth\AdminPasswordResetLinkController;
use App\Http\Controllers\Auth\AdminConfirmablePasswordController;
use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Auth\AdminEmailVerificationPromptController;
use App\Http\Controllers\Auth\AdminEmailVerificationNotificationController;

// Admin Routes
Route::group(array('prefix' => 'admin'), function() {

    Route::get('/login', [AdminAuthenticatedSessionController::class, 'create'])
        ->middleware('guest')
        ->name('admin.login');

    Route::post('/login', [AdminAuthenticatedSessionController::class, 'store'])
        ->middleware('guest');

    Route::post('/logout', [AdminAuthenticatedSessionController::class, 'destroy'])
        ->middleware('admin')
        ->name('admin.logout');


    // Si no hay registro en admnistracion comentamos las siguientes rutas
    // Route::get('/register', [AdminRegisteredUserController::class, 'create'])
    //                 ->middleware('guest')
    //                 ->name('admin.register');

    // Route::post('/register', [AdminRegisteredUserController::class, 'store'])
    //                 ->middleware('guest');


    Route::get('/forgot-password', [AdminPasswordResetLinkController::class, 'create'])
                    ->middleware('guest')
                    ->name('admin.password.request');

    Route::post('/forgot-password', [AdminPasswordResetLinkController::class, 'store'])
                    ->middleware('guest')
                    ->name('admin.password.email');

    Route::get('/reset-password/{token}', [AdminNewPasswordController::class, 'create'])
                    ->middleware('guest')
                    ->name('admin.password.reset');

    Route::post('/reset-password', [AdminNewPasswordController::class, 'store'])
                    ->middleware('guest')
                    ->name('admin.password.update');


    Route::get('/verify-email', [AdminEmailVerificationPromptController::class, '__invoke'])
                    ->middleware('admin')
                    ->name('admin.verification.notice');

    Route::get('/verify-email/{id}/{hash}', [AdminVerifyEmailController::class, '__invoke'])
                    ->middleware(['admin', 'signed', 'throttle:6,1'])
                    ->name('admin.verification.verify');

    Route::post('/email/verification-notification', [AdminEmailVerificationNotificationController::class, 'store'])
                    ->middleware(['admin', 'throttle:6,1'])
                    ->name('admin.verification.send');

    Route::get('/confirm-password', [AdminConfirmablePasswordController::class, 'show'])
                    ->middleware('admin')
                    ->name('admin.password.confirm');

    Route::post('/confirm-password', [AdminConfirmablePasswordController::class, 'store'])
                    ->middleware('admin');

    Route::get('password-expired', [AdminExpiredPasswordController::class, 'expired'])
                    ->name('admin.password.expired')
                    ->middleware('admin');

    Route::post('password-expired', [AdminExpiredPasswordController::class, 'postExpired'])
                    ->name('admin.password.post_expired')
                    ->middleware('admin');

    Route::get('password-expired-forgot', [AdminExpiredPasswordController::class, 'expiredForgot'])
                    ->name('admin.password.expired-forgot')
                    ->middleware('admin');


});





