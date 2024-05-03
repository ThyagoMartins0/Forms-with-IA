<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\FormController;

Route::apiResource('forms', FormController::class);