<?php

use App\Http\Controllers\Auth\{
    AuthenticatedSessionController,
    ConfirmablePasswordController,
    EmailVerificationNotificationController,
    EmailVerificationPromptController,
    NewPasswordController,
    PasswordResetLinkController,
    RegisteredUserController,
    VerifyEmailController
};
use App\Http\Controllers\{MainController, DailyViolationsController, UserController, FileUploadController};
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'guest'], function(){
    Route::get('/register', [RegisteredUserController::class, 'create'])
                    ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                    ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                    ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                    ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                    ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
                    ->name('password.update');
});

Route::group(['middleware' => 'auth'], function(){
    Route::get('/getFilesList',[FileUploadController::class, 'getFilesList'])->name('getFilesList');
    Route::post('/formSubmit',[FileUploadController::class, 'formSubmit'])->name('formSubmit');
     Route::get('/openMonitoringWindow', [MainController::class, 'openMonitoringWindow'])->name('openMonitoringWindow');
     Route::post('/chartData', [DailyViolationsController::class, 'index'])->name('chartData');
     Route::get('/getMonthYearAndDefaults', [DailyViolationsController::class, 'getMonthYearAndDefaults'])->name('getMonthYearAndDefaults');
     Route::post('/getDailyViolations', [DailyViolationsController::class, 'getDailyViolations'])->name('getDailyViolations');
    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                    ->middleware(['auth', 'signed', 'throttle:6,1'])
                    ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                    ->middleware(['auth', 'throttle:6,1'])
                    ->name('verification.send');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])

                    ->name('password.confirm');

    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
;

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])

                    ->name('logout');

    Route::get('/get-users', [UserController::class, 'getUsers'])->name('get-users');
    Route::post('/approve-user', [UserController::class, 'approveUser'])->name('approve-user');

});


