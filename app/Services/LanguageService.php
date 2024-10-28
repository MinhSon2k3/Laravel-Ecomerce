<?php
namespace App\Services;

use  App\Services\Interfaces\LanguageServiceInterface;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;//tương tác với database
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class LanguageService implements LanguageServiceInterface
{
    protected $languageRepository;
    public function __construct( LanguageRepository $languageRepository){
        $this->languageRepository=$languageRepository;
    }
//userRepository là dependency của class UserService vì UserService phụ thuộc userRepository

    public function paginate($request){
       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       $languages=$this->languageRepository->pagination(
        $this->paginateSelect(),
        $condition,
        [],
        ['path'=>'language/index'],
        [],
        [],
         3); 
       return $languages;
    }

    public function paginateSelect(){
        return ['id','name','canonical','publish','description','image','current'];
    }

    public function create($request){

        DB::beginTransaction();
        try{
            //$payload lấy dữ liệu từ các input request
            $payload=$request->input();
            $payload['user_id']=Auth::id();
           
            //lấy dữ liệu từ payload để thêm vào database bằng create() từ languageRepository
            $language=$this->languageRepository->create($payload);//$language biến đại diện cho model Language
           
            DB::commit();
            return true;//thêm dữ liệu thành công
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
            return false;
        }

    }

    public function update($id,$request){

        DB::beginTransaction();
        try{
            //$payload lấy dữ liệu từ các input request
            $payload=$request->input();
           
           
         
            //lấy dữ liệu từ payload và $id để thêm vào database bằng update() từ userCatalougeRepository
            $user=$this->languageRepository->update($id,$payload);
          
           
            DB::commit();
            return true;//sửa dữ liệu thành công
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
            return false;
        }

    }
    public function destroy($id){

        DB::beginTransaction();
        try{
            
            $user=$this->languageRepository->destroy($id);
            DB::commit();
            return true;//xóa dữ liệu thành công
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
            return false;
        }

    }

    public function updateStatus($post=[]){
        DB::beginTransaction();
        try{
            
            $payload[$post['field']] =(($post['value']==1)?2 :1 ) ;//nếu value=1 gán bằng  2 còn lại =1
            $language=$this->languageRepository->update($post['modelId'],$payload);
            DB::commit();
            return true;
        }
        catch(\Exception $e){
            DB::rollback(); 
            dd($e->getMessage());
            return false;   
        }
    }
   
    public function updateStatusAll($post=[]){
        
        DB::beginTransaction();
        try{
            $payload[$post['field']] =$post['value'] ;
            $user=$this->languageRepository->updateByWhereIn('id',$post['id'],$payload);
            DB::commit();
            return true;
        }
        catch(\Exception $e){
            DB::rollback(); 
            dd($e->getMessage());
            return false;
        }
    }

    public function switch($id){
        DB::beginTransaction();
        try{
            $language=$this->languageRepository->findById($id);
            $this->languageRepository->update($id,['current'=> 1]);
            $payload=['current'=>0];
            $this->languageRepository->updateByWhere([
                ['id','!=',$id],
            ],$payload);
            DB::commit();
            return true;
        }
        catch(\Exception $e){
            DB::rollback(); 
            dd($e->getMessage());
            return false;
        }
       
    }
}