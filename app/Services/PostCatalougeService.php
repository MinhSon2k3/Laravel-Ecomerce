<?php
namespace App\Services;

use  App\Services\Interfaces\PostCatalougeServiceInterface;
use  App\Services\BaseService;
use App\Repositories\Interfaces\PostCatalougeRepositoryInterface as PostCatalougeRepository;//tương tác với database
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class PostCatalougeService  extends BaseService implements PostCatalougeServiceInterface
{
    protected $postCatalougeRepository;
    public function __construct( PostCatalougeRepository $postCatalougeRepository){
        $this->postCatalougeRepository=$postCatalougeRepository;
    }
//userRepository là dependency của class UserService vì UserService phụ thuộc userRepository

    public function paginate($request){
       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       $postCatalouges=$this->postCatalougeRepository->pagination($this->paginateSelect(),$condition,[],['path'=>'post/catalouge/index'],[]); 
       return $postCatalouges;
    }

    public function paginateSelect(){
        return ['id','publish','image'];
    }

    public function create($request){

        DB::beginTransaction();
        try{
            //$payload lấy dữ liệu từ các input request
            $payload=$request->only($this->payload());

            $payload['user_id']=Auth::id();
           
            //lấy dữ liệu từ payload để thêm vào database bằng create() từ languageRepository
            $postCatalouge=$this->postCatalougeRepository->create($payload);//$language biến đại diện cho model Language
            if($postCatalouge->id>0){
                $payloadLanguage=$request->only($this->payloadLanguage());
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['post_catalouge_id']=$postCatalouge->id;
                dd($payloadLanguage);
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
            //$payload lấy dữ liệu từ các input request
            $payload=$request->input();
           
           
         
            //lấy dữ liệu từ payload và $id để thêm vào database bằng update() từ userCatalougeRepository
            $user=$this->postCatalougeRepository->update($id,$payload);
          
           
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
            
            $user=$this->postCatalougeRepository->destroy($id);
          
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