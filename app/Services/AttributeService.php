<?php
namespace App\Services;

use  App\Services\Interfaces\AttributeServiceInterface;
use  App\Services\BaseService;
use  App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository;//tương tác với database
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class AttributeService  extends BaseService implements AttributeServiceInterface
{
    protected $attributeRepository;
    protected $routerRepository;
    public function __construct( AttributeRepository $attributeRepository,RouterRepository $routerRepository ){
        $this->attributeRepository=$attributeRepository;
        $this->routerRepository=$routerRepository;
       
    }
//userRepository là dependency của class UserService vì UserService phụ thuộc userRepository

    public function paginate($request){
       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       $attributes=$this->attributeRepository->pagination(
        $this->paginateSelect(),
        $condition,
        [['attribute_languages as tb2', 'tb2.attribute_id', '=', 'attributes.id']],
        ['path'=>'attribute/index'],
        ['attribute_catalouges'],
        ['attributes.id','Desc'],
        5,);
            return $attributes;
    }

    public function paginateSelect(){
        return [
        'id',
        'publish',
        'image',
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
            $attribute=$this->attributeRepository->create($payload);//$attribute biến đại diện cho model attribute
            if($attribute->id>0){
                $payloadLanguage=$this->formatLanguageForattribute($attribute,$request);
                $language=$this->attributeRepository->createPivot($attribute,$payloadLanguage,'languages');
                $catalouge=$this->catalouge($request);
                $attribute->attribute_catalouges()->sync($catalouge);

                $router=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>$attribute->id,
                    'controllers'=>'App\Http\Controllers\Backend\AttributeController'
                ];
             
               $this->routerRepository->create($router);
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

    public function update($id,$request){
        DB::beginTransaction();
        try{
            $attribute = $this->attributeRepository->findById($id);
            $payload=$request->only($this->payload());
            $flag=$this->attributeRepository->update($id,$payload);
            if($flag==true){
                $payloadLanguage=$this->formatLanguageForattribute($attribute,$request);
                $attribute->languages()->detach([$payloadLanguage['language_id'],$id]);
                $response = $this->attributeRepository->createPivot($attribute, $payloadLanguage,'languages');
                $catalouge=$this->catalouge($request);
                $attribute->attribute_catalouges()->sync($catalouge); 

                $payloadRouter=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>$attribute->id,
                    'controllers'=>'App\Http\Controllers\Backend\AttributeController'
                ];
                $condition=[
                    [ 'module_id','=',$attribute->id],
                    [ 'controllers','=','App\Http\Controllers\Backend\AttributeController']

                ];
                $router=$this->routerRepository->findByCondition($condition);
                $this->routerRepository->update($router->id,$payloadRouter);
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
          
            $this->attributeRepository->destroy($id);
            DB::table('attribute_languages')->where('attribute_id', $id)->delete();
            DB::table('routers')->where('module_id', $id)->where('controllers', 'App\Http\Controllers\Backend\AttributeController')->delete();
            DB::commit();
            return true;
        }
        catch(\Exception $e){
            DB::rollback();
            Log::error($e->getMessage());
            return false;
        }
    }

    private function payload(){
        return [
            'attribute_catalouge_id',
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

   private function formatLanguageForAttribute($attribute,$request){
    $payload=$request->only($this->payloadLanguage());
    $payload['canonical']=Str::slug($payload['canonical']);
    $payload['language_id']=$this->currentLanguage();
    $payload['attribute_id']=$attribute->id;
    return $payload;
   }

    public function catalouge($request){
       return array_unique(array_merge($request->input('catalouge'),[$request->attribute_catalouge_id]));


    }

    public function updateStatus($attribute=[]){
        DB::beginTransaction();
        try{
            
            $payload[$attribute['field']] =(($attribute['value']==1)?2 :1 ) ;//nếu value=1 gán bằng  2 còn lại =1
            $attribute=$this->attributeRepository->update($attribute['modelId'],$payload);
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