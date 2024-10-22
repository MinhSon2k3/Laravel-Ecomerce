<?php
namespace App\Services;

use  App\Services\Interfaces\UserServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;//tương tác với database
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;



class UserService implements UserServiceInterface
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository){
        $this->userRepository=$userRepository;
    }
//userRepository là dependency của class UserService vì UserService phụ thuộc userRepository

    public function paginate($request){
       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       $condition['user_catalouge_id'] = $request->integer('user_catalouge_id');
     
       $users=$this->userRepository->pagination($this->paginateSelect(),$condition,[],['path'=>'user/index']); 
       return $users;
        
    }

    public function paginateSelect(){
        return ['id','email','name','phone','address','publish','user_catalouge_id'];    
    }

    public function create($request){

        DB::beginTransaction();
        try{
            //$payload lấy dữ liệu từ các input request
            $payload=$request->input();
           
            $payload['password']=Hash::make($payload['password']);
            
            //lấy dữ liệu từ payload để thêm vào database bằng create() từ userRepository
            $user=$this->userRepository->create($payload);//$user biến đại diện cho model User
           
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
           
         
            //lấy dữ liệu từ payload và $id để thêm vào database bằng update() từ userRepository
            $user=$this->userRepository->update($id,$payload);//$user biến đại diện cho model User
          
           
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
            
            $user=$this->userRepository->destroy($id);
          
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
            $user=$this->userRepository->update($post['modelId'],$payload);
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
            $user=$this->userRepository->updateByWhereIn('id',$post['id'],$payload);
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