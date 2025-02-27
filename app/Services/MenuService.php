<?php
namespace App\Services;

use  App\Services\Interfaces\MenuServiceInterface;
use  App\Services\BaseService;
use  App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;//tương tác với database
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class MenuService  extends BaseService implements MenuServiceInterface
{
    protected $menuRepository;
    public function __construct( MenuRepository $menuRepository){
        $this->menuRepository=$menuRepository;
    }
//userRepository là dependency của class UserService vì UserService phụ thuộc userRepository

public function paginate($request){
   return 0;
     
 }
 public function paginateSelect(){
     return ['id','email','name','phone','address','publish','user_catalouge_id'];    
 }

}