<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RiceTypeController;


Route::apiResource('rice-types', RiceTypeController::class);
