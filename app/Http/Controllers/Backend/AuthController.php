<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        // Hàm khởi tạo (constructor)
    }

    // Hàm kiểm tra nếu đã đăng nhập thì chuyển sang dashboard
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        return view('backend.auth.login');
    }

    // Hàm đăng nhập
    public function login(AuthRequest $request)
    {
        $credentials = [  
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];
    
        if (Auth::attempt($credentials)) {
            // Lấy thông tin người dùng hiện tại
            $user = Auth::user();
            
            // Kiểm tra user_catalogue_id và publish
            if ($user->user_catalouge_id == 1 && $user->publish == 1) {
                return redirect()->route('dashboard.index')->with('success', 'Đăng nhập thành công');
            } else {
                // Đăng xuất nếu không có quyền
                Auth::logout();
                return redirect()->route('auth.admin')->with('error', 'Bạn không có quyền truy cập');
            }
        } else {
            return redirect()->route('auth.admin')->with('error', 'Tài khoản không chính xác');
        }
    }
    

    // Hàm đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.admin');
    }
}
