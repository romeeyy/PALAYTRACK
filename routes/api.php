<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RiceTypeController;


// Rice type data is internal to authenticated owner/staff sessions.
Route::middleware('auth')->apiResource('rice-types', RiceTypeController::class);
