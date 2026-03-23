<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SavsoftCategory extends Model
{
    protected $table = 'savsoft_category';
    protected $primaryKey = 'cid';
    public $timestamps = false;

    protected $fillable = ['category_name'];
}