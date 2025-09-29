<?php

namespace App\Models\LHW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Summary_Model extends Model
{
    use HasFactory;

    protected $table = 'HealthFacility';

    public static function getMainSummary($searchFilter)
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('lhw')->select(DB::raw("HealthFacility.dist_id,
	HealthFacility.district,
	COUNT ( DISTINCT HealthFacility.tehsil ) AS total_tehsil,
	COUNT ( DISTINCT HealthFacility.hfcode ) AS total_hfc,
	COUNT ( lhw.dist_id ) AS total_lhw "))
            ->leftJoin('HealthFacility', 'lhw.hfCode', '=', 'HealthFacility.hfcode');
        $sql->groupBy('HealthFacility.dist_id', 'HealthFacility.district');

        if (isset($searchFilter['dist']) && $searchFilter['dist'] != '' && $searchFilter['dist'] != 0) {
            $sql->where('HealthFacility.dist_id', $searchFilter['dist']);
            $sql->addSelect('HealthFacility.tehsil', 'HealthFacility.tehsil_id');
            $sql->groupBy('HealthFacility.tehsil', 'HealthFacility.tehsil_id');
        }
        if (isset($searchFilter['tehsil']) && $searchFilter['tehsil'] != '' && $searchFilter['tehsil'] != 0) {
            $sql->where('HealthFacility.tehsil_id', $searchFilter['tehsil']);
            $sql->addSelect('HealthFacility.hfcode', 'HealthFacility.hf_name');
            $sql->groupBy('HealthFacility.hfcode', 'HealthFacility.hf_name');
        }
        if (isset($searchFilter['hfc']) && $searchFilter['hfc'] != '' && $searchFilter['hfc'] != 0) {
            $sql->where('HealthFacility.hfcode', $searchFilter['hfc']);
        }
        if (isset($searchFilter['lhw']) && $searchFilter['lhw'] != '' && $searchFilter['lhw'] != 0) {
            $sql->where('lhw.lhw_code', $searchFilter['lhw']);
        }
        $sql->where('lhw.dist_id', 'not like', '9%');
        if (isset(Auth::user()->district) && Auth::user()->district != '' && Auth::user()->district != '0') {
            $dist = Auth::user()->district;
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('HealthFacility.dist_id', '=', trim($d));
                }
            });
        }
        $sql->where(function ($query) {
            $query->where('HealthFacility.colflag')
                ->orWhere('HealthFacility.colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->where('lhw.colflag')
                ->orWhere('lhw.colflag', '=', '0');
        });
        $sql->orderBy('HealthFacility.district');
        $data = $sql->get();
        return $data;
    }


    public static function getMainActivitySummary($searchFilter)
    {

        $sql = DB::connection('sqlsrv_uen_ph2')->table('LHWForm as lform');

        $select = '';
        $grpBy = '';

        if (isset($searchFilter['dist']) && $searchFilter['dist'] != '' && $searchFilter['dist'] != 0) {
            $sql->where('lhw.dist_id', $searchFilter['dist']);
            $select .= 'lform.a102 as tehsil,lhw.tehsil_id,';
            $grpBy .= 'lform.a102,lhw.tehsil_id,';
        }

        if (isset($searchFilter['tehsil']) && $searchFilter['tehsil'] != '' && $searchFilter['tehsil'] != 0) {
            $sql->where('lhw.tehsil_id', $searchFilter['tehsil']);
            $select .= 'lform.a103,lhw.hfcode, ';
            $grpBy .= 'lform.a103,lhw.hfcode, ';
        }
        if (isset($searchFilter['hfc']) && $searchFilter['hfc'] != '' && $searchFilter['hfc'] != 0) {
            $sql->where('lhw.hfcode', $searchFilter['hfc']);
        }
        if (isset($searchFilter['lhw']) && $searchFilter['lhw'] != '' && $searchFilter['lhw'] != 0) {
            $sql->where('lhw.lhw_code', $searchFilter['lhw']);
        }

        $select .= "lform.a101,
        lhw.dist_id,
	COUNT ( DISTINCT lform.a102 ) AS total_tehsil,
	COUNT ( DISTINCT lform.a103 ) AS contacted_hfc,
	COUNT ( DISTINCT lform.a104c ) AS contacted_lhw ";
        $grpBy .= 'lform.a101, lhw.dist_id';

        $sql->select(DB::raw($select))
            ->leftJoin('lhw', 'lform.a104c', '=', 'lhw.lhw_code');
        $sql->groupBy(DB::raw($grpBy));

        if (isset(Auth::user()->district) && Auth::user()->district != '' && Auth::user()->district != '0') {
            $dist = Auth::user()->district;
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('lhw.dist_id', '=', trim($d));
                }
            });
        }
        $sql->where('lform.a101', 'Not like', '%test%');
        $sql->where('lform.username', 'Not like', '%test%');
        $sql->where('lform.username', 'Not like', '%moom8015%');
        $sql->where('lform.username', 'Not like', '%user%');
        $sql->whereNotIn('lform.col_id', [165, 145, 147, 138, 98, 2690, 1642, 3063, 3059, 2712, 1533]);
        $sql->where('lhw.dist_id', 'not like', '9%');
        $sql->where(function ($query) {
            $query->where('lform.colflag')
                ->orWhere('lform.colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->where('lhw.colflag')
                ->orWhere('lhw.colflag', '=', '0');
        });
        $data = $sql->get();
        return $data;
    }

}
