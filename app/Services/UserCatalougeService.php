<?php
namespace App\Services;

use  App\Services\Interfaces\UserCatalougeServiceInterface;
use App\Repositories\Interfaces\UserCatalougeRepositoryInterface as UserCatalougeRepository;//tương tác với database
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;



class UserCatalougeService implements UserCatalougeServiceInterface
{
    protected $userCatalougeRepository;
    protected $userRepository;
    public function __construct(UserCatalougeRepository $userCatalougeRepository , UserRepository $userRepository){
        $this->userCatalougeRepository=$userCatalougeRepository;
        $this->userRepository=$userRepository;
    }
//userRepository là dependency của class UserService vì UserService phụ thuộc userRepository

    public function paginate($request){
       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       $userCatalouges=$this->userCatalougeRepository->pagination($this->paginateSelect(),$condition,[],['path'=>'user/catalouge/index'],['users']); 
       return $userCatalouges;
    }

    public function paginateSelect(){
        return ['id','description','name','publish'];
    }

    public function create($request){

        DB::beginTransaction();
        try{
            //$payload lấy dữ liệu từ các input request
            $payload=$request->input();
          
            
            //lấy dữ liệu từ payload để thêm vào database bằng create() từ userRepository
            $user=$this->userCatalougeRepository->create($payload);//$user biến đại diện cho model User
           
            DB::commit();
            return true;//thêm dữ liệu thành công
        }
        catch(\Exception $e){
            DB::rollback();
            echo $e->getMessage();
            return false;
        }

    }

    public function update($id,$request){

        DB::beginTransaction();
        try{
            //$payload lấy dữ liệu từ các input request
            $payload=$request->input();
           
           
         
            //lấy dữ liệu từ payload và $id để thêm vào database bằng update() từ userCatalougeRepository
            $user=$this->userCatalougeRepository->update($id,$payload);
          
           
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
            
            $user=$this->userCatalougeRepository->destroy($id);
          
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
            
            $payload[$post['field']] =(($post['value']==1)?2 :1) ;//nếu value=1 gán bằng  2 còn lại =1
            $user=$this->userCatalougeRepository->update($post['modelId'],$payload);
            $this->changeUserStatus($post,$payload[$post['field']]);
            DB::commit();
            return true;//xóa dữ liệu thành công
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
            $user=$this->userCatalougeRepository->updateByWhereIn('id',$post['id'],$payload);
            $this->changeUserStatus($post,$post['value']);
            DB::commit();
            return true;
        }
        catch(\Exception $e){
            DB::rollback(); 
            dd($e->getMessage());
            return false;
        }
    }

    public function changeUserStatus($post,$value){

        DB::beginTransaction();
        try{
            $array=[];
            if(isset($post['modelId'])){
                $array[]=$post['modelId'];
            }else{
                $array=$post['id'];
            }
           $payload[$post['field']]=$value;
        
           $this->userRepository->updateByWhereIn('user_catalouge_id',$array,$payload);//update theo model của userRepository(User) theo trường user_catalouge_id
          


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