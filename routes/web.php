<?php

use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

Route::get('/{any}', [FrontController::class, 'index'])->where('any', '.*');
