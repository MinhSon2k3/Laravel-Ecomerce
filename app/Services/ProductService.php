<?php
namespace App\Services;

use  App\Services\Interfaces\ProductServiceInterface;
use  App\Services\BaseService;
use  App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;//tương tác với database
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Repositories\Interfaces\ProductVariantLanguageRepositoryInterface as ProductVariantLanguageRepository;
use App\Repositories\Interfaces\ProductVariantAttributeRepositoryInterface as ProductVariantAttributeRepository;
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
    protected $productVariantLanguageRepository;
    protected $productVariantAttributeRepository;
    public function __construct( 
        ProductRepository $productRepository,
        RouterRepository $routerRepository,
        ProductVariantLanguageRepository $productVariantLanguageRepository,
        ProductVariantAttributeRepository $productVariantAttributeRepository
         ){
        $this->productRepository=$productRepository;
        $this->routerRepository=$routerRepository;
        $this->productVariantLanguageRepository=$productVariantLanguageRepository;
        $this->productVariantAttributeRepository=$productVariantAttributeRepository;
        $this->controllerName = 'ProductController';
       
    }
//productRepository là dependency của class ProductService vì ProductService phụ thuộc productRepository

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
    function convert_price($price)
    {
        // Loại bỏ dấu chấm và trả về số
        return str_replace('.', '', $price);
    }
    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            $product = $this->createProduct($request);
            if($product->id > 0){
                $this->updateLanguageForProduct($product, $request, $languageId);
                $this->updateCatalougeForProduct($product, $request);
                $this->createRouter($product, $request, $this->controllerName, $languageId);
                $this->createVariant($product,$request,$languageId);
            }
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    public function update($id, $request, $languageId){
        DB::beginTransaction();
        try{
            $product = $this->productRepository->findById($id);
            if($this->uploadProduct($product, $request)){
                $this->updateLanguageForProduct($product, $request, $languageId);
                $this->updateCatalougeForProduct($product, $request);
                $this->updateRouter(
                    $product, $request, $this->controllerName, $languageId
                );
                $product->product_variants()->each(function($variant) {
                    // Xóa tất cả dữ liệu trong bảng trung gian trước
                    $variant->languages()->detach();
                    $variant->attributes()->detach();
                
                    // Sau đó xóa variant
                    $variant->delete();
                });
                
                $this->createVariant($product,$request,$languageId);

            }
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
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
            'attributeCatalouge',
            'attribute',
            'variant'
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
            'canonical',
        ];
    }

    private function createProduct($request){
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($request);
        $payload['price'] = $this->convert_price($payload['price']);
        $payload['attributeCatalouge']=$this->formatJson($request,'attributeCatalouge');
        $payload['attribute']=$this->formatJson($request,'attribute');
        $payload['variant']=$this->formatJson($request,'variant');
        $product = $this->productRepository->create($payload);
        return $product;
    }

    private function uploadProduct($product, $request){
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $payload['price'] = $this->convert_price($payload['price']);
        return $this->productRepository->update($product->id, $payload);
    }

   
    private function updateLanguageForProduct($product, $request, $languageId){
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $product->id, $languageId);
        $product->languages()->detach([$languageId, $product->id]);
        return $this->productRepository->createPivot($product, $payload, 'languages');
    }

    private function updateCatalougeForProduct($product, $request){
        $product->product_catalouges()->sync($this->catalouge($request));
    }

    private function formatLanguagePayload($payload, $productId, $languageId){
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] =  $languageId;
        $payload['product_id'] = $productId;
        return $payload;
    }


    private function catalouge($request){
        if($request->input('catalouge') != null){
            return array_unique(array_merge($request->input('catalouge'), [$request->product_catalouge_id]));
        }
        return [$request->product_catalouge_id];
    }

    public function createVariant($product,$request,$languageId){
        $payload=$request->only('variant','productVariant','attribute');
       
        
        $variant=$this->createVariantArray($payload);
        $variants=$product->product_variants()->createMany($variant);
        $variantsId=$variants->pluck('id');
        $productVariantLanguage=[];
        $variantAttribute=[];
        $attributeCombibes=$this->combineAttribute(array_values($payload['attribute']));
        if(count($variantsId)){
            foreach($variantsId as $key => $val){
                $productVariantLanguage[]=[
                    'product_variant_id'=>$val,
                    'language_id'=>$languageId,
                    'name'=>$payload['productVariant']['name'][$key]
                ];

                if(count($attributeCombibes)){
                    foreach($attributeCombibes[$key] as $attributeId){
                        $variantAttribute[]=[
                            'product_variant_id'=>$val,
                            'attribute_id'=>$attributeId
                        ];
                    }
                }
            }
        }
      
    
        $variantLanguage=$this->productVariantLanguageRepository->createBatch($productVariantLanguage);
        $variantAttribute=$this->productVariantAttributeRepository->createBatch($variantAttribute);
       
    }

    private function combineAttribute($attributes=[],$index =0){
        if($index===count($attributes)) return [[]];
        $subComebines=$this->combineAttribute($attributes,$index+1);
        $comebines=[];
        foreach($attributes[$index] as $key => $val){
            foreach($subComebines as $keySub => $valSub){
                $comebines[]= array_merge([$val],$valSub);
            }
        }
        return $comebines;
    }

    private function createVariantArray(array $payload = []): array
    {
        $variant = [];
        if(isset($payload['variant']['sku']) && count($payload['variant']['sku'])) {
            foreach($payload['variant']['sku'] as $key => $val) {
                $variant[] = [
                    'code' => ($payload['productVariant']['id'][$key]) ?? '',
                    'quantity' => ($payload['variant']['quantity'][$key]) ?? '',
                    'sku' => $val,
                    'price' => ($payload['variant']['price'][$key]) ? $this->convert_price($payload['variant']['price'][$key]) : '',
                    'barcode' => ($payload['variant']['barcode'][$key]) ?? '',
                    'file_name' => ($payload['variant']['file_name'][$key]) ?? '',
                    'file_url' => ($payload['variant']['file_url'][$key]) ?? '',
                    'album' => ($payload['variant']['album'][$key]) ?? '',
                    'user_id' => Auth::id(),
                ];
            }
        }
        return $variant;
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