<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller; // Đảm bảo bạn import đúng lớp Controller
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    public function __constuct(){
        
    }

    public function index(){

        $template='backend.dashboard.home.index';

        return view ('backend.dashboard.layout',compact('template'));//truyền biến template vào hàm view để layout lấy view từ home.index
    }
}
    