<?php

use App\Http\Controllers\Admin\AdminBeritaController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDestinationController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ChatbotController;
use App\Models\Destination;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $destinations = Destination::all();

    return view('pages.beranda', compact('destinations'));
})->name('beranda');

Route::get('/peta', function () {
    $destinations = Destination::all();

    return view('pages.peta', compact('destinations'));
})->name('peta');

Route::get('/daftar-produk', function () {
    $destinations = Destination::all();

    return view('pages.daftar-produk', compact('destinations'));
})->name('daftar-produk');

Route::get('/detail/{id?}', function ($id = 1) {
    $destination = Destination::findOrFail($id);

    return view('pages.detail', compact('destination'));
})->name('detail');

Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.detail');

// AI Chatbot Route
Route::post('/chatbot/message', [ChatbotController::class, 'message'])->name('chatbot.message');

Route::get('/login', function () {
    return view('pages.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Socialite Routes
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

// User Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');
});

// Admin Content Management
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('destinations', AdminDestinationController::class);
    Route::resource('berita', AdminBeritaController::class)->parameters(['berita' => 'berita']);
    Route::resource('users', AdminUserController::class);
});
