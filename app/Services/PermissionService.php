<?php
namespace App\Services;

use  App\Services\Interfaces\PermissionServiceInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;//tương tác với database
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class PermissionService implements PermissionServiceInterface
{
    protected $permissionRepository;
    public function __construct( PermissionRepository $permissionRepository){
        $this->permissionRepository=$permissionRepository;
    }
//permissionRepository là dependency của class permissionService vì permissionService phụ thuộc permissionRepository

    public function paginate($request){
       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       $permissions=$this->permissionRepository->pagination(
        $this->paginateSelect(),
        $condition,
        [],
        ['path'=>'permission/index'],
        [],
        [],
         10); 
       return $permissions;
    }

    public function paginateSelect(){
        return ['id','name','canonical'];
    }

    public function create($request){

        DB::beginTransaction();
        try{
            //$payload lấy dữ liệu từ các input request
            $payload=$request->input();
            $payload['permission_id']=Auth::id();
           
            //lấy dữ liệu từ payload để thêm vào database bằng create() từ permissionRepository
            $permission=$this->permissionRepository->create($payload);//$permission biến đại diện cho model permission
           
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
           
           
         
            //lấy dữ liệu từ payload và $id để thêm vào database bằng update() từ permissionCatalougeRepository
            $permission=$this->permissionRepository->update($id,$payload);
          
           
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
            
            $permission=$this->permissionRepository->destroy($id);
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
            $permission=$this->permissionRepository->update($post['modelId'],$payload);
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
            $permission=$this->permissionRepository->updateByWhereIn('id',$post['id'],$payload);
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
            $permission=$this->permissionRepository->findById($id);
            $this->permissionRepository->update($id,['current'=> 1]);
            $payload=['current'=>0];
            $this->permissionRepository->updateByWhere([
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