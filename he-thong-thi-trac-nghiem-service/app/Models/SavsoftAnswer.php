<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftAnswer extends Model
{
    protected $table = 'savsoft_answers';
    protected $primaryKey = 'aid';
    public $timestamps = false; // Savsoft thường không dùng timestamps chuẩn Laravel

    protected $fillable = [
        'uid',         // ID của sinh viên (lấy từ Token)
        'qid',         // ID của câu hỏi
        'q_option',   // Nội dung đáp án sinh viên chọn 
        'score_u' ,  // 1 nếu đúng, 0 nếu sai
        'rid'       // Cột này là Result ID 
    ];
}