<?php

namespace App\Http\Controllers;
use App\Models\SavsoftAnswer;
use App\Models\SavsoftQbank;
use Illuminate\Http\Request;
use App\Models\SavsoftOption;
use App\Models\User;
use App\Models\SavsoftResult;
use App\Models\SavsoftQuiz;
class QuestionController extends Controller
{
    public function index(Request $request, $quid = 62)
{
    $token = $request->bearerToken();
    $user = \App\Models\User::where('web_token', $token)->first();

    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $uid = $user->uid;
    // 1. KIỂM TRA "SỔ": Dùng Model SavsoftResult tìm bài thi đang mở
    $existingResult = SavsoftResult::where('uid', $uid)
        ->where('quid', $quid)
        ->where('result_status', 'Open')
        ->first();

    if ($existingResult) {
        $qids = explode(',', $existingResult->r_qids);
        $questions = \App\Models\SavsoftQbank::whereIn('qid', $qids)
        ->with('options')
        ->get()
        ->sortBy(fn($m) => array_search($m->qid, $qids))
        ->values();

    $time_spent = time() - $existingResult->start_time; 
    $total_time = 2100; // 35 phút = 2100 
    $time_left = $total_time - $time_spent;
    if ($time_left <= 0) {
    // 1. Cập nhật trạng thái bài thi thành Closed ngay lập tức
    $existingResult->result_status = 'Closed';
    $existingResult->end_time = time();
    $existingResult->save();

    // 2. Trả về thông báo hết giờ thay vì trả về bộ câu hỏi
    return response()->json([
        'message' => 'Hết thời gian làm bài!',
        'status' => 'timeout',
        'rid' => $existingResult->rid,
        'time_left' => 0,
        'data' => [] // Không cho xem câu hỏi nữa để tránh gian lận
    ]);
}
    return response()->json([
        'message' => 'Đang thi dở',
        'rid' => $existingResult->rid,
        'time_left' => $time_left > 0 ? $time_left : 0,
        'data' => $questions
    ]);
}

    // 2. BỐC ĐỀ MỚI: (Giữ nguyên logic bốc từ qcl)
    $structure = \App\Models\SavsoftQcl::where('quid', $quid)->get();
    $allSelectedQids = [];

    foreach ($structure as $row) {
        $qids = \App\Models\SavsoftQbank::where('cid', $row->cid)
            ->inRandomOrder() 
            ->limit($row->noq)
            ->pluck('qid')
            ->toArray();
            
        $allSelectedQids = array_merge($allSelectedQids, $qids);
    }
    
    shuffle($allSelectedQids); 

    // 3. GHI SỔ: Dùng Model để tạo mới bản ghi
    $newResult = new SavsoftResult();
    $newResult->uid = $uid;
    $newResult->quid = $quid;
    $newResult->r_qids = implode(',', $allSelectedQids);
    $newResult->result_status = 'Open';
    $newResult->start_time = time();
    $newResult->end_time = 0; 
    $newResult->score_obtained = 0;
    $newResult->percentage_obtained = 0; 
    $newResult->attempted_ip = $request->ip(); 
    $newResult->save();

    $rid = $newResult->rid; // Lấy rid vừa tự sinh ra

    // 4. TRẢ VỀ:
    $finalQuestions = SavsoftQbank::whereIn('qid', $allSelectedQids)
        ->with('options')
        ->get()
        ->sortBy(fn($m) => array_search($m->qid, $allSelectedQids))->values();

    return response()->json([
        'status' => 'success',
        'rid' => $rid,
        'time_left' => 10, // Mặc định 35 phút cho lần thi mới
        'total' => $finalQuestions->count(),
        'data' => $finalQuestions
    ]);
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
    $rid = $request->rid; // Lấy rid từ request để liên kết với lượt thi hiện tại
    // Lấy điểm từ bảng options để biết đúng hay sai
    $option = \App\Models\SavsoftOption::where('oid', $oid)->first();
    $score = ($option && $option->score > 0) ? 1 : 0;

    // LƯU VÀO DATABASE
   \App\Models\SavsoftAnswer::updateOrCreate(
    [
        'uid' => $uid, 
        'qid' => $qid, 
        'rid' => $rid // BẮT BUỘC có RID ở đây để không bị đè điểm lần thi trước
    ], 
    [
        'q_option' => $request->oid,
        'score_u' => $score
    ]
);
    return response()->json([
        'status' => 'success',
        'message' => 'Lưu đáp án vào bảng savsoft_answers thành công!'
    ]);
}
public function submitExam(Request $request,$rid)
{
    // 1. Lấy user từ Token (giống như hàm lưu đáp án)
    $token = $request->bearerToken();
    $user = \App\Models\User::where('web_token', $token)->first();

    if (!$user) {
        return response()->json(['message' => 'Phiên đăng nhập hết hạn'], 401);
    }

    $uid = $user->uid;

    // 1. Chỉ tính điểm cho những câu thuộc đúng lượt thi (rid) này
    $answers = \App\Models\SavsoftAnswer::where('uid', $uid)
                ->where('rid', $rid); 

    $total_answered = $answers->count();
    $total_score = $answers->sum('score_u'); // Tổng số câu đúng

    // 2. Tính thang điểm 10 (Giả sử đề 40 câu)
    $final_grade = ($total_score / 40) * 10;
    $isTimeout = $request->input('is_timeout', false);
    // 3. GHI VÀO SỔ (Cập nhật Model SavsoftResult)
    $result = \App\Models\SavsoftResult::find($rid);
    if ($result) {
        $result->score_obtained = $total_score;
        $result->result_status = 'Closed'; 
        $result->end_time = time();
        
        
        $result->save();
    }

    return response()->json([
        'status' => 'success',
        'message' => $isTimeout ? 'Đã hết giờ, hệ thống tự động lưu bài!' : 'Nộp bài thành công!',
        'result' => [
            'rid' => $rid,
            'student_name' => $user->first_name . ' ' . $user->last_name,
            'total_correct' => $total_score,
            'final_grade' => round($final_grade, 2),
            'submitted_at' => date('Y-m-d H:i:s', time()) // Trả thêm thời gian nộp cho rõ ràng
        ]
    ], 200);
}
public function getExamHistory(Request $request)
{
    // 1. Lấy Token từ Header và tìm User
    $token = $request->bearerToken();
    $user = \App\Models\User::where('web_token', $token)->first();

    // Kiểm tra nếu không tìm thấy User (Token sai hoặc hết hạn)
    if (!$user) {
        return response()->json(['message' => 'Bạn cần đăng nhập để xem lịch sử'], 401);
    }

    $uid = $user->uid; // Bây giờ chắc chắn có UID rồi nè

    // 2. Dùng Model lấy lịch sử
    $history = \App\Models\SavsoftResult::with('quiz:quid,quiz_name')
        ->where('uid', $uid)
        ->orderBy('rid', 'desc')
        ->get();

    return response()->json([
        'status' => 'success',
        'data' => $history
    ], 200);
}
public function viewResultDetail($rid)
{
    // 1. Dùng Model để tìm bài thi theo rid, kèm luôn thông tin bộ đề (quiz) cho xịn
    $result = \App\Models\SavsoftResult::with('quiz')->find($rid);

    // 2. Kiểm tra nếu không tồn tại
    if (!$result) {
        return response()->json(['message' => 'Không tìm thấy bài thi này!'], 404);
    }

    // 3. Tách chuỗi r_qids (VD: "863,860...") thành mảng
    $qids = explode(',', $result->r_qids);

    // 4. Lấy chi tiết câu hỏi kèm các lựa chọn (options)
    // Sắp xếp lại đúng thứ tự lúc thi bằng sortBy
    $questions = \App\Models\SavsoftQbank::whereIn('qid', $qids)
        ->with('options')
        ->get()
        ->sortBy(fn($m) => array_search($m->qid, $qids))
        ->values();

    // 5. Trả về kết quả
    return response()->json([
        'status' => 'success',
        'info' => $result,       // Chứa thông tin điểm, thời gian, tên bộ đề
        'questions' => $questions // Chứa nội dung 40 câu hỏi và các đáp án
    ], 200);
}
public function getShowCid61()
{
    $quiz = SavsoftQuiz::where('quid', 61)->first();
    if (!$quiz) {
        return response()->json(['message' => 'Không tìm thấy đề thi'], 404);
    }
    return response()->json([
        'status' => 'success',
        'data' => [
            'Tên đề thi:' => $quiz->quiz_name,
            'Thời lượng(phút)' => $quiz->duration, // Ví dụ: 45 (phút)
            'Số lượng câu hỏi:' => $quiz->noq,
            'Số lần làm bài:' => $quiz->question_selection, //  (chỉ được làm 1 lần)
            ]
    ]);
}
}
