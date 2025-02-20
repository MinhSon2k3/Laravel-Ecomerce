<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{

    public $serviceBindings=[
        //USER
        'App\Services\Interfaces\UserServiceInterface'=>'App\Services\UserService',
         //USER-CATALOGUE  
        'App\Services\Interfaces\UserCatalougeServiceInterface'=>'App\Services\UserCatalougeService',
         //Permission
         'App\Services\Interfaces\PermissionServiceInterface'=>'App\Services\PermissionService',
        //POST-CATALOUGE
        'App\Services\Interfaces\PostCatalougeServiceInterface'=>'App\Services\PostCatalougeService',
        //POST
        'App\Services\Interfaces\PostServiceInterface'=>'App\Services\PostService',
         //LANGUAGE
         'App\Services\Interfaces\LanguageServiceInterface'=>'App\Services\LanguageService',
        //Generate
         'App\Services\Interfaces\GenerateServiceInterface'=>'App\Services\GenerateService',
        //Base
        'App\Services\Interfaces\BaseServiceInterface'=>'App\Services\BaseService',
        //Product
        'App\Services\Interfaces\ProductCatalougeServiceInterface' => 'App\Services\ProductCatalougeService',
        'App\Services\Interfaces\ProductServiceInterface' => 'App\Services\ProductService',
        //Attribute
        'App\Services\Interfaces\AttributeCatalougeServiceInterface' => 'App\Services\AttributeCatalougeService',
        'App\Services\Interfaces\AttributeServiceInterface' => 'App\Services\AttributeService',
        //System
        'App\Services\Interfaces\SystemServiceInterface' => 'App\Services\SystemService',
         //Menu
         'App\Services\Interfaces\MenuServiceInterface' => 'App\Services\MenuService',
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {   
        require_once app_path('Helpers/MyHelper.php');
        foreach($this->serviceBindings as $key => $val ){
            $this->app->bind($key,$val);
        }
        $this->app->register(RepositoryServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        schema::defaultStringLength(191);
    }
}