<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// --- CÁC API KHÔNG CẦN ĐĂNG NHẬP ---
Route::post('/login', [AuthController::class, 'login']);


// --- CÁC API YÊU CẦU PHẢI ĐĂNG NHẬP (Gửi kèm Token) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // apiResource tự động tạo ra 4 routes:
    // GET /users (Lấy danh sách)
    // POST /users (Thêm mới)
    // PUT /users/{id} (Cập nhật)
    // DELETE /users/{id} (Xóa)
    Route::post('/users/import', [UserController::class, 'import']);
    Route::apiResource('users', UserController::class); 
});