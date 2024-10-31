<?php
namespace App\Services;

use  App\Services\Interfaces\PostServiceInterface;
use  App\Services\BaseService;
use  App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;//tương tác với database
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
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
    protected $routerRepository;
    public function __construct( PostRepository $postRepository,RouterRepository $routerRepository ){
        $this->postRepository=$postRepository;
        $this->routerRepository=$routerRepository;
       
    }
//userRepository là dependency của class UserService vì UserService phụ thuộc userRepository

    public function paginate($request){
       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       $posts=$this->postRepository->pagination(
        $this->paginateSelect(),
        $condition,
        [['post_languages as tb2', 'tb2.post_id', '=', 'posts.id']],
        ['path'=>'post/index'],
        ['post_catalouges'],
        ['posts.id','Desc'],
        5,);
            return $posts;
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
            $post=$this->postRepository->create($payload);//$post biến đại diện cho model post
            if($post->id>0){
                $payloadLanguage=$this->formatLanguageForPost($post,$request);
                $language=$this->postRepository->createPivot($post,$payloadLanguage,'languages');
                $catalouge=$this->catalouge($request);
                $post->post_catalouges()->sync($catalouge);

                $router=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>$post->id,
                    'controllers'=>'App\Http\Controllers\Backend\PostController'
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
            $post = $this->postRepository->findById($id);
            $payload=$request->only($this->payload());
            $flag=$this->postRepository->update($id,$payload);
            if($flag==true){
                $payloadLanguage=$this->formatLanguageForPost($post,$request);
                $post->languages()->detach([$payloadLanguage['language_id'],$id]);
                $response = $this->postRepository->createPivot($post, $payloadLanguage,'languages');
                $catalouge=$this->catalouge($request);
                $post->post_catalouges()->sync($catalouge); 

                $payloadRouter=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>$post->id,
                    'controllers'=>'App\Http\Controllers\Backend\PostController'
                ];
                $condition=[
                    [ 'module_id','=',$post->id],
                    [ 'controllers','=','App\Http\Controllers\Backend\PostController']

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
          
            $this->postRepository->destroy($id);
            DB::table('post_languages')->where('post_id', $id)->delete();
            DB::table('routers')->where('module_id', $id)->where('controllers', 'App\Http\Controllers\Backend\PostController')->delete();
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
            'post_catalouge_id',
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

   private function formatLanguageForPost($post,$request){
    $payload=$request->only($this->payloadLanguage());
    $payload['canonical']=Str::slug($payload['canonical']);
    $payload['language_id']=$this->currentLanguage();
    $payload['post_id']=$post->id;
    return $payload;
   }

    public function catalouge($request){
       return array_unique(array_merge($request->input('catalouge'),[$request->post_catalouge_id]));


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