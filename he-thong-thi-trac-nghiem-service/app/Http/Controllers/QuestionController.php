<?php

namespace App\Http\Controllers;
use App\Models\SavsoftAnswer;
use App\Models\SavsoftQbank;
use Illuminate\Http\Request;
use App\Models\SavsoftOption;
use APP\Models\User;

class QuestionController extends Controller
{
    public function index()
    {
        // Lấy danh sách câu hỏi kèm theo các options của từng câu
        // paginate(40) lấy 40 câu hỏi của bài thi
       $questions = SavsoftQbank::with(['category', 'options'])
                ->inRandomOrder() 
                ->limit(40) 
                ->get(); // Dùng get() để lấy danh sách phẳng

    return response()->json($questions, 200);
    }

    public function show($id)
    {
        // Xem chi tiết 1 câu cũng kèm luôn options
        $question = SavsoftQbank::with('options')->find($id);

        if (!$question) {
            return response()->json(['message' => 'Không thấy câu hỏi'], 404);
        }

        return response()->json($question, 200);
    }
    public function storeAnswer(Request $request)
{
    $token = $request->bearerToken();
    $user = \App\Models\User::where('web_token', $token)->first();

    if (!$user) {
        return response()->json(['message' => 'Token không hợp lệ'], 401);
    }

    $uid = $user->uid;
    $qid = $request->qid;
    $oid = $request->oid; // Giá trị lấy từ Postman gửi lên

    // Lấy điểm từ bảng options để biết đúng hay sai
    $option = \App\Models\SavsoftOption::where('oid', $oid)->first();
    $score = ($option && $option->score > 0) ? 1 : 0;

    // LƯU VÀO DATABASE
    \App\Models\SavsoftAnswer::updateOrCreate(
        ['uid' => $uid, 'qid' => $qid], // Điều kiện: 1 user chỉ có 1 đáp án cho 1 câu hỏi
        [
            'q_option' => $oid,   // Đổ giá trị vào đúng cột q_option
            'score_u'  => $score, // Đổ điểm vào đúng cột score_u
            'rid'      => 0       // Tạm thời để 0
        ]
    );

    return response()->json([
        'status' => 'success',
        'message' => 'Lưu đáp án vào bảng savsoft_answers thành công!'
    ]);
}
public function submitExam(Request $request)
{
    // 1. Lấy user từ Token (giống như hàm lưu đáp án)
    $token = $request->bearerToken();
    $user = \App\Models\User::where('web_token', $token)->first();

    if (!$user) {
        return response()->json(['message' => 'Phiên đăng nhập hết hạn'], 401);
    }

    $uid = $user->uid;

    // 2. Đếm tổng số câu hỏi trong đề (ví dụ đề có 40 câu)
    $total_answered = \App\Models\SavsoftAnswer::where('uid', $uid)->count();

    // 3. Tính tổng điểm (Cộng tất cả cột score_u của sinh viên)
    $total_score = \App\Models\SavsoftAnswer::where('uid', $uid)->sum('score_u');

    // 4. Tính phần trăm hoặc thang điểm 10
    // Giả sử mỗi câu 0.25 điểm cho đề 40 câu:
    $final_grade = $total_score * 0.25; 
\App\Models\SavsoftAnswer::where('uid', $uid)->delete();
    return response()->json([
        'status' => 'success',
        'message' => 'Nộp bài thành công!',
        'result' => [
            'student_name' => $user->first_name . ' ' . $user->last_name,
            'total_correct' => $total_score,   // Số câu đúng
            'total_answered' => $total_answered, // Số câu đã làm
            'final_grade' => $final_grade      // Điểm số (thang điểm 10)
        ]
    ], 200);
}
}
