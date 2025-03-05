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



 public function save($request, $languageId){
    DB::beginTransaction();
    try{
        $payload=$request->only('menu','menu_catalouge_id');
        if(count($payload['menu']['name'])){
            foreach($payload['menu']['name'] as $key =>$val){
                $menuId=$payload['menu']['id'][$key]; 
                $menuArray=[
                    'menu_catalouge_id' => $payload['menu_catalouge_id'] ,
                    'order' => $payload['menu']['order'][$key],
                    'user_id' => Auth::id()
                ];  
                if($menuId==0){
                    $menuSave=$this->menuRepository->create($menuArray);
                }
                else{
                    $menuSave=$this->menuRepository->update($menuId,$menuArray);
                    if($menuSave->rgt - $menuSave->lft >1){
                        $this->menuRepository->updateByWhere(
                            [
                                ['lft','>',$menuSave->lft],
                                ['rgt','<',$menuSave->rgt],
                            ],[ 'menu_catalouge_id' => $payload['menu_catalouge_id']]
                        );
                    }
                }
                if (!empty($menuSave)) {

                    $menuSave->languages()->detach([$languageId,$menuSave->id]);
                    $payloadLanguage=[
                        'language_id'=>$languageId,
                        'name'=>$val,
                        'canonical'=>$payload['menu']['canonical'][$key]
                    ];
                    $this->menuRepository->createPivot($menuSave,$payloadLanguage,'languages');
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

public function saveChildren($request, $languageId, $menu){
    DB::beginTransaction();
    try{
        $payload=$request->only('menu');
        if(count($payload['menu']['name'])){
            foreach($payload['menu']['name'] as $key =>$val){

                $menuId=$payload['menu']['id'][$key]; 
                $menuArray=[
                    'menu_catalouge_id' =>$menu->menu_catalouge_id,
                    'parent_id'=>$menu->id,
                    'order' => $payload['menu']['order'][$key],
                    'user_id' => Auth::id()
                ];
                if($menuId==0){
                    $menuSave=$this->menuRepository->create($menuArray);
                }
                else{
                    $menuSave=$this->menuRepository->update($menuId,$menuArray);
                }

                if (!empty($menuSave)) {
                    $menuSave->languages()->detach([$languageId,$menu->id]);
                    $payloadLanguage=[
                        'language_id'=>$languageId,
                        'name'=>$val,
                        'canonical'=>$payload['menu']['canonical'][$key]
                    ];
                    $this->menuRepository->createPivot($menuSave,$payloadLanguage,'languages');
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

public function getAndconvertMenu($menu = null): array {
    $menuList = $this->menuRepository->findByConditionAndRelation([
        ['parent_id','=',$menu->id],
        ],['languages']);
    return $this->convertMenu($menuList);
}

public function convertMenu($menuList){
    $temp = [];
    $fields = ['name', 'canonical', 'order', 'id'];
    if (count($menuList)) {
        foreach ($menuList as $key => $val) {
            foreach ($fields as $field) {
                if ($field == 'name' || $field == 'canonical') {
                    $temp[$field][] = $val->languages->first()->pivot->{$field};
                } else {
                    $temp[$field][] = $val->{$field};
                }
            }
        }
    }
    return $temp;
   }

   public function dragUpdate(array $json = [], int $menuCatalougeId = 0, $parentId = 0){
        if (count($json)) {
            foreach ($json as $key => $val) {
                $update = [
                    'order' => count($json) - $key,
                    'parent_id' => $parentId,
                ];

                $menu = $this->menuRepository->update($val['id'], $update);

                if (isset($val['children']) && count($val['children'])) {
                    $this->dragUpdate($val['children'], $menuCatalougeId, $val['id']);
                }
            }
        } 
        $this->nestedsetbie->Get('level ASC,order ASC');
        $this->nestedsetbie->Recursive(0,$this->nestedsetbie->Set());
        $this->nestedsetbie->Action();
   }



}