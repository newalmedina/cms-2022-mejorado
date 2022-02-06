<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FrontNewPasswordController;
use App\Http\Controllers\Auth\FrontVerifyEmailController;
use App\Http\Controllers\Auth\FrontRegisteredUserController;
use App\Http\Controllers\Auth\FrontExpiredPasswordController;
use App\Http\Controllers\Auth\FrontPasswordResetLinkController;
use App\Http\Controllers\Auth\FrontConfirmablePasswordController;
use App\Http\Controllers\Auth\FrontAuthenticatedSessionController;
use App\Http\Controllers\Auth\FrontEmailVerificationPromptController;
use App\Http\Controllers\Auth\FrontEmailVerificationNotificationController;

// En PSP front no hay registro
// Route::get('/register', [FrontRegisteredUserController::class, 'create'])
//                 ->middleware('guest')
//                 ->name('register');

// Route::post('/register', [FrontRegisteredUserController::class, 'store'])
//                 ->middleware('guest');

Route::get('/login', [FrontAuthenticatedSessionController::class, 'create'])
                ->middleware('guest')
                ->name('login');

Route::post('/login', [FrontAuthenticatedSessionController::class, 'store'])
                ->middleware('guest');

Route::get('/forgot-password', [FrontPasswordResetLinkController::class, 'create'])
                ->middleware('guest')
                ->name('password.request');

Route::post('/forgot-password', [FrontPasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

Route::get('/reset-password/{token}', [FrontNewPasswordController::class, 'create'])
                ->middleware('guest')
                ->name('password.reset');

Route::post('/reset-password', [FrontNewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.update');

Route::get('/verify-email', [FrontEmailVerificationPromptController::class, '__invoke'])
                ->middleware('auth')
                ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [FrontVerifyEmailController::class, '__invoke'])
                ->middleware(['auth', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/email/verification-notification', [FrontEmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');

Route::get('/confirm-password', [FrontConfirmablePasswordController::class, 'show'])
                ->middleware('auth')
                ->name('password.confirm');

Route::post('/confirm-password', [FrontConfirmablePasswordController::class, 'store'])
                ->middleware('auth');

Route::post('/logout', [FrontAuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');

Route::get('password-expired', [FrontExpiredPasswordController::class, 'expired'])
                ->name('password.expired')
                ->middleware('auth');

Route::post('password-expired', [FrontExpiredPasswordController::class, 'postExpired'])
        ->name('password.post_expired')
        ->middleware('auth');

Route::get('password-expired-forgot', [FrontExpiredPasswordController::class, 'expiredForgot'])
        ->name('password.expired-forgot')
        ->middleware('auth');
