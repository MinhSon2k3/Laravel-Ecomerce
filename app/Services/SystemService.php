<?php
namespace App\Services;

use  App\Services\Interfaces\SystemServiceInterface;
use App\Repositories\Interfaces\SystemRepositoryInterface as SystemRepository;//tương tác với database
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class SystemService implements SystemServiceInterface
{
    protected $systemRepository;
    public function __construct(SystemRepository $systemRepository){
        $this->systemRepository=$systemRepository;
    }
//SystemRepository là dependency của class SystemService vì SystemService phụ thuộc SystemRepository

   

    public function save($request,$languageId){

        DB::beginTransaction();
        try{
            //$payload lấy dữ liệu từ các input request
            $config=$request->input('config');
            $payload=[];
            if(count($config)){
                foreach($config as $key =>$val){
                    $payload=[
                        'keyword'=>$key,
                        'content'=>$val,
                        'language_id'=>$languageId,
                        'user_id'=>Auth::id()
                    ];
                    $condition = [
                        'keyword' => $key,
                        'language_id' => (int) $languageId,
                        'user_id' => (int) Auth::id(),
                    ];
                    $this->systemRepository->updateOrInsert($condition,$payload);
                }
            }
          
            DB::commit();
            return true;//thêm dữ liệu thành công
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
            return false;
        }

    }

    

}