<?php

use App\Http\Controllers\TapeCollectionController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/koleksi');

// Ubah status satu tindakan (dipanggil via fetch/AJAX dari halaman detail).
// scopeBindings() memastikan {action} benar-benar milik {koleksi}.
Route::patch('koleksi/{koleksi}/tindakan/{action}/status', [TapeCollectionController::class, 'updateActionStatus'])
    ->name('koleksi.actions.status')
    ->scopeBindings();

// Menghasilkan 7 route: index, create, store, show, edit, update, destroy
Route::resource('koleksi', TapeCollectionController::class);
