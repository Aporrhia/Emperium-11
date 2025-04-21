<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\TierListController;
use App\Http\Controllers\PrivacySettingController;
use App\Http\Controllers\RaceController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Properties
Route::get('/properties', [PropertyController::class, 'properties'])->name('properties');
Route::get('/businesses', [PropertyController::class, 'businesses'])->name('businesses');
Route::get('/property/{id}/{type}', [PropertyController::class, 'show'])->name('property.show')->middleware('auth');
Route::post('/property/{id}/buy', [PropertyController::class, 'buy'])->name('property.buy')->middleware('auth');

// Tier List
Route::get('/tier-list', [TierListController::class, 'index'])->name('tier.list');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::patch('/profile/avatar', [UserController::class, 'updateAvatar'])->name('profile.updateAvatar');
    Route::patch('/profile/banner', [UserController::class, 'updateBanner'])->name('profile.updateBanner');
    Route::post('/races/{race}/bid', [RaceController::class, 'placeBid'])->name('races.bid');
    
    // Privacy Settings Routes
    Route::get('/privacy-settings', [UserController::class, 'privacySettings'])->name('privacy.settings');
    Route::post('/privacy-settings', [UserController::class, 'updatePrivacySettings'])->name('privacy.settings.update');
});

// Race Routes
Route::get('/races', [RaceController::class, 'index'])->name('races');
Route::get('/races/create', [RaceController::class, 'createRace'])->name('races.create')->middleware('auth');
Route::get('/races/{race}', [RaceController::class, 'show'])->name('races.show');
Route::post('/races/{race}/bid', [RaceController::class, 'placeBid'])->name('races.bid');
Route::post('/races/{race}/simulate', [RaceController::class, 'simulateRace'])->name('races.simulate')->middleware('auth');
Route::post('/races/update-statuses', [RaceController::class, 'updateStatuses'])->name('races.updateStatuses');