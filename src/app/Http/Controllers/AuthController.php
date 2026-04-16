<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        return view('attendance.index', [
            'status' => '未出勤'
        ]);
    }

    public function store(Request $request)
    {

    }
}
