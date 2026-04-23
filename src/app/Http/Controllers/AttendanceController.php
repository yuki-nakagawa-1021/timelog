<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $status = 'before';
        return view('attendance.index', compact('status'));
    }

    public function start()
    {
        return redirect('/attendance');
    }

    public function end()
    {
        return redirect('/attendance');
    }

    public function breakStart()
    {
        return redirect('/attendance');
    }

    public function breakEnd()
    {
        return redirect('/attendance');
    }
}