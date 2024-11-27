<?php
namespace App\Services;

use App\Services\Interfaces\ProductCatalougeServiceInterface;
use App\Services\BaseService;
use App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\ProductCatalougeRepositoryInterface as ProductCatalougeRepository;//tương tác với database
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class ProductCatalougeService  extends BaseService implements ProductCatalougeServiceInterface
{
    protected $productCatalougeRepository;
    protected $routerRepository;

    public function __construct( productCatalougeRepository $productCatalougeRepository,RouterRepository $routerRepository ){
        $this->productCatalougeRepository=$productCatalougeRepository;
        $this->routerRepository=$routerRepository;
        $this->nestedsetbie=new Nestedsetbie([
            'table'=>'product_catalouges',
            'foreignkey'=>'product_catalouge_id',
            'language_id'=>$this->currentLanguage()
        ]);
    }

    public function paginate($request){

       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       $productCatalouges=$this->productCatalougeRepository->pagination(
        $this->paginateSelect(),
        $condition,
        [
            ['product_catalouge_languages as tb2', 'tb2.product_catalouge_id', '=', 'product_catalouges.id']
        ],
        ['path'=>'product/catalouge/index'],
        [],
        [
            'product_catalouges.lft','Asc'
        ],
        ); 
        return $productCatalouges;
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
            $productCatalouge=$this->productCatalougeRepository->create($payload);//$productCatalouge biến đại diện cho model productCatalouge
            if($productCatalouge->id>0){
                $payloadLanguage=$request->only($this->payloadLanguage());
                $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['product_catalouge_id']=$productCatalouge->id;
                $language=$this->productCatalougeRepository->createTranslatePivot($productCatalouge,$payloadLanguage);

                $router=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>$productCatalouge->id,
                    'controllers'=>'App\Http\Controllers\Backend\ProductCatalougeController'
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
            $productCatalouge = $this->productCatalougeRepository->findById($id);
            $payload=$request->only($this->payload());
            $flag = $this->productCatalougeRepository->update($id, $payload);
            if($flag == TRUE){
                $payloadLanguage=$request->only($this->payloadLanguage());
                $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['product_catalouge_id']=$id;
                $productCatalouge->languages()->detach([$payloadLanguage['language_id'],$id]);
                $response = $this->productCatalougeRepository->createTranslatePivot($productCatalouge, $payloadLanguage);

                $payloadRouter=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>$productCatalouge->id,
                    'controllers'=>'App\Http\Controllers\Backend\ProductCatalougeController'
                ];
                $condition=[
                    [ 'module_id','=',$productCatalouge->id],
                    [ 'controllers','=','App\Http\Controllers\Backend\ProductCatalougeController']

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
            
            $productCatalouge=$this->productCatalougeRepository->destroy($id);
            $this->nestedsetbie->Get('level ASC, order ASC');
            $this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
            $this->nestedsetbie->Action();
            DB::table('routers')->where('module_id', $id)->where('controllers', 'App\Http\Controllers\Backend\ProductCatalougeController')->delete();
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

    
    public function updateStatus($product=[]){
        DB::beginTransaction();
        try{
            
            $payload[$product['field']] =(($product['value']==1)?2 :1 ) ;//nếu value=1 gán bằng  2 còn lại =1
            $product=$this->productCatalougeRepository->update($product['modelId'],$payload);
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