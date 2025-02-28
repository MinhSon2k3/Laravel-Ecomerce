<?php

namespace App\Services;

use App\Services\Interfaces\GenerateServiceInterface;
use App\Repositories\Interfaces\GenerateRepositoryInterface as GenerateRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;


/**
 * Class GenerateService
 * @package App\Services
 */
class GenerateService implements GenerateServiceInterface
{
    protected $generateRepository;
    

    public function __construct(
        GenerateRepository $generateRepository,
    ){
        $this->generateRepository = $generateRepository;
    }

    public function paginate($request){
        $condition['keyword'] = addslashes($request->input('keyword'));
        $generates=$this->generateRepository->pagination(
         $this->paginateSelect(),
         $condition,
         [],
         ['path'=>'generate/index'],
         [],
         [],
          3); 
        return $generates;
    }
    
    private function paginateSelect(){
        return [
            'id', 
            'name', 
            'schema',
        ];
    }

    public function create($request){
         DB::beginTransaction();
        try{
            $database=$this->makeDatabase($request);
            $controller=$this->makeController($request);
            $model=$this->makeModel($request);
            $repository=$this->makeRepository($request);
            $service=$this->makeService($request);
            $provider=$this->makeProvider($request);
            $requests=$this->makeRequest($request);
            if($request->input('module_type')=='catalouge'){
                $rule=$this->makeRule($request);
            }
            $view=$this->makeView($request);
            $router=$this->makeRoute($request);
            // $payload=$request->only($this->payload());
            // $payload['user_id'] = Auth::id();
         
            // $generate = $this->generateRepository->create($payload);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage().'-'.$e->getLine();die();
            return false;
        }
    }

    private function makeDatabase($request){
        try{
            $payload = $request->only('schema', 'name','module_type');
            $module = $this->convertModuleNameToTableName($payload['name']); //produc
            $moduleExtract = explode('_', $module);
            $this->makeMainTable($request, $module, $payload);
            if($payload['module_type'] !== 'difference'){
                $this->makeLanguageTable($request, $module);
                if(count($moduleExtract) == 1){
                    $this->makeRelationTable($request, $module);
                }
            }
            ARTISAN::call('migrate');
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            echo $e->getMessage().'-'.$e->getLine(); die();
            return false;
        }  
    }
    //makeDatabase
    private function makeRelationTable($request, $module){
        $moduleExtract = explode('_', $module);
        $tableName = $module.'_catalouge_'.$moduleExtract[0]; 
        $schema = $this->relationSchema($tableName, $module);
        $migrationRelationFile = $this->createMigrationFile($schema, $tableName);
        $migrationRelationFileName = date('Y_m_d_His', time() + 10).'_create_'.$tableName.'_table.php';
        $migrationRelationPath = database_path('migrations/'.$migrationRelationFileName);
        FILE::put($migrationRelationPath, $migrationRelationFile);
    }
    //makeDatabase
    private function makeLanguageTable($request, $module){
        $foreignKey = $module.'_id';
        $pivotTableName =  $module.'_language';
        $pivotSchema = $this->pivotSchema($module);
        $dropPivotTable = $module.'_language';

        $migrationPivot = $this->createMigrationFile($pivotSchema, $dropPivotTable);

        $migrationPivotFileName = date('Y_m_d_His', time() + 10).'_create_'.$pivotTableName.'_table.php';
        $migrationPivotPath = database_path('migrations/'.$migrationPivotFileName);
        FILE::put($migrationPivotPath, $migrationPivot);
    }
    //makeDatabase
    private function makeMainTable($request, $module, $payload){
        $moduleExtract = explode('_', $module); //product
        $tableName = $module.'s';
        $migrationFileName = date('Y_m_d_His').'_create_'.$tableName.'_table.php';
        $migrationPath = database_path('migrations/'.$migrationFileName);
        $migrationTemplate = $this->createMigrationFile($payload['schema'], $tableName);
        FILE::put($migrationPath, $migrationTemplate);
    }
    //makeDatabase
    private function relationSchema($tableName = '', $module = ''){
        $schema = <<<SCHEMA
        Schema::create('{$tableName}', function (Blueprint \$table) {
            \$table->unsignedBigInteger('{$module}_catalouge_id');
            \$table->unsignedBigInteger('{$module}_id');
            \$table->foreign('{$module}_catalouge_id')->references('id')->on('{$module}_catalouges')->onDelete('cascade');
            \$table->foreign('{$module}_id')->references('id')->on('{$module}s')->onDelete('cascade');
        });
        SCHEMA;
                return $schema;
            }

            private function pivotSchema($module){
                        $pivotSchema = <<<SCHEMA
                Schema::create('{$module}_languages', function (Blueprint \$table) {
                    \$table->unsignedBigInteger('{$module}_id');
                    \$table->unsignedBigInteger('language_id');
                    \$table->foreign('{$module}_id')->references('id')->on('{$module}s')->onDelete('cascade');
                    \$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
                    \$table->string('name');
                    \$table->text('description')->nullable();
                    \$table->longText('content')->nullable();
                    \$table->string('meta_title')->nullable();
                    \$table->string('meta_keyword')->nullable();
                    \$table->text('meta_description')->nullable();
                    \$table->string('canonical')->nullable();
                    \$table->timestamps();
                });       
                SCHEMA;
                return $pivotSchema;
    }
    //makeDatabase
    private function createMigrationFile($schema, $dropTable = ''){
       
        $migrationTemplate = <<<MIGRATION
        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration 
        {
            /**
             * Run the migrations.
             */
            public function up()
            {
                {$schema}
            }

            /**
             * Reverse the migrations.
             */
            public function down()
            {
                Schema::dropIfExists('{$dropTable}');
            }
        };
        MIGRATION;  
        return $migrationTemplate;
    }
     //convertModuleName
    private function convertModuleNameToTableName($name){
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        return $temp;
    }
    
    public function makeController($request){
        try{
            $payload=$request->only('name','module_type');
            switch($payload['module_type']){
                case 'catalouge':
                    $this->createTemplateCatalougeController($payload['name'],'TemplateCatalougeController');
                    break;
                case 'detail':
                    $this->createTemplateController($payload['name'],'TemplateController');
                    break; 
                default:
                    $this->createSingleController($payload['name'],'SingleController');
                    break;
                    
            }
            return true;
        }catch(\Exception $e ){
          
            echo $e->getMessage().'-'.$e->getLine();die();
            return false;
        }
    }
    //makeController
    private function createTemplateCatalougeController($name,$controllerFile){
        $controllerName=$name.'Controller';
        $templateControllerPath = base_path('app/Templates/controllers/'.$controllerFile.'.php');
        $controllerContent = file_get_contents($templateControllerPath);
        $replace = [
            'ModuleTemplate' => $name,
            'moduleTemplate' => lcfirst($name),
            'foreignkey'=>$this->convertModuleNameToTableName($name.'_id'),
            'moduleView'=>str_replace('_','.',$this->convertModuleNameToTableName($name)),
            'tableName'=>$this->convertModuleNameToTableName($name.'s')
        ];
        $controllerContent=str_replace('{ModuleTemplate}',$replace['ModuleTemplate'],$controllerContent);
        $controllerContent=str_replace('{moduleTemplate}',$replace['moduleTemplate'],$controllerContent);
        $controllerContent=str_replace('{foreignkey}',$replace['foreignkey'],$controllerContent);
        $controllerContent=str_replace('{moduleView}',$replace['moduleView'],$controllerContent);
        $controllerContent=str_replace('{tableName}',$replace['tableName'],$controllerContent);
        
        $controllerPath=base_path('app/Http/Controllers/Backend/'.$controllerName.'.php');
        FILE::put($controllerPath,$controllerContent);
        
    }
    //makeController
    private function createTemplateController($name,$controllerFile){
        $controllerName=$name.'Controller';
        $templateControllerPath = base_path('app/Templates/controllers/'.$controllerFile.'.php');
        $controllerContent = file_get_contents($templateControllerPath);
        $replace = [
            'ModuleTemplate' => $name,
            'moduleTemplate' => lcfirst($name),
            'moduleView'=>str_replace('_','.',$this->convertModuleNameToTableName($name).'.'.$this->convertModuleNameToTableName($name)),
            'foreignkey'=>$this->convertModuleNameToTableName($name.'catalouge_id'),
            'tableName'=>$this->convertModuleNameToTableName($name.'_catalouges')
        ];
        $controllerContent=str_replace('{ModuleTemplate}',$replace['ModuleTemplate'],$controllerContent);
        $controllerContent=str_replace('{moduleTemplate}',$replace['moduleTemplate'],$controllerContent);
        $controllerContent=str_replace('{moduleView}',$replace['moduleView'],$controllerContent);
        $controllerContent=str_replace('{foreignkey}',$replace['foreignkey'],$controllerContent);
        $controllerContent=str_replace('{tableName}',$replace['tableName'],$controllerContent);
        $controllerPath=base_path('app/Http/Controllers/Backend/'.$controllerName.'.php');
        FILE::put($controllerPath,$controllerContent);
    }
    //makeController
    private function createSingleController($name,$controllerName){
        return 0;
    }

    public function makeModel($request){
        try{
            if($request->input('module_type')=='catalouge'){
                $this->createTemplateCatalougeModel($request);
            }
            if($request->input('module_type')=='detail'){
                $this->createTemplateModel($request);
            }
            return true;
        }catch(\Exception $e ){
          
            echo $e->getMessage().'-'.$e->getLine();die();
            return false;
        }
    }
    //makeModel
    private function createTemplateCatalougeModel($request){
            $modelName=$request->input('name');
            $templateModelPath = base_path('app/Templates/models/TemplateCatalougeModel.php');
            $modelContent = file_get_contents($templateModelPath);
            $module=$this->convertModuleNameToTableName($modelName);
            $extractModule=explode('_',$module);
            $replace = [
                'ModuleTemplate' => $modelName,
                'tableName'=>$module.'s',
                'relation' => $extractModule[0],
                'relationTable' => ucfirst($extractModule[0]),
                'relationPivot'=>$module.'_'.$extractModule[0],
                'foreignkey'=>$module.'_id',
                'pivotTable'=>$module.'_languages',
                'pivotModel'=>$modelName.'Language'
            ];
            $modelContent=str_replace('{ModuleTemplate}',$replace['ModuleTemplate'],$modelContent);
            $modelContent=str_replace('{tableName}',$replace['tableName'],$modelContent);
            $modelContent=str_replace('{relation}',$replace['relation'],$modelContent);
            $modelContent=str_replace('{relationTable}',$replace['relationTable'],$modelContent);
            $modelContent=str_replace('{relationPivot}',$replace['relationPivot'],$modelContent);
            $modelContent=str_replace('{foreignkey}',$replace['foreignkey'],$modelContent);
            $modelContent=str_replace('{pivotTable}',$replace['pivotTable'],$modelContent);
            $modelContent=str_replace('{pivotModel}',$replace['pivotModel'],$modelContent);
           
            $modelPath=base_path('app/Models/'.$modelName.'.php');
            FILE::put($modelPath,$modelContent);
    }
    //makeModel
    private function createTemplateModel($request){
        $modelName=$request->input('name');
        $templateModelPath = base_path('app/Templates/models/TemplateModel.php');
        $modelContent = file_get_contents($templateModelPath);
        $module=$this->convertModuleNameToTableName($modelName);
        $extractModule=explode('_',$module);
        $replace = [
            'ModuleTemplate' => $modelName,
            'tableName'=>$this->convertModuleNameToTableName($modelName.'s'),
            'relation' => $extractModule[0].'_catalouge',
            'relationTable' => ucfirst($extractModule[0]).'Catalouge',
            'relationPivot'=>$module.'_catalouge_'.$module,
            'foreignkey'=>$this->convertModuleNameToTableName($modelName.'_id'),
            'pivotTable'=>$module.'_languages',
        ];
        $modelContent=str_replace('{ModuleTemplate}',$replace['ModuleTemplate'],$modelContent);
        $modelContent=str_replace('{tableName}',$replace['tableName'],$modelContent);
        $modelContent=str_replace('{relation}',$replace['relation'],$modelContent);
        $modelContent=str_replace('{relationTable}',$replace['relationTable'],$modelContent);
        $modelContent=str_replace('{relationPivot}',$replace['relationPivot'],$modelContent);
        $modelContent=str_replace('{foreignkey}',$replace['foreignkey'],$modelContent);
        $modelContent=str_replace('{pivotTable}',$replace['pivotTable'],$modelContent);       
        $modelPath=base_path('app/Models/'.$modelName.'.php');
        FILE::put($modelPath,$modelContent);
    }

    private function makeRepository($request){
        $name = $request->input('name');
        $module = explode('_', $this->convertModuleNameToTableName($name));
        $repositoryPath = (count($module) == 1) ? base_path('app/Templates/repositories/TemplateRepository.php') : base_path('app/Templates/repositories/TemplateCatalougeRepository.php');
        $path = [
            'Interfaces' => base_path('app/Templates/repositories/TemplateRepositoryInterface.php'),
            'Respositories' => $repositoryPath
        ];
        $replacement = [
            '$class' => ucfirst(current($module)),
            'module' => lcfirst(current($module)),
            '$extend' => (count($module) == 2) ? 'Catalouge' : '',
        ];

        foreach($path as $key => $val){
            $content = file_get_contents($val);
            $newContent = $this->replaceContent($content, $replacement);
            $contentPath = ($key == 'Interfaces') ? base_path('app/Repositories/Interfaces/'.$name.'RepositoryInterface.php') : base_path('app/Repositories/'.$name.'Repository.php');
            if(!FILE::exists($contentPath)){
                FILE::put($contentPath, $newContent);
            }
        }
    }

    private function replaceContent($content, $replacement){
        $newContent = $content;
        foreach($replacement as $key => $val){
            $newContent = str_replace('{'.$key.'}', $val, $newContent);
        }
        return $newContent;
    }
    
    public function makeService($request){
        try{
            $payload=$request->only('name','module_type');
            switch($payload['module_type']){
                case 'catalouge':
                    $this->createTemplateCatalougeService($request);
                    break;
                case 'detail':
                    $this->createTemplateService($request);
                    break; 
                default:
                    return 0;
                    break;
                    
            }
            return true;
        }catch(\Exception $e ){
          
            echo $e->getMessage().'-'.$e->getLine();die();
            return false;
        }
    }
    //makeService
    public function createTemplateCatalougeService($request){
        $name = $request->input('name');
        $option = [
            'serviceName' => $name . 'Service',
            'serviceInterfaceName' => $name . 'ServiceInterface',
        ];
        $templateServiceInterfacePath = base_path('app/Templates/services/TemplateServiceInterface.php');
        $serviceInterfaceContent = file_get_contents($templateServiceInterfacePath);
     
        $templateCatalougeServicePath = base_path('app/Templates/services/TemplateCatalougeService.php');
        $serviceContent = file_get_contents($templateCatalougeServicePath);
        $replace = [
            'Module' => $name,
            'tableName' =>$this->convertModuleNameToTableName($name),
            'modulePath'=>str_replace('_','/',$this->convertModuleNameToTableName($name)),
            'moduleTemplate'=> lcfirst($name),
            'foreignkey'=>$this->convertModuleNameToTableName($name.'_id')
        ];
        $serviceInterfaceContent = str_replace('{Module}', $replace['Module'], $serviceInterfaceContent);
        $serviceInterfacePath = base_path('app/Services/Interfaces/' . $option['serviceInterfaceName'].'.php');
        FILE::put($serviceInterfacePath,$serviceInterfaceContent);
        
        $serviceContent = str_replace('{Module}', $replace['Module'], $serviceContent);
        $serviceContent = str_replace('{tableName}', $replace['tableName'], $serviceContent);
        $serviceContent = str_replace('{modulePath}', $replace['modulePath'], $serviceContent);
        $serviceContent = str_replace('{moduleTemplate}', $replace['moduleTemplate'], $serviceContent);
        $serviceContent = str_replace('{foreignkey}', $replace['foreignkey'], $serviceContent);
        $servicePath = base_path('app/Services/' . $option['serviceName'].'.php');
        FILE::put($servicePath,$serviceContent);
       
    }
     //makeService
    private function createTemplateService($request){
        $name = $request->input('name');
        $option = [
            'serviceName' => $name . 'Service',
            'serviceInterfaceName' => $name . 'ServiceInterface',
        ];
        $templateServiceInterfacePath = base_path('app/Templates/services/TemplateServiceInterface.php');
        $serviceInterfaceContent = file_get_contents($templateServiceInterfacePath);
     
        $templateServicePath = base_path('app/Templates/services/TemplateService.php');
        $serviceContent = file_get_contents($templateServicePath);
        $replace = [
            'Module' => $name,
            'module'=>lcfirst($name),
        ];
      
        $serviceInterfaceContent = str_replace('{Module}', $replace['Module'], $serviceInterfaceContent);
        $serviceInterfacePath = base_path('app/Services/Interfaces/' . $option['serviceInterfaceName'].'.php');
        FILE::put($serviceInterfacePath,$serviceInterfaceContent);
        
        $serviceContent = str_replace('{Module}', $replace['Module'], $serviceContent);
        $serviceContent = str_replace('{module}', $replace['module'], $serviceContent);
        $servicePath = base_path('app/Services/' . $option['serviceName'].'.php');
        FILE::put($servicePath,$serviceContent);
    }

    public function makeProvider($request){
        $name = $request->input('name');
        $provider = [
            'providerPath' => base_path('app/Providers/AppServiceProvider.php'),
            'repositoryProviderPath' => base_path('app/Providers/RepositoryServiceProvider.php'),
        ];
        
        foreach($provider as $key => $val){
            $content = file_get_contents($val);
            $insertLine = ($key == 'providerPath') ? "'App\\Services\\Interfaces\\{$name}ServiceInterface' => 'App\\Services\\{$name}Service'," : "'App\\Repositories\\Interfaces\\{$name}RepositoryInterface' => 'App\\Repositories\\{$name}Repository',"; 
            $position = strpos($content, '];');

            if($position !== false){
                $newContent = substr_replace($content, "    ".$insertLine . "\n".'    ', $position, 0);
            }
            File::put($val, $newContent);
        }
    }

    public function makeRequest($request){
        $name = $request->input('name');
        $requestArray = ['Store'.$name.'Request', 'Update'.$name.'Request', 'Delete'.$name.'Request'];
        $requestTemplate = ['RequestTemplateStore','RequestTemplateUpdate','RequestTemplateDelete'];
        if($request->input('module_type') != 'catalouge'){
            unset($requestArray[2]);
            unset($requestTemplate[2]);
        }
        foreach($requestTemplate as $key => $val){
            $requestPath = base_path('app/Templates/requests/'.$val.'.php');
            $requestContent = file_get_contents($requestPath);
            $requestContent = str_replace('{Module}', $name, $requestContent);
            $requestPut = base_path('app/Http/Requests/'.$requestArray[$key].'.php');
            FILE::put($requestPut, $requestContent);
        }
    }

    public function makeRule($request){
        $name = $request->input('name');
        $destination = base_path('app/Rules/Check'.$name.'ChildrenRule.php');
        $ruleTemplate = base_path('app/Templates/rules/RuleTemplate.php');
        $content = file_get_contents($ruleTemplate);
        $content = str_replace('{Module}', $name, $content);
        if(!FILE::exists($destination)){
            FILE::put($destination, $content);
        }
    }

    public function makeView($request){
        try{
            $name = $request->input('name');
            $module = $this->convertModuleNameToTableName($name); 
            $extractModule = explode('_', $module);
            $basePath =  resource_path("views/backend/{$extractModule[0]}");

            $folderPath = (count($extractModule) == 2) ? "$basePath/{$extractModule[1]}" : "$basePath/{$extractModule[0]}";
            $componentPath = "$folderPath/component";

            $this->createDirectory($folderPath);
            $this->createDirectory($componentPath);
            

            $sourcePath = base_path('app/Templates/views/'.((count($extractModule) == 2) ? 'catalouge' : 'detail').'/');
            $viewPath = (count($extractModule) == 2) ? "{$extractModule[0]}.{$extractModule[1]}" : $extractModule[0].'.'.$extractModule[0];
            $replacement = [
                'view' => $viewPath,
                'module' => lcfirst($name),
                'Module' => $name,
            ];
           
            $fileArray = ['create.blade.php','index.blade.php','delete.blade.php','edit.blade.php'];
            $componentFile = ['aside.blade.php', 'filter.blade.php','table.blade.php','seo.blade.php','general.blade.php'];
            $this->CopyAndReplaceContent($sourcePath, $folderPath, $fileArray, $replacement);
            $this->CopyAndReplaceContent("{$sourcePath}component/", $componentPath, $componentFile, $replacement);


            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        } 
    }
    //makeView
    private function createDirectory($path){
        if(!FILE::exists($path)){
            File::makeDirectory($path, 0755, true);
        }
    }
    //makeView
    private function CopyAndReplaceContent(string $sourcePath ,string $destinationPath, array $fileArray, array $replacement){
        foreach($fileArray as $key => $val){
            $sourceFile = $sourcePath.$val;
            $destination = "{$destinationPath}/{$val}";
            $content = file_get_contents($sourceFile);
            foreach($replacement as $keyReplace => $replace){
                $content = str_replace('{'.$keyReplace.'}', $replace, $content);
            }
            if(!FILE::exists($destination)){
                FILE::put($destination, $content);
            }
        }
    }

    public function makeRoute($request){
        $name = $request->input('name');
        $module = $this->convertModuleNameToTableName($name);
        $moduleExtract = explode('_', $module);
        $routesPath = base_path('routes/web.php');
        $content = file_get_contents($routesPath);
        $routeUrl = (count($moduleExtract) == 2) ? "{$moduleExtract[0]}/$moduleExtract[1]" : $moduleExtract[0];
        $routeName = (count($moduleExtract) == 2) ? "{$moduleExtract[0]}.$moduleExtract[1]" : $moduleExtract[0];

       
        
        $routeGroup = <<<ROUTE
        Route::group(['prefix' => '$routeUrl'], function () {
            Route::get('index', [{$name}Controller::class, 'index'])->name('{$routeName}.index');
            Route::get('create', [{$name}Controller::class, 'create'])->name('{$routeName}.create');
            Route::post('store', [{$name}Controller::class, 'store'])->name('{$routeName}.store');
            Route::get('{id}/edit', [{$name}Controller::class, 'edit'])->name('{$routeName}.edit');
            Route::post('{id}/update', [{$name}Controller::class, 'update'])->name('{$routeName}.update');
            Route::get('{id}/delete', [{$name}Controller::class, 'delete'])->name('{$routeName}.delete');
            Route::post('{id}/destroy', [{$name}Controller::class, 'destroy'])->name('{$routeName}.destroy');
        });
        //@@new-module@@

        ROUTE;

                $useController = <<<ROUTE
        use App\Http\Controllers\Backend\\{$name}Controller;
        //@@useController@@
        ROUTE;

      
        $content = str_replace('//@@new-module@@', $routeGroup, $content);
        $content = str_replace('//@@useController@@', $useController, $content);
       
        FILE::put($routesPath, $content);
    }

    public function destroy($id){
        DB::beginTransaction();
        try{
            $generate = $this->generateRepository->destroy($id);

            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    private function payload(){
        return [
           'name',
           'schema'
        ];
    }
}
