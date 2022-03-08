<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;

Route::post('session/share', [AuthController::class, 'share_session']);