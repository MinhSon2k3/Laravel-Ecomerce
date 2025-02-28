<?php
namespace App\Services;

use  App\Services\Interfaces\MenuCatalougeServiceInterface;
use  App\Services\BaseService;
use  App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\MenuCatalougeRepositoryInterface as MenuCatalougeRepository;//tương tác với database
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class MenuCatalougeService  extends BaseService implements MenuCatalougeServiceInterface
{
    protected $menuCatalougeRepository;

    public function __construct( MenuCatalougeRepository $menuCatalougeRepository){
        $this->menuCatalougeRepository=$menuCatalougeRepository;
    }
  
    public function paginate($request){
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $menuCatalouges=$this->menuCatalougeRepository->pagination(
         $this->paginateSelect(),
         $condition,
         [],
         ['path'=>'menu/index'],
         [],
         [],
          10); 
        return $menuCatalouges;
     }
    public function paginateSelect(){
        return [
            'id',
            'name',
            'keyword',
            'publish'
        ];
    }
    public function updateStatus($post=[]){
        DB::beginTransaction();
        try{
            $payload[$post['field']] =(($post['value']==1)? 2 : 1 ) ;//nếu value=1 gán bằng  2 còn lại =1
            $postCatalouge=$this->menuCatalougeRepository->update($post['modelId'],$payload);
            DB::commit();
            return true;
        }
        catch(\Exception $e){
            DB::rollback(); 
            dd($e->getMessage());
            return false;   
        }
    }
 
    public function create($request){
        DB::beginTransaction();
        try{
            $payload=$request->only('name','keyword');
            $menuCatalouge=$this->menuCatalougeRepository->create($payload);
            DB::commit();
            return [
                'flag'=>TRUE,
                'name'=>$menuCatalouge->name,
                'id'=>$menuCatalouge->id
            ];
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
            return false;
        }

    }



}