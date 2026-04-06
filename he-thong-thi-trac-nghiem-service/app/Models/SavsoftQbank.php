<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftQbank extends Model
{
    protected $table = 'savsoft_qbank';
    protected $primaryKey = 'qid';
    public $timestamps = false;

    // Một câu hỏi có NHIỀU đáp án
    public function options()
    {
        return $this->hasMany(SavsoftOption::class, 'qid', 'qid');
    }
    public function category()
{
    // Một câu hỏi thuộc về MỘT danh mục
    return $this->belongsTo(SavsoftCategory::class, 'cid', 'cid');
}
}
