<?php

namespace App\Models\LHW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HFC_Summary_Model extends Model
{
    use HasFactory;

    protected $table = 'lhw';

    public static function getData($searchFilter)
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('lhw')->select(DB::raw("HealthFacility.district,
	lhw.tehsil,
	lhw.uc_name,
	lhw.hf_name,
	COUNT ( DISTINCT lhw.lhw_code ) as total_lhws"))
            ->leftJoin('HealthFacility', 'lhw.dist_id', '=', 'HealthFacility.dist_id');
        $sql->groupBy('HealthFacility.district', 'lhw.tehsil', 'lhw.uc_name', 'lhw.hf_name');
        $sql->where('lhw.dist_id', 'NOT LIKE','9%');
        $sql->where(function ($query) {
            $query->where('HealthFacility.colflag')
                ->orWhere('HealthFacility.colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->where('lhw.colflag')
                ->orWhere('lhw.colflag', '=', '0');
        });

        if (isset($searchFilter['dist']) && $searchFilter['dist'] != '' && $searchFilter['dist'] != 0) {
            $sql->where('lhw.dist_id', $searchFilter['dist']);
        }
        if (isset($searchFilter['tehsil']) && $searchFilter['tehsil'] != '' && $searchFilter['tehsil'] != 0) {
            $sql->where('lhw.tehsil_id', $searchFilter['tehsil']);
        }
        if (isset($searchFilter['hfc']) && $searchFilter['hfc'] != '' && $searchFilter['hfc'] != 0) {
            $sql->where('lhw.hfcode', $searchFilter['hfc']);
        }
        if (isset($searchFilter['lhw']) && $searchFilter['lhw'] != '' && $searchFilter['lhw'] != 0) {
            $sql->where('lhw.lhw_code', $searchFilter['lhw']);
        }

        if (isset(Auth::user()->district) && Auth::user()->district != '' && Auth::user()->district != '0') {
            $dist = Auth::user()->district;
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('lhw.dist_id', 'like', trim($d) . '%');
                }
            });
        }
        $data = $sql->get();
        return $data;
    }
}
