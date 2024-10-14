<?php


namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Models\District;

interface DistrictRepositoryInterface extends BaseRepositoryInterface
{


    public function findDistrictByProvinceId(int $province_id);
}
