<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;  // dăng ký đăng nhập 

// Route::post('/register',[AuthController::class,'register']); 
Route::post('/login',[AuthController::class,'login']);
Route::middleware(['check.student'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // API lấy đề thi
    Route::get('/exam-questions', function () {
        // Lấy thông tin user đã check được từ middleware
        $user = request()->attributes->get('auth_user');
        return response()->json([
            'message' => "Chào $user->first_name, mời bạn làm bài!",
            'data' => [] 
        ]);
    });
});

