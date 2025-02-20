<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller; // Đảm bảo bạn import đúng lớp Controller
use  App\Services\Interfaces\SystemServiceInterface as SystemService;
use  App\Repositories\Interfaces\SystemRepositoryInterface as SystemRepository;
use Illuminate\Http\Request;
use App\Classes\System; 

class SystemController extends Controller
{
    protected $systemLibrary;
    protected $systemService;
    protected $systemRepository;
    public function __construct(System $systemLibrary,SystemService $systemService,SystemRepository $systemRepository){
        $this->systemLibrary=$systemLibrary;
        $this->systemService=$systemService;
        $this->systemRepository=$systemRepository;
        $this->language=$this->currentLanguage();
    }

    public function index(){
        $systemConfig=$this->systemLibrary->config();
        $systems=convert_array($this->systemRepository->all(),'keyword','content');
        $template='backend.system.index';
        $seo = [
            //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
            'meta_title' => __('messages.system') 
          ];
        return view ('backend.dashboard.layout',compact('template','seo','systemConfig','systems'));//truyền biến template vào hàm view để layout lấy view từ home.index
    }

    public  function store(Request $request ){ // validate các thông tin cần create
        if($this->systemService->save($request,$this->language)){
          return redirect()->route('system.index')->with('success', 'Cập nhật thành công');
        }
        return redirect()->route('system.index')->with('error', 'Cập nhật ko thành công');
      }
}
    