<?php

use App\Livewire\Dashboard;
use App\Livewire\Pos;
use App\Livewire\ProductManager;
use App\Livewire\TransactionHistory;
use Illuminate\Support\Facades\Route;

Route::get('/', Pos::class)->name('pos');
Route::get('/pos', Pos::class)->name('pos.index');
Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/produk', ProductManager::class)->name('products');
Route::get('/riwayat', TransactionHistory::class)->name('history');
