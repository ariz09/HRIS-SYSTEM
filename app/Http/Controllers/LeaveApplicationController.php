<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaveApplicationController extends Controller
{
    public function index() {
        return view('leaves.application');
    }
}
