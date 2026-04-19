<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'studentid' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'studentid.required' => 'Mã sinh viên không được để trống',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        try {
            // Đồng nhất dùng BASE_API_URL giống ExamController
            $baseUrl = rtrim(env('BASE_API_URL', 'http://127.0.0.1:8000'), '/');
            
            $response = Http::post($baseUrl . '/api/login', $credentials);

            if ($response->successful()) {
                $data = $response->json();
                session([
                    'auth_token' => $data['token'] ?? null,
                    'user' => $data['user'] ?? null,
                    'studentid' => $data['user']['studentid'] ?? null,
                ]);
                
                return redirect('/exams')->with('success', 'Đăng nhập thành công!');
            } else {
                $errorMsg = $response->json()['message'] ?? 'Đăng nhập thất bại';
                return back()->withErrors(['studentid' => $errorMsg])->withInput();
            }
        } catch (\Exception $e) {
            // SỬA LỖI Ở ĐÂY: In ra nguyên nhân sập thật sự để dễ sửa
            return back()->withErrors(['studentid' => 'Lỗi thực sự là: ' . $e->getMessage()])->withInput();
        }
    }

    public function logout(Request $request)
    {
        // Call backend logout API if token exists
        $token = session('auth_token');
        if ($token) {
            try {
                $baseUrl = rtrim(env('BASE_API_URL', 'http://127.0.0.1:8000'), '/');
                Http::withToken($token)->post($baseUrl . '/api/logout');
            } catch (\Exception $e) {
                // Ignore errors, just logout locally
            }
        }

        session()->flush();
        return redirect('/login')->with('success', 'Đã đăng xuất thành công');
    }

    public function isAuthenticated()
    {
        return session()->has('auth_token') && session()->has('user');
    }

    // Hàm gọi thử API test-users
    public function testUsers()
    {
        try {
            $baseUrl = rtrim(env('BASE_API_URL', 'http://127.0.0.1:8000'), '/');
            $response = Http::get($baseUrl . '/api/test-users');

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'Lỗi từ API: ' . $response->status()], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể kết nối tới server local: ' . $e->getMessage()], 500);
        }
    }
}
