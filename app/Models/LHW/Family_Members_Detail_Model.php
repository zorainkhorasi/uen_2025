<?php

namespace App\Models\LHW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Family_Members_Detail_Model extends Model
{
    use HasFactory;

    protected $table = 'HouseholdForm';

    public static function getData($searchFilter)
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('FamilyMember as fm')->select(DB::raw("lhw.dist_id,
	District.district_name,
	SUM (CASE WHEN  CAST (fm.h305 AS INT)>=0 AND CAST (fm.h305 AS INT)<=4 THEN 1 ELSE 0 END) AS child_0_4,
	SUM (CASE WHEN fm.h303=1 AND CAST (fm.h305 AS INT)>=0 AND CAST (fm.h305 AS INT)<=4 THEN 1 ELSE 0 END) AS total_0_4_male,
	SUM (CASE WHEN fm.h303=2 AND CAST (fm.h305 AS INT)>=0 AND CAST (fm.h305 AS INT)<=4 THEN 1 ELSE 0 END) AS total_0_4_female,
	SUM (CASE WHEN  CAST (fm.h305 AS INT)>=5 AND CAST (fm.h305 AS INT)<=9 THEN 1 ELSE 0 END) AS child_5_10,
	SUM (CASE WHEN fm.h303=1 AND CAST (fm.h305 AS INT)>=5 AND CAST (fm.h305 AS INT)<=9 THEN 1 ELSE 0 END) AS total_5_10_male,
	SUM (CASE WHEN fm.h303=2 AND CAST (fm.h305 AS INT)>=5 AND CAST (fm.h305 AS INT)<=9 THEN 1 ELSE 0 END) AS total_5_10_female,
	SUM (CASE WHEN   fm.h306=2 AND CAST (fm.h305 AS INT)>=10 AND CAST (fm.h305 AS INT)<19 THEN 1 ELSE 0 END) AS adol,
	SUM (CASE WHEN fm.h303=1 AND fm.h306=2 AND CAST (fm.h305 AS INT)>=10 AND CAST (fm.h305 AS INT)<19 THEN 1 ELSE 0 END) AS total_adol_male,
	SUM (CASE WHEN fm.h303=2 AND fm.h306=2 AND CAST (fm.h305 AS INT)>=10 AND CAST (fm.h305 AS INT)<19 THEN 1 ELSE 0 END) AS total_adol_female,
	SUM (CASE WHEN fm.h303=2 and CAST (fm.h305 AS INT)>49 THEN 1 ELSE 0 END) AS female_49,
	SUM (CASE WHEN fm.h303=2 and CAST (fm.h305 AS INT)>=15  and CAST (fm.h305 AS INT)<=49  THEN 1 ELSE 0 END) AS wra,
	SUM (CASE WHEN fm.h303=2 and CAST (fm.h305 AS INT)>=15  and CAST (fm.h305 AS INT)<=49 and  fm.h306!=2 THEN 1 ELSE 0 END) AS mwra,
	SUM (CASE WHEN fm.h303=1 and CAST (fm.h305 AS INT)>19   THEN 1 ELSE 0 END) AS male"))
            ->leftJoin('lhw', 'fm.lhwCode', '=', 'lhw.lhw_code')
            ->leftJoin('District', 'lhw.dist_id', '=', 'District.district_code');
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
