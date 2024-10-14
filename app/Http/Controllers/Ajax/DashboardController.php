<?php

namespace App\Http\Controllers\Ajax;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class DashboardController extends Controller
{
   

    public function __construct()
    {
      
    }

    public function changeStatus(Request $request){
        $post=$request->input();

        $serviceInterfaceNamespace ='\App\Services\\' .ucfirst($post['model']).'Service';//post['model]=ten service da dang ky trong appserviceprovider
      
        if(class_exists($serviceInterfaceNamespace)){
            $serviceInstance=app($serviceInterfaceNamespace);//app() là một helper function của Laravel, được sử dụng để tương tác với Service Container
        }
        $flag=$serviceInstance->updateStatus($post);    
        return  response()->json(['flag'=>$flag]);
    }

    public function changeStatusAll(Request $request){
        $post=$request->input();
        
        $serviceInterfaceNamespace ='\App\Services\\' .ucfirst($post['model']).'Service';
        if(class_exists($serviceInterfaceNamespace)){
            $serviceInstance=app($serviceInterfaceNamespace);
        }
        $flag=$serviceInstance->updateStatusAll($post);

        return  response()->json(['flag'=>$flag]);
    }
}
