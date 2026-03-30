<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum')
;
Route::get('/test-users', function () {
    
    return Illuminate\Support\Facades\DB::table('savsoft_users')->get();
});
