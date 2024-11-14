<?php
namespace App\Services;

use App\Services\Interfaces\{Module}ServiceInterface;
use App\Services\BaseService;
use App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\{Module}RepositoryInterface as {Module}Repository;//tương tác với database
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class {Module}Service  extends BaseService implements {Module}ServiceInterface
{
    protected ${moduleTemplate}Repository;
    protected $routerRepository;

    public function __construct( {moduleTemplate}Repository ${moduleTemplate}Repository,RouterRepository $routerRepository ){
        $this->{moduleTemplate}Repository=${moduleTemplate}Repository;
        $this->routerRepository=$routerRepository;
        $this->nestedsetbie=new Nestedsetbie([
            'table'=>'{tableName}s',
            'foreignkey'=>'{foreignkey}',
            'language_id'=>$this->currentLanguage()
        ]);
    }

    public function paginate($request){

       $condition['keyword'] = addslashes($request->input('keyword'));
       $condition['publish'] = $request->integer('publish');
       ${moduleTemplate}s=$this->{moduleTemplate}Repository->pagination(
        $this->paginateSelect(),
        $condition,
        [
            ['{tableName}_languages as tb2', 'tb2.{foreignkey}', '=', '{tableName}s.id']
        ],
        ['path'=>'{modulePath}/index'],
        [],
        [
            '{tableName}s.lft','Asc'
        ],
        ); 
        return ${moduleTemplate}s;
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
            ${moduleTemplate}=$this->{moduleTemplate}Repository->create($payload);//${moduleTemplate} biến đại diện cho model {moduleTemplate}
            if(${moduleTemplate}->id>0){
                $payloadLanguage=$request->only($this->payloadLanguage());
                $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['{foreignkey}']=${moduleTemplate}->id;
                $language=$this->{moduleTemplate}Repository->createTranslatePivot(${moduleTemplate},$payloadLanguage);

                $router=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>${moduleTemplate}->id,
                    'controllers'=>'App\Http\Controllers\Backend\{Module}Controller'
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
            ${moduleTemplate} = $this->{moduleTemplate}Repository->findById($id);
            $payload=$request->only($this->payload());
            $flag = $this->{moduleTemplate}Repository->update($id, $payload);
            if($flag == TRUE){
                $payloadLanguage=$request->only($this->payloadLanguage());
                $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['{foreignkey}']=$id;
                ${moduleTemplate}->languages()->detach([$payloadLanguage['language_id'],$id]);
                $response = $this->{moduleTemplate}Repository->createTranslatePivot(${moduleTemplate}, $payloadLanguage);

                $payloadRouter=[
                    'canonical'=>$payloadLanguage['canonical'],
                    'module_id'=>${moduleTemplate}->id,
                    'controllers'=>'App\Http\Controllers\Backend\{Module}Controller'
                ];
                $condition=[
                    [ 'module_id','=',${moduleTemplate}->id],
                    [ 'controllers','=','App\Http\Controllers\Backend\{Module}Controller']

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
            
            ${moduleTemplate}=$this->{moduleTemplate}Repository->destroy($id);
            $this->nestedsetbie->Get('level ASC, order ASC');
            $this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
            $this->nestedsetbie->Action();
            DB::table('routers')->where('module_id', $id)->where('controllers', 'App\Http\Controllers\Backend\{Module}Controller')->delete();
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