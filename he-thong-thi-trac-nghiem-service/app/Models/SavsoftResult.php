<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftResult extends Model
{
    protected $table = 'savsoft_result'; // Khai báo tên bảng thực tế
    protected $primaryKey = 'rid';       // Khai báo khóa chính (vì Savsoft dùng rid chứ không phải id)
    public $timestamps = false;          // Savsoft dùng start_time (int) chứ không dùng created_at

    // Khai báo các trường có thể gán giá trị (fillable)
    protected $attributes = [ 
        'end_time' => 0,
    'score_obtained' => 0,
    'percentage_obtained' => 0, // Tên đúng trong ảnh
    'categories' => '',
    'category_range' => '',
    'individual_time' => '',    // Cột số 10 trong ảnh
    'attempted_ip' => '',      // Cột số 14 (Thay cho 'ips')
    'score_individual' => '',   // Cột số 15
    'photo' => '',
    ];
    // Ràng buộc với sinh viên
    public function user() {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }

    // Ràng buộc với bộ đề
    public function quiz() {
        return $this->belongsTo(SavsoftQuiz::class, 'quid', 'quid');
    }
}
?>