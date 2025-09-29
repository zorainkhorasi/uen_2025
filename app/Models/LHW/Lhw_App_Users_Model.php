<?php

namespace App\Models\LHW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Lhw_App_Users_Model extends Model
{
    use HasFactory;

    protected $table = 'AppUser';

    public static function getAllData()
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('AppUser')->select('*');
        $sql->where(function ($query) {
            $query->whereNull('colflag')
                ->orWhere('colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->whereNull('proj')
                ->orWhere('proj', '!=', 'KMC');
        });
        $sql->where('enabled', '=', '1')->orderByDesc('id');
        $data = $sql->get();
        return $data;
    }

    public static function checkName($userName)
    {
        $data = DB::connection('sqlsrv_uen_ph2')->table('AppUser')
            ->where('enabled', '=', '1')
            ->where(function ($query) {
                $query->whereNull('proj')
                    ->orWhere('proj', '!=', 'KMC');
            })
            ->where('username', $userName)
            ->get();
        return $data;
    }

    public static function getUserDetails($id)
    {
        $data = DB::connection('sqlsrv_uen_ph2')->table('AppUser')
            ->where('proj', '!=', 'KMC')
            ->where('id', $id)
            ->get();
        return $data;
    }

    public static function getDistricts()
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('District')->select('district_code as dist_id', 'district_name as district');
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
        $sql->groupBy('district_code', 'district_name');
        $data = $sql->get();
        return $data;
    }
}
