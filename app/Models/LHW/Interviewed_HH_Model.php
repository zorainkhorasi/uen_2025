<?php

namespace App\Models\LHW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Interviewed_HH_Model extends Model
{
    use HasFactory;

    protected $table = 'HouseholdForm';

    public static function getData($searchFilter)
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('FamilyMember as fm')->select(DB::raw("lhw.dist_id,
	District.district_name,
SUM (CASE WHEN fm.h306=2 AND CAST (fm.h305 AS INT)>=10 AND CAST (fm.h305 AS INT)< 19 AND fm.h309=1 THEN 1 ELSE 0 END) AS avail_adol,

(SELECT COUNT (DISTINCT fmm._uuid) FROM FamilyMember AS fmm LEFT JOIN lhw ll ON fmm.lhwCode =ll.lhw_code LEFT JOIN HouseholdForm ON fmm.lhwCode =HouseholdForm.lhwcode AND fmm._uuid =HouseholdForm._uid WHERE fmm.h306 =2 AND fmm.h309=1 AND ll.dist_id=lhw.dist_id AND CAST (fmm.h305 AS INT)>=10 AND CAST (fmm.h305 AS INT)< 19 AND HouseholdForm.ab102!=0  AND HouseholdForm.ab102 is not null AND (fmm.colflag IS NULL OR fmm.colflag =0) AND (HouseholdForm.colflag IS NULL OR HouseholdForm.colflag =0)) AS interviewed_adol,

SUM (CASE WHEN fm.h303=1 AND CAST (fm.h305 AS INT)>= 18 AND fm.h309=1 THEN 1 ELSE 0 END) AS avail_male,

(SELECT COUNT (DISTINCT fmm._uuid) FROM FamilyMember AS fmm LEFT JOIN lhw ll ON fmm.lhwCode =ll.lhw_code LEFT JOIN HouseholdForm ON fmm.lhwCode =HouseholdForm.lhwcode AND fmm._uuid =HouseholdForm._uid WHERE fmm.h303=1 AND fmm.h309=1 AND CAST (fmm.h305 AS INT)>= 18 AND ll.dist_id=lhw.dist_id   AND (fmm.colflag IS NULL OR fmm.colflag =0) AND (HouseholdForm.colflag IS NULL OR HouseholdForm.colflag =0) AND fmm.username NOT LIKE 'user0301' AND fmm.username NOT LIKE 'moom8015' AND fmm._uuid NOT IN ('a539aea26d9ff352153','c9dde9f714cd70e914','c9dde9f714cd70e915','c9dde9f714cd70e916','c9dde9f714cd70e917','c9dde9f714cd70e918')) AS interviewed_male,

SUM (CASE WHEN fm.h303=2 AND CAST (fm.h305 AS INT)>=15 AND CAST (fm.h305 AS INT)<=49 AND fm.h306!=2 AND fm.h309=1 THEN 1 ELSE 0 END) AS avail_mwra,
COUNT ( distinct MwraForm._uuid) AS interviewed_mwra"))
            ->leftJoin('lhw', 'fm.lhwCode', '=', 'lhw.lhw_code')
            ->leftJoin('District', 'lhw.dist_id', '=', 'District.district_code')
            ->leftJoin('MwraForm', function($join)
            {
                $join->on('fm.lhwCode', '=', 'MwraForm.cluster');
                $join->on('fm._uid', '=', 'MwraForm._uuid');
            });
        $sql->groupBy('lhw.dist_id', 'District.district_name');
        $sql->where(function ($query) {
            $query->where('fm.colflag')
                ->orWhere('fm.colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->where('lhw.colflag')
                ->orWhere('lhw.colflag', '=', '0');
        });

        $sql->where('fm.username', 'Not like', '%user%');
        $sql->where('fm.username', 'Not like', '%test%');
        $sql->where('fm.username', 'Not like', '%moom8015%');
        $sql->where('lhw.dist_id', 'not like', '9%');


        if (isset($searchFilter['dist']) && $searchFilter['dist'] != '' && $searchFilter['dist'] != 0) {
            $sql->where('lhw.dist_id', $searchFilter['dist']);
            $sql->addSelect('lhw.tehsil');
            $sql->groupBy('lhw.tehsil');
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
                    $query->orWhere('lhw.dist_id', '=', trim($d));
                }
            });
        }
        $data = $sql->get();
        return $data;
    }
}
