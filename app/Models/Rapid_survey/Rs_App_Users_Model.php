<?php

namespace App\Models\Rapid_survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Rs_App_Users_Model extends Model
{
    use HasFactory;


    protected $table = 'users';


    public static function getAllData()
    {
        $sql =DB::connection('endline_survey')->table('users')->select('*');
        $sql->where('enabled', '=', '1')->orderByDesc('id');
        $data = $sql->get();
        return $data;
    }

    public static function checkName($userName)
    {
        $data =DB::connection('endline_survey')->table('users')
            ->where('enabled', '=', '1')
            ->where('username', $userName)
            ->get();
        return $data;
    }

    public static function getUserDetails($id)
    {
        $data = DB::connection('endline_survey')->table('users')
            ->where('id', $id)
            ->get();
        return $data;
    }

    public static function getDistricts()
    {
        $sql =DB::connection('endline_survey')->table('clusters')->select('dist_id','district');
        if (isset(Auth::user()->district) && Auth::user()->district != '' && Auth::user()->district != '0') {
            $dist = Auth::user()->district;
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('dist_id', '=', trim($d));
                }
            });
        }
        $sql->where(function ($query) {
            $query->whereNull('col_flag')
                ->orWhere('col_flag', '=', '0');
        });
        $sql->groupBy('dist_id','district');
        $data = $sql->get();
        return $data;
    }

}
