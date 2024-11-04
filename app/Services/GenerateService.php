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
        $condition['publish'] = $request->integer('publish');
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
            $this->makeDatabase($request);
            $payload['user_id']= Auth::id();
            $generate=$this->generateRepository->create($payload);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage().'-'.$e->getLine();die();
            return false;
        }
    }

    public function makeDatabase($request){
        $payload = $request->only('schema', 'name','module_type');
        $tableName =$this->convertModuleNameToTableName($payload['name']).'s';  
        $module = date('Y_m_d_His').'_create_'.$tableName.'_table.php';
        $migrationPath=database_path('migrations/'.$module);
        $migrationTemplate=$this->createMigrationFile($payload);
        FILE::put($migrationPath,$migrationTemplate);
        if($payload['module_type']!==3){
            $foreignKey=$this->convertModuleNameToTableName($payload['name'].'_id');
            $pivotTableName =$this->convertModuleNameToTableName($payload['name']).'_language';  
            $pivotSchema=$this->pivotSchema($tableName,$foreignKey,$pivotTableName);
             $migrationPivotTemplate=$this->createMigrationFile([
                'schema'=>$pivotSchema,
                'name'=>$pivotTableName,
             ]);
             $module1 = date('Y_m_d_His',time() + 100).'_create_'.$pivotTableName.'_table.php';
             $migrationPivotPath=database_path('migrations/'.$module1);
            FILE::put($migrationPivotPath,$migrationPivotTemplate);   
        }
        ARTISAN::call('migrate');
    }

    private function createMigrationFile($payload){
        $migrationTemplate= <<<MIGRATION
        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            /**
             * Run the migrations.
             */
            public function up(): void
            {
                {$payload['schema']}
            }

            /**
             * Reverse the migrations.
             */
            public function down(): void
            {
                Schema::dropIfExists('{$this->convertModuleNameToTableName($payload['name'])}');
            }
        };  
        MIGRATION;
        return $migrationTemplate;
    }

    private function pivotSchema($tableName,$foreignKey,$pivotTableName){
        $pivotSchema= <<<SCHEMA
         Schema::create('$pivotTableName', function (Blueprint \$table) {
            \$table->unsignedbigInteger('{$foreignKey}');
            \$table->unsignedbigInteger('language_id');
            \$table->foreign('$foreignKey')->references('id')->on('$tableName')->onDelete('cascade');
            \$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            \$table->string('name');
            \$table->text('description');
            \$table->longText('content');
            \$table->string('meta_title');
            \$table->string('meta_keyword');
            \$table->text('meta_description');
        });
        SCHEMA;
        return $pivotSchema;
    }

    private function convertModuleNameToTableName($name){
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        return $temp;
    }

    public function update($id, $request){
        DB::beginTransaction();
        try{

            $payload = $request->except(['_token','send']);
            $generate = $this->generateRepository->update($id, $payload);
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
            $generate = $this->generateRepository->delete($id);

            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

}
