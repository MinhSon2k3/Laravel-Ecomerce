<?php
namespace App\Services;

use App\Services\Interfaces\MenuServiceInterface;
use App\Services\BaseService;
use App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;//tương tác với database
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class MenuService  extends BaseService implements MenuServiceInterface
{
    protected $menuRepository;
    public function __construct( MenuRepository $menuRepository){
        $this->menuRepository=$menuRepository;
        $this->nestedsetbie=new Nestedsetbie([
            'table'=>'menus',
            'foreignkey'=>'menu_id',
            'isMenu'=>true,
            'language_id'=>$this->currentLanguage()
        ]);
    }
//userRepository là dependency của class UserService vì UserService phụ thuộc userRepository

public function paginate($request){
   return 0;
     
 }
 public function paginateSelect(){
     return ['id','email','name','phone','address','publish','user_catalouge_id'];    
 }

 public function create($request, $languageId, $menu=null){
    DB::beginTransaction();
    try{
        $payload=$request->only('menu','menu_catalouge_id','type');
        if(count($payload['menu']['name'])){
            foreach($payload['menu']['name'] as $key =>$val){
                $menuArray=[
                    'menu_catalouge_id' => (isset($payload['menu_catalouge_id'])) ? $payload['menu_catalouge_id'] : $menu->menu_catalouge_id ,
                    'parent_id'=>(is_null($menu)) ? 0 : $menu->id,
                    'type' => (is_null($menu)) ? $payload['type'] : '',
                    'order' => $payload['menu']['order'][$key],
                    'user_id' => Auth::id()
                ];  
                dd($menuArray);
                $menu=$this->menuRepository->create($menuArray);
                if (!empty($menu)) {

                    $menu->languages()->detach([$languageId,$menu->id]);
                    $payloadLanguage=[
                        'language_id'=>$languageId,
                        'name'=>$val,
                        'canonical'=>$payload['menu']['canonical'][$key]
                    ];
                    $this->menuRepository->createPivot($menu,$payloadLanguage,'languages');
                }
            }
            $this->nestedsetbie->Get('level ASC,order ASC');
            $this->nestedsetbie->Recursive(0,$this->nestedsetbie->Set());
            $this->nestedsetbie->Action();
        }

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