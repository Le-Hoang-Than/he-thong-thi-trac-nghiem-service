<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftQcl extends Model
{
    protected $table = 'savsoft_qcl';
    protected $primaryKey = 'qcl_id'; // Khóa chính của bảng này là qcl_id
    public $timestamps = false;
}