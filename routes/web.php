<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware('auth');

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth');

Route::get('/role-permission', [\App\Http\Controllers\RolePermissionController::class, 'index'])->name('role-permission.index');
Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index')->middleware('auth');

Route::resource('categories', \App\Http\Controllers\CategoryController::class)->middleware('auth');
Route::resource('submissions', \App\Http\Controllers\SubmissionController::class)->middleware('auth');
Route::post('submissions/{id}/approve', [\App\Http\Controllers\SubmissionController::class, 'approve'])->name('submissions.approve')->middleware('auth');
Route::post('submissions/{id}/reject', [\App\Http\Controllers\SubmissionController::class, 'reject'])->name('submissions.reject')->middleware('auth');
Route::post('submissions/{id}/reapply', [\App\Http\Controllers\SubmissionController::class, 'reapply'])->name('submissions.reapply')->middleware('auth');
Route::post('submission-discussions', [\App\Http\Controllers\SubmissionDiscussionController::class, 'store'])->middleware('auth')->name('submission-discussions.store');
Route::post('/submissions/{id}/revisi', [\App\Http\Controllers\SubmissionController::class, 'revision'])->name('submissions.revision')->middleware('auth');

