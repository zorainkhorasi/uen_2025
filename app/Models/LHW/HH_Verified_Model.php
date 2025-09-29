<?php

namespace App\Models\LHW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HH_Verified_Model extends Model
{
    use HasFactory;

    protected $table = 'HouseholdForm';

    public static function getData($searchFilter)
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('LHWHousehold')->select(DB::raw("LHWHousehold.a101,
	lhw.tehsil ,
	lhw.hf_name ,
	COUNT ( DISTINCT HouseholdForm.lhwCode ) AS total_hh"))
            ->leftJoin('lhw', 'LHWHousehold.lhwCode', '=', 'lhw.lhw_code')
            ->leftJoin('HouseholdForm', 'LHWHousehold._uid', '=', 'HouseholdForm._lhwuid');
        $sql->groupBy('LHWHousehold.a101', 'lhw.tehsil','lhw.hf_name');

        $sql->where('LHWHousehold.a101', 'NOT LIKE','%test%');
        $sql->where('LHWHousehold.username', 'NOT LIKE','%test%');
        $sql->where('LHWHousehold.username', 'NOT LIKE','%USER%');
        $sql->whereNotIn('LHWHousehold._uuid', ['a577479c1e3ab2d8', '4a7c3612a2a823c63', 'dbeaedb7ea8b87a06']);

        $sql->where(function ($query) {
            $query->where('HouseholdForm.colflag')
                ->orWhere('HouseholdForm.colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->where('LHWHousehold.colflag')
                ->orWhere('LHWHousehold.colflag', '=', '0');
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
            $sql->where('LHWHousehold.lhwCode', $searchFilter['lhw']);
        }

        if (isset(Auth::user()->district) && Auth::user()->district != '' && Auth::user()->district != '0') {
            $dist = Auth::user()->district;
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('LHWHousehold.a104c', 'like', trim($d).'%');
                }
            });
        }
        $data = $sql->get();
        return $data;
    }
}
