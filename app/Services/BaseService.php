<?php
namespace App\Services;
use  App\Services\Interfaces\BaseServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class BaseService implements BaseServiceInterface
{

    public function __construct(){
        
    }

    public function currentLanguage(){
        return 3;
    }


}