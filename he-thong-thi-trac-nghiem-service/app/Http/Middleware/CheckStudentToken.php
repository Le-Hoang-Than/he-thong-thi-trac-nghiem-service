<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStudentToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
 public function handle($request, \Closure $next)
{
    // 1. Lấy token từ Header (Bearer Token)
    $token = $request->bearerToken();

    // 2. Kiểm tra trong bảng savsoft_users xem có ai khớp token này không
    $user = \App\Models\User::where('web_token', $token)
                            ->whereNotNull('web_token')
                            ->first();

    // 3. Nếu không thấy thì báo lỗi 401
    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Phiên đăng nhập hết hạn hoặc không hợp lệ.'
        ], 401);
    }

    // 4. Nếu hợp lệ, gán user vào request để Controller sau này dùng luôn
    $request->attributes->set('auth_user', $user);

    return $next($request);
}
}
