<?php

namespace App\Models\LHW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Filters_Modal extends Model
{
    use HasFactory;

    protected $table = 'HealthFacility';

    public static function getTehsilByDistrict($dist_id)
    {
        $data = DB::connection('sqlsrv_uen_ph2')->table('tehsil')
            ->where('dist_id', $dist_id)
            ->get();
        return $data;
    }

    public static function getHFByTehsil($tehsil_id)
    {
        $data = DB::connection('sqlsrv_uen_ph2')->table('HealthFacility')
            ->where('tehsil_id', $tehsil_id)
            ->get();
        return $data;
    }

}
