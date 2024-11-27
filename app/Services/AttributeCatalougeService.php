<?php
namespace App\Services;

use App\Services\Interfaces\AttributeCatalougeServiceInterface;
use App\Services\BaseService;
use App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\AttributeCatalougeRepositoryInterface as AttributeCatalougeRepository;//tương tác với database
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class AttributeCatalougeService  extends BaseService implements AttributeCatalougeServiceInterface
{
    protected $attributeCatalougeRepository;
    protected $routerRepository;

    public function __construct( attributeCatalougeRepository $attributeCatalougeRepository,RouterRepository $routerRepository ){
        $this->attributeCatalougeRepository=$attributeCatalougeRepository;
        $this->routerRepository=$routerRepository;
        $this->nestedsetbie=new Nestedsetbie([
            'table'=>'attribute_catalouges',
            'foreignkey'=>'attribute_catalouge_id',
            'language_id'=>$this->currentLanguage()
        ]);
    }

    public function paginate($request){

       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       $attributeCatalouges=$this->attributeCatalougeRepository->pagination(
        $this->paginateSelect(),
        $condition,
        [
            ['attribute_catalouge_languages as tb2', 'tb2.attribute_catalouge_id', '=', 'attribute_catalouges.id']
        ],
        ['path'=>'attribute/catalouge/index'],
        [],
        [
            'attribute_catalouges.lft','Asc'
        ],
        ); 
        return $attributeCatalouges;
    }

    public function paginateSelect(){
        return [
        'id',
        'publish',
        'image',
        'level',
        'order',
        'tb2.name',
        'tb2.canonical',
        ];
    }

    public function create($request){

        DB::beginTransaction();
        try{
            //$payload lấy dữ liệu từ các input request
            $payload=$request->only($this->payload());
            $payload['user_id']=Auth::id();
           
            //lấy dữ liệu từ payload để thêm vào database bằng create() từ languageRepository
            $attributeCatalouge=$this->attributeCatalougeRepository->create($payload);//$attributeCatalouge biến đại diện cho model attributeCatalouge
            if($attributeCatalouge->id>0){
                $payloadLanguage=$request->only($this->payloadLanguage());
                $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['attribute_catalouge_id']=$attributeCatalouge->id;
                $language=$this->attributeCatalougeRepository->createTranslatePivot($attributeCatalouge,$payloadLanguage);

                $router=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>$attributeCatalouge->id,
                    'controllers'=>'App\Http\Controllers\Backend\AttributeCatalougeController'
                ];
               $this->routerRepository->create($router);
            }
            $this->nestedsetbie->Get('level ASC,order ASC');
            $this->nestedsetbie->Recursive(0,$this->nestedsetbie->Set());
            $this->nestedsetbie->Action();
        
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
            $attributeCatalouge = $this->attributeCatalougeRepository->findById($id);
            $payload=$request->only($this->payload());
            $flag = $this->attributeCatalougeRepository->update($id, $payload);
            if($flag == TRUE){
                $payloadLanguage=$request->only($this->payloadLanguage());
                $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['attribute_catalouge_id']=$id;
                $attributeCatalouge->languages()->detach([$payloadLanguage['language_id'],$id]);
                $response = $this->attributeCatalougeRepository->createTranslatePivot($attributeCatalouge, $payloadLanguage);

                $payloadRouter=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>$attributeCatalouge->id,
                    'controllers'=>'App\Http\Controllers\Backend\AttributeCatalougeController'
                ];
                $condition=[
                    [ 'module_id','=',$attributeCatalouge->id],
                    [ 'controllers','=','App\Http\Controllers\Backend\AttributeCatalougeController']

                ];
                $router=$this->routerRepository->findByCondition($condition);
                $this->routerRepository->update($router->id,$payloadRouter);

                $this->nestedsetbie->Get('level ASC, order ASC');
                $this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
                $this->nestedsetbie->Action();
            }
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
            
            $attributeCatalouge=$this->attributeCatalougeRepository->destroy($id);
            $this->nestedsetbie->Get('level ASC, order ASC');
            $this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
            $this->nestedsetbie->Action();
            DB::table('routers')->where('module_id', $id)->where('controllers', 'App\Http\Controllers\Backend\AttributeCatalougeController')->delete();
            DB::commit();
            return true;//xóa dữ liệu thành công
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
            return false;
        }

    }

    private function payload(){
        return [
            'parent_id',
            'follow',
            'publish',
            'image',
            'album',
        ];
    }
    
    private function payloadLanguage(){
        return [
            'name',
            'description', 
            'content',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'canonical'
        ];
    }

}