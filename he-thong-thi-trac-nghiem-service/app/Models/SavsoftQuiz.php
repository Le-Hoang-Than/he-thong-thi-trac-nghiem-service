<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftQuiz extends Model
{
    protected $table = 'savsoft_quiz';
    protected $primaryKey = 'quid';
    public $timestamps = false;

    // Khai báo tất cả các trường có trong bảng để có thể thêm/sửa
    protected $fillable = [
        'quiz_name', 
        'description', 
        'start_date', 
        'end_date',
        'gids', 
        'qids',
        'noq',
        'correct_score',
        'incorrect_score',
        'ip_address',
        'duration',
        'maximum_attempts',
        'pass_percentage',
        'view_answer',
        'question_selection',
        'gen_certificate',
        'certificate_text',
        'with_login',
        'quiz_template',
        'demo',
    ];
}