<?php

use App\Livewire\Landing;
use App\Livewire\PPA\CreatePPA;
use Illuminate\Support\Facades\Route;

Route::get('/', Landing::class);

Route::prefix('ppa')->group(
    function () {
        Route::get('/', CreatePPA::class);
        Route::get('/{ppa_id}', CreatePPA::class)->name('ppa.update');
    }
);
