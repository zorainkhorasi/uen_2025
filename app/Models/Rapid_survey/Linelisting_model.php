<?php

namespace App\Models\Rapid_survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\helpers\DistrictHelper;

class linelisting_model extends Model
{
    use HasFactory;

    protected $table = 'listings';


    public static function getClustersProvince($searchdata)
    {
        // dd('here');
        $sql = DB::connection('endline_survey')->table('clusters');
        $sql->select(DB::raw('dist_id,district,COUNT (dist_id) AS totalDistrict'));
        if (isset($searchdata['pageLevel']) && $searchdata['pageLevel'] != '' && $searchdata['pageLevel'] == '2') {
            $sql->select(DB::raw('dist_id,district,tehsil,COUNT (dist_id) AS totalDistrict'));
            $sql->groupBy('tehsil');
        }

        $sql->groupBy('dist_id', 'district');
        if (isset($searchdata['district']) && $searchdata['district'] != '') {
            $dist = $searchdata['district'];
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('dist_id', '=', trim($d));
                }
            });
        }
        $sql = DistrictHelper::applyDistrictFilter($sql, 'dist_id');


        $sql->where(function ($query) {
            $query->where('col_flag')
                ->orWhere('col_flag', '=', '0');
        });
        $sql->where('cluster_no', 'NOT LIKE', '999%');

        $sql->orderBy('dist_id', 'ASC');
        $data = $sql->get();
        return $data;
    }

    public static function completedClusters_district($searchdata)
    {
        $sql = DB::table('clusters as c');
        $select = " l.hh01a,c.district,l.hh01,c.dist_id,(SELECT count(distinct deviceid) FROM Listings
            Where hh01=l.hh01 and (hh11 NOT like ('Deleted') or hh11 IS NULL) AND (col_flag IS NULL OR col_flag=0)) as collecting_tabs,
            (SELECT COUNT(DISTINCT deviceid) completed_tabs FROM (SELECT deviceid,MAX(CAST(hh04 as int)) ms FROM Listings  where  hh01a= l.hh01a and hh01=l.hh01 and
            ((hh11 NOT like ('Deleted') or hh11 IS NULL)  and (col_flag IS NULL or col_flag=0) AND hh07=9) GROUP BY deviceid) AS completed_tabs)completed_tabs";

        if (isset($searchdata['pageLevel']) && $searchdata['pageLevel'] != '' && $searchdata['pageLevel'] == '2') {
            $select .= ',c.tehsil';
            $sql->groupBy('c.tehsil');
        }
        $sql->select(DB::raw($select))->leftJoin('listings as l', 'c.cluster_no', '=', 'l.hh01');

        // if (isset($searchdata['district']) && $searchdata['district'] != '') {
        //     $dist = $searchdata['district'];
        //     $sql->where(function ($query) use ($dist) {
        //         $exp_dist = explode(',', $dist);
        //         foreach ($exp_dist as $d) {
        //             $query->orWhere('c.dist_id', '=', trim($d));
        //         }
        //     });
        // }
        $sql = DistrictHelper::applyDistrictFilter($sql, 'dist_id');

        $sql->where(function ($query) {
           // $query->whereNotIn('l.username', ['user0113'])
                $query->where('l.username', 'NOT LIKE', 'user0113')
                ->orWhere('l.username');
        });
        $sql->where(function ($query) {
            $query->where('l.col_flag')
                ->orWhere('l.col_flag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->where('c.col_flag')
                ->orWhere('c.col_flag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->orWhere('hh15', '!=', '')
                ->orWhere('hh11', 'NOT LIKE', 'Deleted');
        });
        $sql->where('cluster_no', 'NOT LIKE', '999%');
        //  $sql->where('cluster_no', 'NOT LIKE', '%9502');
        $sql->groupBy('c.district', 'c.dist_id', 'l.col_flag', 'l.hh01a', 'l.hh01');
        $sql->orderBy('l.hh01', 'ASC');
        $sql->orderBy('c.dist_id', 'ASC');
        $data = $sql->get();
        //echo '<pre>';print_r($completedClusters_district);die;
        return $data;
    }

    /*============================ LineListing Datatable  ============================*/
    public static function get_linelisting_table($searchdata)
    {
        //        (select DISTINCT COUNT (hh03) FROM listings where hh04 in (1,2) and hh02 = l.hh02 AND (col_flag is null or col_flag=0)) as structures,
        $sql = DB::connection('endline_survey')->table('clusters as c');
        $select = "c.cluster_no,l.hh01a,l.hh01,c.randomized,c.tehsil,
          (SELECT COUNT (*) FROM (SELECT DISTINCT hh04,tabNo FROM [UeN_EL_II].[dbo].[listings]
            WHERE hh07 NOT IN ('8','9') AND (col_flag is null or col_flag=0) and (hh11!='Deleted' or hh11 is null) AND hh01 = l.hh01) AS structures) AS structures,
            (select DISTINCT COUNT (hh04) FROM [UeN_EL_II].[dbo].[listings]  where hh08 = '1' and	 (hh11!='Deleted' or hh11 is null) and hh01 = l.hh01 AND (col_flag is null or col_flag=0)) as residential_structures,
            (select DISTINCT COUNT (hh04) FROM [UeN_EL_II].[dbo].[listings]  where hh08 = '1' and	 (hh11!='Deleted' or hh11 is null) AND hh13=1 and hh01 = l.hh01 AND (col_flag is null or col_flag=0)) as eligible_households,
            (select sum(cast(hh13a as int)) FROM [UeN_EL_II].[dbo].[listings] where hh08 = '1' and	 (hh11!='Deleted' or hh11 is null) and hh01 = l.hh01 AND (col_flag is null or col_flag=0)) as no_of_eligible_wras,
            (select count(distinct deviceid) FROM [UeN_EL_II].[dbo].[listings] where hh01 = l.hh01 and (hh11!='Deleted' or hh11 is null) AND (col_flag is null or col_flag=0)) as collecting_tabs,
            (select count(*) completed_tabs from(select deviceid, max(cast(hh04 as int)) ms FROM [UeN_EL_II].[dbo].[listings] where hh01a = l.hh01a and (hh11!='Deleted' or hh11 is null) and hh01 = l.hh01 AND (col_flag is null OR col_flag = '0')
            and hh07 = '9'  group by deviceid) AS completed_tabs) completed_tabs";
        $sql = DistrictHelper::applyDistrictFilter($sql, 'dist_id');
        $sql->select(DB::raw($select))->leftJoin('listings as l', function ($join) {
            $join->on('c.cluster_no', '=', 'l.hh01')
                ->where(function ($query) {
                    $query->where('l.username', 'NOT LIKE', 'user0113')
                        ->orWhereNull('l.username');
                })
                ->where(function ($query) {
                    $query->whereNull('l.col_flag')
                        ->orWhere('l.col_flag', '=', 0);
                })
                ->where(function ($query) {
                    $query->where('l.hh15', '!=', '')
                        ->orWhere('l.hh11', 'NOT LIKE', 'Deleted');
                });
        });

        if (isset($searchdata['type']) && $searchdata['type'] == 'c') {
            //echo 111;die;
            $sql->whereRaw("(select count(distinct deviceid) from listings where hh01a = l.hh01a  AND (col_flag is null or col_flag=0 ))!=0
             ");
        } elseif (isset($searchdata['type']) && $searchdata['type'] == 'i') {

            $sql->whereRaw(" (select count(distinct deviceid) from listings where hh02 = l.hh02 and hh01a = l.hh01a  and (hh11!='1' or hh11 is null) AND (col_flag is null or col_flag=0 ))
             != (select count(*) completed_tabs from(select deviceid, max(cast(hh03 as int)) ms from listings where hh01a = l.hh01a and hh02 = l.hh02  and (hh11!='1' or hh11 is null) AND (col_flag is null OR col_flag = '0')  and hh04 = 9 group by deviceid) AS completed_tabs)");
        } elseif (isset($searchdata['type']) && $searchdata['type'] == 'r') {
            $sql->whereRaw("(select count(distinct deviceid) from listings where hh02 = l.hh02 and hh01a = l.hh01a  and (hh11!='Deleted' or hh11 is null) AND (col_flag is null or col_flag=0))=0  and( l.hh01 IS  NULL)");
        } else {
            $cluster_type_where = '';
        }
        if (isset($searchdata['district']) && $searchdata['district'] != '') {
            $dist = $searchdata['district'];
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('c.dist_id', '=', trim($d));
                }
            });
        }

        $sql->where(function ($query) {
            $query->where('c.col_flag')
                ->orWhere('c.col_flag', '=', '0');
        });

        $sql->where('cluster_no', 'NOT LIKE', '999%');
        //$sql->where('cluster_no', 'NOT LIKE', '%9502');
        $sql->groupBy('c.cluster_no', 'l.hh01a', 'l.hh01', 'c.randomized', 'c.tehsil');
        $sql->orderBy('c.cluster_no', 'ASC');
        $sql->orderBy('l.hh01a', 'ASC');
        $data = $sql->get();


        return $data;
    }

    /*============================ Systematic Randomization ============================*/
    public static function get_rand_cluster($cluster)
    {
        $sql = DB::connection('endline_survey')->table('clusters as c')->select('c.randomized');
        $sql->where('cluster_no', '=', $cluster);
        $sql->where(function ($query) {
            $query->where('c.col_flag')
                ->orWhere('c.col_flag', '=', '0');
        });

        $data = $sql->get();
        return $data;
    }

    public static function chkDuplicateTabs($cluster)
    {
        $sql = DB::connection('endline_survey')->table('listings');
        $select = "COUNT ((tabNo + '-' + hh03 + '-' + hh07)) AS duplicates,(tabNo + '-' + hh03 + '-' + hh07) AS hh";
        $sql->select(DB::raw($select));
        $sql->where('hh02', '=', $cluster);
        $sql->where(function ($query) {
            $query->where('col_flag')
                ->orWhere('col_flag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->whereNotIn('username', ['dmu@aku', 'user0001', 'user0002', 'test1234', 'afg12345', 'user0113', 'user0123', 'user0211', 'user0234', 'user0252', 'user0414', 'user0432', 'user0434'])
                ->orWhere('username');
        });
        $sql->where(function ($query) {
            $query->where('hh15')
                ->orWhere('hh15', '!=', '1');
        });
        $sql->groupByRaw("(tabNo + '-' + hh03 + '-' + hh07)");
        $sql->havingRaw("(COUNT (tabNo + '-' + hh03 + '-' + hh07)) > 1");
        $data = $sql->get();
        return $data;
    }

    public static function get_systematic_rand($cluster)
    {
        $sql = DB::connection('endline_survey')->table('listings');
        $select = "hhid,col_id,tabNo,village_name,hh01,hh04,hh07,hh08,hh02,hh11,hh13,hh13a,dov, hh01a,LEFT(hh01,3)dist_id, _uid";
        $sql->select(DB::raw($select));
        $sql->where('hh01', '=', $cluster);
        $sql->where('hh08', '=', '1');
        $sql->where('hh13', '=', '1');
        //  $sql->where('hh10', '=', '1');
        $sql->where(function ($query) {
            $query->where('col_flag')
                ->orWhere('col_flag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->whereNotIn('username', ['user0113', 'user0252'])
                ->orWhere('username');
        });
        $sql->where('hh11', '!=', 'Deleted');

        $sql->orderByRaw("tabNo, deviceid,cast(hh04 as int)");
        $data = $sql->get();
        return $data;

    }

    public static function get_randomized_table($cluster)
    {
        $sql = DB::connection('endline_survey')->table('bl_randomised');
        $select = "bl_randomised.dist_id,hh02,hh01, bl_randomised.randDT,bl_randomised.hh02,bl_randomised.hh08,bl_randomised.compid,bl_randomised.tabNo,
        clusters.geoarea,clusters.district,clusters.tehsil,clusters.uc,clusters.village";
        $sql->select(DB::raw($select))->leftJoin('clusters', 'bl_randomised.hh02', '=', 'clusters.cluster_no');
        $sql->where('bl_randomised.hh02', '=', $cluster);
        $sql->where(function ($query) {
            $query->where('bl_randomised.col_flag')
                ->orWhere('bl_randomised.col_flag', '=', '0');
        });
        $sql->orderByRaw("bl_randomised.sno,bl_randomised._id");
        $data = $sql->get();
        return $data;

    }
}
