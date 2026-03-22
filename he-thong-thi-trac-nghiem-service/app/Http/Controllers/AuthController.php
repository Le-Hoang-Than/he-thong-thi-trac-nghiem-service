<?php
// đăng ký đăng nhập 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AuthController extends Controller
{
// public function register(Request $request)
// {
//     try {

//         $user = User::create([
//             'email' => $request->email,
//             'password' => md5($request->password),

//             'first_name' => $request->first_name,
//             'last_name' => $request->last_name,

//             'gid' => 2,
//             'su' => 0,
//             'verify_code' => 0,
//             'subscription_expired' => 0,

//             'user_status' => 'Active',
//             'registered_date' => date('Y-m-d H:i:s'),

//             'note' => ''
//         ]);

//         return response()->json([
//             'message' => 'Đăng ký thành công',
//             'user' => $user
//         ]);

//     } catch (\Exception $e) {

//         return response()->json([
//             'error' => $e->getMessage()
//         ]);
//     }
// }
public function login(Request $request)
{
    $request->validate([
        'studentid' => 'required',
        'password' => 'required'
    ]);

    $user = User::where('studentid',$request->studentid)->first();

    if(!$user){
        return response()->json([
            'message'=>'StudentID không tồn tại'
        ],404);
    }

    if(md5($request->password) != $user->password){
        return response()->json([
            'message'=>'Sai mật khẩu'
        ],401);
    }

    // tạo token
    $token = bin2hex(random_bytes(32));

    $user->web_token = $token;
    $user->save();

    return response()->json([
        'message'=>'Đăng nhập thành công',
        'token'=>$token,
        'user'=>$user
    ]);
}

public function logout(Request $request)
{
    // Lấy token từ Header gửi lên
    $token = $request->bearerToken();

    if ($token) {
        $user = \App\Models\User::where('web_token', $token)->first();
        if ($user) {
            $user->web_token = null; // Xóa token
            $user->save();
        }
    }

    return response()->json(['message' => 'Đã đăng xuất thành công']);
}



}
