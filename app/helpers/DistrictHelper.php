<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class DistrictHelper
{
    public static function applyDistrictFilter($query, $columnName = 'dist_id')
    {
        if(isset(Auth::user()->district) && Auth::user()->district != '' && Auth::user()->district != '0' && Auth::user()->idGroup != '1') {
            $district = Auth::user()->district;

            if (strpos($district, ',') !== false) {
                $districtArray = explode(',', $district);
                $districtArray = array_map('trim', $districtArray);
                $query->whereIn($columnName, $districtArray);
            } else {
                $query->where($columnName, '=', $district);
            }
        }
        // dd($query->get());
        return $query;
    }
}
