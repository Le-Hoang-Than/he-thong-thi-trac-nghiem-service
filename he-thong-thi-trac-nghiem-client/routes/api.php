<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController; // Đảm bảo bạn đã có UserController ở project Service này nhé


Route::get('/users', [UserController::class, 'index']);