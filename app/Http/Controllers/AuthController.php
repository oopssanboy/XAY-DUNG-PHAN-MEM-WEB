<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. API Đăng nhập
    public function login(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Tìm user theo email
        $user = User::where('email', $request->email)->first();

        // Kiểm tra user có tồn tại và mật khẩu có đúng không
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không chính xác.'
            ], 401);
        }

        // Tạo Token cho user (cấp thẻ từ)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    // 2. API Đăng xuất
    public function logout(Request $request)
    {
        // Thu hồi toàn bộ thẻ từ (token) của user này
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Đã đăng xuất thành công'
        ]);
    }
}