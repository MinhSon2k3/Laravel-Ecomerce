<?php
namespace App\Services;

use  App\Services\Interfaces\PostCatalougeServiceInterface;
use  App\Services\BaseService;
use  App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\PostCatalougeRepositoryInterface as PostCatalougeRepository;//tương tác với database
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class PostCatalougeService  extends BaseService implements PostCatalougeServiceInterface
{
    protected $postCatalougeRepository;
    public function __construct( PostCatalougeRepository $postCatalougeRepository){
        $this->postCatalougeRepository=$postCatalougeRepository;
        $this->nestedsetbie=new Nestedsetbie([
            'table'=>'post_catalouges',
            'foreignkey'=>'post_catalouge_id',
            'language_id'=>$this->currentLanguage()
        ]);
    }
//userRepository là dependency của class UserService vì UserService phụ thuộc userRepository

    public function paginate($request){
       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       $postCatalouges=$this->postCatalougeRepository->pagination(
        $this->paginateSelect(),
        $condition,
        [
            ['post_catalouge_languages as tb2', 'tb2.post_catalouge_id', '=', 'post_catalouges.id']

        ],
        ['path'=>'post/catalouge/index'],
        [],
        [
            'post_catalouges.lft','Asc'
        ],
    ); 
     
       return $postCatalouges;
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
            $postCatalouge=$this->postCatalougeRepository->create($payload);//$postCatalouge biến đại diện cho model postCatalouge
            if($postCatalouge->id>0){
                $payloadLanguage=$request->only($this->payloadLanguage());
             
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['post_catalouge_id']=$postCatalouge->id;
              
                $language=$this->postCatalougeRepository->createTranslatePivot($postCatalouge,$payloadLanguage);
             
             
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
            $postCatalouge = $this->postCatalougeRepository->findById($id);
            $payload=$request->only($this->payload());
            $flag = $this->postCatalougeRepository->update($id, $payload);
            if($flag == TRUE){
                $payloadLanguage=$request->only($this->payloadLanguage());
                $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['post_catalouge_id']=$id;
                $postCatalouge->languages()->detach([$payloadLanguage['language_id'],$id]);
                $response = $this->postCatalougeRepository->createTranslatePivot($postCatalouge, $payloadLanguage);
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
            
            $postCatalouge=$this->postCatalougeRepository->destroy($id);
            $this->nestedsetbie->Get('level ASC, order ASC');
            $this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
            $this->nestedsetbie->Action();
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

    public function updateStatus($post=[]){
        DB::beginTransaction();
        try{
            
            $payload[$post['field']] =(($post['value']==1)?2 :1 ) ;//nếu value=1 gán bằng  2 còn lại =1
            $postCatalouge=$this->postCatalougeRepository->update($post['modelId'],$payload);
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