<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Health_Care_Providers_Model extends Model
{
    use HasFactory;

    protected $table = 'healthcare_providers';

    public static function getAllData()
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('healthcare_providers')->select('*');
        $sql->where(function ($query) {
            $query->whereNull('colflag')
                ->orWhere('colflag', '=', '0');
        });
        $data = $sql->get();
        return $data;
    }

    public static function getMaxProviderCode($hfcode)
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('healthcare_providers')->select(DB::raw('max(provider_code) as max_provider_code'))
            ->where('hfcode', $hfcode);
        $sql->where(function ($query) {
            $query->whereNull('colflag')
                ->orWhere('colflag', '=', '0');
        });
        $data = $sql->get();
        return $data;
    }
    public static function checkName($userName)
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('healthcare_providers')->select('*')
            ->where('provider_name', $userName);
        $sql->where(function ($query) {
            $query->whereNull('colflag')
                ->orWhere('colflag', '=', '0');
        });
        $data = $sql->get();
        return $data;
    }

    public static function getUserDetails($id)
    {
        $data = DB::connection('sqlsrv_uen_ph2')->table('healthcare_providers')
            ->where('id', $id)
            ->get();
        return $data;
    }


    public static function getDistricts()
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('District')
            ->select('district_code as dist_id', 'district_name as district','pro_id','province');
        if (isset(Auth::user()->district) && Auth::user()->district != '' && Auth::user()->district != '0') {
            $dist = Auth::user()->district;
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('district_code', '=', trim($d));
                }
            });
        }
        $sql->where(function ($query) {
            $query->whereNull('colflag')
                ->orWhere('colflag', '=', '0');
        });
        $sql->groupBy('district_code', 'district_name','pro_id','province');
        $data = $sql->get();
        return $data;
    }
}
