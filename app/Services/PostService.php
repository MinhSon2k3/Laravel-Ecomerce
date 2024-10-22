<?php
namespace App\Services;

use  App\Services\Interfaces\PostServiceInterface;
use  App\Services\BaseService;
use  App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;//tương tác với database
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class PostService  extends BaseService implements PostServiceInterface
{
    protected $postRepository;
    public function __construct( PostRepository $postRepository){
        $this->postRepository=$postRepository;
        $this->nestedsetbie=new Nestedsetbie([
            'table'=>'posts',
            'foreignkey'=>'post_id',
            'language_id'=>$this->currentLanguage()
        ]);
    }
//userRepository là dependency của class UserService vì UserService phụ thuộc userRepository

    public function paginate($request){
       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       $posts=$this->postRepository->pagination(
        $this->paginateSelect(),
        $condition,
        [
            ['post_languages as tb2', 'tb2.post_id', '=', 'posts.id']

        ],
        ['path'=>'post//index'],
        [],
        [
            'posts.id','Asc'
        ],
    ); 
     
       return $posts;
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
            $post=$this->postRepository->create($payload);//$post biến đại diện cho model post
            if($post->id>0){
                $payloadLanguage=$request->only($this->payloadLanguage());
             
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['post__id']=$post->id;
              
                $language=$this->postRepository->createTranslatePivot($post,$payloadLanguage);
             
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
            $post = $this->postRepository->findById($id);
            $payload=$request->only($this->payload());
            $flag = $this->postRepository->update($id, $payload);
            if($flag == TRUE){
                $payloadLanguage=$request->only($this->payloadLanguage());
                $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['post__id']=$id;
                $post->languages()->detach([$payloadLanguage['language_id'],$id]);
                $response = $this->postRepository->createTranslatePivot($post, $payloadLanguage);
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
            
            $post=$this->postRepository->destroy($id);
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
            $post=$this->postRepository->update($post['modelId'],$payload);
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