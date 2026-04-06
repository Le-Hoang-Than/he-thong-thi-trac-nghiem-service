<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
   public function index()
    {
      
        $response = Http::withoutVerifying()->get(env('BASE_API') . '/test-users');
        
        $users = $response->json() ?? [];

        return view('users', compact('users'));
    }

    public function store(Request $request)
    {
        
        Http::withoutVerifying()->post(env('BASE_API') . '/users', [
            'name' => $request->name
        ]);

        return redirect('/');
    }
}