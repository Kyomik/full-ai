<?php

use App\Http\Controllers\PortalController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortalController::class, 'home'])->name('home');
Route::get('/festival', [PortalController::class, 'events'])->name('events.index');
Route::get('/festival/{event:slug}', [PortalController::class, 'event'])->name('events.show');
Route::get('/berita', [PortalController::class, 'news'])->name('news.index');
Route::get('/berita/{article:slug}', [PortalController::class, 'article'])->name('news.show');
Route::get('/galeri', [PortalController::class, 'gallery'])->name('gallery');
Route::get('/pengumuman', [PortalController::class, 'announcements'])->name('announcements');
Route::get('/kontak', [PortalController::class, 'contact'])->name('contact');
Route::get('/kalender', [PortalController::class, 'calendar'])->name('calendar');
Route::view('/tentang', 'portal.about')->name('about');
Route::get('/daftar-instansi', [RegistrationController::class, 'create'])->name('register.agency');
Route::post('/daftar-instansi', [RegistrationController::class, 'store'])->middleware('throttle:5,1')->name('register.agency.store');
Route::get('/api/wilayah/{level}/{parent?}', [PortalController::class, 'regions'])->where('level', 'provinces|regencies|districts|villages')->name('regions');
