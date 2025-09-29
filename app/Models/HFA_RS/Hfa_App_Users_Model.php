<?php

namespace App\Models\HFA_RS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Hfa_App_Users_Model extends Model
{
    use HasFactory;

    protected $table = 'AppUser';

    public static function getAllData()
    {
        $sql = DB::connection('UeN_HFA_EL')->table('AppUser')->select('*');
        $sql->where('enabled', '=', '1')->orderByDesc('id');
        $data = $sql->get();
        return $data;
    }

    public static function checkName($userName)
    {
        $data = DB::connection('UeN_HFA_EL')->table('AppUser')
            ->where('enabled', '=', '1')
            ->where('username', $userName)
            ->get();
        return $data;
    }

    public static function getUserDetails($id)
    {
        $data = DB::connection('UeN_HFA_EL')->table('AppUser')
            ->where('id', $id)
            ->get();
        return $data;
    }

    public static function getDistricts()
    {
        $sql = DB::connection('UeN_HFA_EL')->table('District')->select('district_code as dist_id', 'district_name as district');
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
