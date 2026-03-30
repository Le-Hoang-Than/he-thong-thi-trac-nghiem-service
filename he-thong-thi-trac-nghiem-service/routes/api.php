    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;  // dăng ký đăng nhập 
    use App\Http\Controllers\QuestionController; // dăng ký câu hỏi
    use App\Models\SavsoftResult;

    // Route::post('/register',[AuthController::class,'register']); 
    Route::post('/login',[AuthController::class,'login']);
    Route::middleware(['check.student'])->group(function () {
        
        Route::post('/logout', [AuthController::class, 'logout']);
        // API lấy đề thi
        Route::get('/exam-questions/{quid?}', [QuestionController::class, 'index']);
        // API show 40 câu hỏi của mỗi chuyên đềlàm bài
        //Route::get('/questions', [QuestionController::class, 'index']);
        // API lưu câu trả lời 
        Route::post('/submit-exam/{rid}', [QuestionController::class, 'submitExam']);
        Route::post('/save-answer', [QuestionController::class, 'storeAnswer']);
        Route::get('/quizs', [QuestionController::class, 'quizcato']);
        Route::get('/result/{rid}', [QuestionController::class, 'viewResultDetail']);

        // Xem tổng hợp lịch sử
        Route::get('/exam-history', [QuestionController::class, 'getExamHistory']);
    });

