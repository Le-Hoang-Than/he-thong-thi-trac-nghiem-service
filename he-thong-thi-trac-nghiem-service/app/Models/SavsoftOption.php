<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftOption extends Model
{
    // Khai báo tên bảng 
    protected $table = 'savsoft_options';

    // Khai báo khóa chính 
    protected $primaryKey = 'oid';

    public $timestamps = false;
protected $hidden = ['score']; // Ẩn cột điểm đi, chỉ để lại nội dung đáp án
    protected $fillable = [
        'qid',
        'q_option', 
        'score'
    ];
}