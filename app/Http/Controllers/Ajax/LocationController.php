<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\DistrictRepositoryInterface as DistrictRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;

class LocationController extends Controller
{
    protected $districtRepository;
    protected $provinceRepository;

    public function __construct(DistrictRepository $districtRepository, ProvinceRepository $provinceRepository)
    {
        $this->districtRepository = $districtRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function getLocation(Request $request)
    {
        $target = $request->input('target');
        $location_id = $request->input('location_id');
        
        $html = ''; 
        if ($target == 'districts') {
            $province_id = (int)$location_id;
            $province = $this->provinceRepository->findById($province_id, ['code', 'name'], ['districts']);
            $html = $this->renderHTML($province->districts, '[Chọn Quận/Huyện]');
        } elseif ($target == 'wards') {
            $district_id = (int)$location_id;
            $district = $this->districtRepository->findById($district_id, ['code', 'name'], ['wards']);
            $html = $this->renderHTML($district->wards, '[Chọn Phường/Xã]');
        }

        return response()->json(['html' => $html]);
    }

    public function renderHTML($locations, $root = '[Chọn]')
    {
        $html = '<option value="">' . $root . '</option>';
        foreach ($locations as $location) {
            $html .= '<option value="' . $location->code . '">' . $location->name . '</option>';
        }
        return $html;
    }
}

