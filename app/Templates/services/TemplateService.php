<?php
namespace App\Services;

use  App\Services\Interfaces\{Module}ServiceInterface;
use  App\Services\BaseService;
use  App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\{Module}RepositoryInterface as {Module}Repository;//tương tác với database
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class {Module}Service  extends BaseService implements {module}ServiceInterface
{
    protected ${module}Repository;
    protected $routerRepository;
    public function __construct( {Module}Repository ${module}Repository,RouterRepository $routerRepository ){
        $this->{module}Repository=${module}Repository;
        $this->routerRepository=$routerRepository;
       
    }
//userRepository là dependency của class UserService vì UserService phụ thuộc userRepository

    public function paginate($request){
       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       ${module}s=$this->{module}Repository->pagination(
        $this->paginateSelect(),
        $condition,
        [['{module}_languages as tb2', 'tb2.{module}_id', '=', '{module}s.id']],
        ['path'=>'{module}/index'],
        ['{module}_catalouges'],
        ['{module}s.id','Desc'],
        5,);
            return ${module}s;
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
            ${module}=$this->{module}Repository->create($payload);//${module} biến đại diện cho model {module}
            if(${module}->id>0){
                $payloadLanguage=$this->formatLanguageFor{module}(${module},$request);
                $language=$this->{module}Repository->createPivot(${module},$payloadLanguage,'languages');
                $catalouge=$this->catalouge($request);
                ${module}->{module}_catalouges()->sync($catalouge);

                $router=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>${module}->id,
                    'controllers'=>'App\Http\Controllers\Backend\{Module}Controller'
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
            ${module} = $this->{module}Repository->findById($id);
            $payload=$request->only($this->payload());
            $flag=$this->{module}Repository->update($id,$payload);
            if($flag==true){
                $payloadLanguage=$this->formatLanguageFor{module}(${module},$request);
                ${module}->languages()->detach([$payloadLanguage['language_id'],$id]);
                $response = $this->{module}Repository->createPivot(${module}, $payloadLanguage,'languages');
                $catalouge=$this->catalouge($request);
                ${module}->{module}_catalouges()->sync($catalouge); 

                $payloadRouter=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>${module}->id,
                    'controllers'=>'App\Http\Controllers\Backend\{Module}Controller'
                ];
                $condition=[
                    [ 'module_id','=',${module}->id],
                    [ 'controllers','=','App\Http\Controllers\Backend\{Module}Controller']

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
          
            $this->{module}Repository->destroy($id);
            DB::table('{module}_languages')->where('{module}_id', $id)->delete();
            DB::table('routers')->where('module_id', $id)->where('controllers', 'App\Http\Controllers\Backend\{Module}Controller')->delete();
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
            '{module}_catalouge_id',
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

   private function formatLanguageFor{Module}(${module},$request){
    $payload=$request->only($this->payloadLanguage());
    $payload['canonical']=Str::slug($payload['canonical']);
    $payload['language_id']=$this->currentLanguage();
    $payload['{module}_id']=${module}->id;
    return $payload;
   }

    public function catalouge($request){
       return array_unique(array_merge($request->input('catalouge'),[$request->{module}_catalouge_id]));


    }

    public function updateStatus(${module}=[]){
        DB::beginTransaction();
        try{
            
            $payload[${module}['field']] =((${module}['value']==1)?2 :1 ) ;//nếu value=1 gán bằng  2 còn lại =1
            ${module}=$this->{module}Repository->update(${module}['modelId'],$payload);
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