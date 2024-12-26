<?php
namespace App\Services;

use  App\Services\Interfaces\ProductServiceInterface;
use  App\Services\BaseService;
use  App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;//tương tác với database
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class ProductService  extends BaseService implements productServiceInterface
{
    protected $productRepository;
    protected $routerRepository;
    public function __construct( ProductRepository $productRepository,RouterRepository $routerRepository ){
        $this->productRepository=$productRepository;
        $this->routerRepository=$routerRepository;
       
    }
//userRepository là dependency của class UserService vì UserService phụ thuộc userRepository

    public function paginate($request){
       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       $products=$this->productRepository->pagination(
        $this->paginateSelect(),
        $condition,
        [['product_languages as tb2', 'tb2.product_id', '=', 'products.id']],
        ['path'=>'product/index'],
        ['product_catalouges'],
        ['products.id','Desc'],
        5,);
            return $products;
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
            $product=$this->productRepository->create($payload);//$product biến đại diện cho model product
            if($product->id>0){
                $payloadLanguage=$this->formatLanguageForproduct($product,$request);
                $language=$this->productRepository->createPivot($product,$payloadLanguage,'languages');
                $catalouge=$this->catalouge($request);
                $product->product_catalouges()->sync($catalouge);

                $router=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>$product->id,
                    'controllers'=>'App\Http\Controllers\Backend\ProductController'
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
            $product = $this->productRepository->findById($id);
            $payload=$request->only($this->payload());
            $flag=$this->productRepository->update($id,$payload);
            if($flag==true){
                $payloadLanguage=$this->formatLanguageForproduct($product,$request);
                $product->languages()->detach([$payloadLanguage['language_id'],$id]);
                $response = $this->productRepository->createPivot($product, $payloadLanguage,'languages');
                $catalouge=$this->catalouge($request);
                $product->product_catalouges()->sync($catalouge); 

                $payloadRouter=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>$product->id,
                    'controllers'=>'App\Http\Controllers\Backend\ProductController'
                ];
                $condition=[
                    [ 'module_id','=',$product->id],
                    [ 'controllers','=','App\Http\Controllers\Backend\ProductController']

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
          
            $this->productRepository->destroy($id);
            DB::table('product_languages')->where('product_id', $id)->delete();
            DB::table('routers')->where('module_id', $id)->where('controllers', 'App\Http\Controllers\Backend\ProductController')->delete();
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
            'product_catalouge_id',
            'follow',
            'publish',
            'image',
            'album',
            'code',
            'price',
            'made_in',
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

   private function formatLanguageForProduct($product,$request){
    $payload=$request->only($this->payloadLanguage());
    $payload['canonical']=Str::slug($payload['canonical']);
    $payload['language_id']=$this->currentLanguage();
    $payload['product_id']=$product->id;
    return $payload;
   }

    public function catalouge($request){
       return array_unique(array_merge($request->input('catalouge'),[$request->product_catalouge_id]));


    }

    public function updateStatus($product=[]){
        DB::beginTransaction();
        try{
            
            $payload[$product['field']] =(($product['value']==1)?2 :1 ) ;//nếu value=1 gán bằng  2 còn lại =1
            $product=$this->productRepository->update($product['modelId'],$payload);
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