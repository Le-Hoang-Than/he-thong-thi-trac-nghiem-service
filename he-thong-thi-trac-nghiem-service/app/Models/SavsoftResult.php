<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftResult extends Model
{
    protected $table = 'savsoft_result'; 
    protected $primaryKey = 'rid';       
    public $timestamps = false;          

    // BÙA CHÚ Ở ĐÂY: Mở khóa cho phép Laravel lưu dữ liệu vào TẤT CẢ các cột.
    // Dòng này sẽ sửa triệt để lỗi "ảo ảnh" bị mất dữ liệu.
    protected $guarded = [];

    // Khai báo các trường có giá trị mặc định (nếu cần)
    protected $attributes = [ 
        'end_time' => 0,
        'score_obtained' => 0,
        'percentage_obtained' => 0,
        'categories' => '',
        'category_range' => '',
        'individual_time' => '',    
        'attempted_ip' => '',      
        'score_individual' => '',   
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