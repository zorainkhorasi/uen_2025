<?php

namespace App\Models\Rapid_survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class datacollection_model extends Model
{
    use HasFactory;

    protected $table = 'forms';

    public static function randomizedClusters_district($searchdata)
    {
        $sql = DB::connection('endline_survey')->table('clusters');
        $select = "dist_id,district,SUM ( CASE WHEN randomized = '1' THEN 1 ELSE 0 END ) AS randomized_c";
        if (isset($searchdata['pageLevel']) && $searchdata['pageLevel'] != '' && $searchdata['pageLevel'] == '2') {
            $select .= ',tehsil';
            $sql->groupBy('tehsil');
        }
        $sql->select(DB::raw($select));
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
        $sql->where(function ($query) {
            $query->where('col_flag')
                ->orWhere('col_flag', '=', '0')
                ->orWhereNotIn('col_flag', ['1','2', '3']);
        });
        $sql->where('cluster_no', 'NOT LIKE', '%9501');
        $sql->where('cluster_no', 'NOT LIKE', '%9502');
        $sql->orderBy('dist_id', 'ASC');
        $data = $sql->get();
        return $data;
    }

    public static function completed_rand_Clusters_district($searchdata)
    {

        $sql = DB::connection('endline_survey')->table('clusters as c');
        $select = "c.dist_id,c.district,c.cluster_no,
        (SELECT COUNT (*) FROM bl_randomised WHERE dist_id=c.dist_id AND hh02=c.cluster_no AND (bl_randomised.col_flag IS NULL OR bl_randomised.col_flag ='0')) AS hh_randomized,
        (SELECT COUNT (DISTINCT f.hhid) FROM  Form AS f WHERE f.dist_id=c.dist_id AND f.a101 =c.cluster_no AND
        f.username NOT IN ('user0113','user0252') AND c.cluster_no NOT LIKE '999%' ) AS hh_collected";

        if (isset($searchdata['pageLevel']) && $searchdata['pageLevel'] != '' && $searchdata['pageLevel'] == '2') {
            $select .= ',c.tehsil';
            $sql->groupBy('c.tehsil');
        }
        $sql->select(DB::raw($select));

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
                ->orWhere('c.col_flag', '=', '0')
                ->orWhereNotIn('c.col_flag', ['1','2', '3']);
        });
        $sql->where('c.cluster_no', 'NOT LIKE', '%9501');
        $sql->where('c.cluster_no', 'NOT LIKE', '%9502');
        $sql->where('c.cluster_no', '!=', 'null');
        $sql->where('c.randomized', '=', '1');
        $sql->groupBy('c.dist_id', 'c.district', 'c.cluster_no');
        $sql->orderBy('c.dist_id', 'ASC');
        $data = $sql->get();
        return $data;
    }

    /*============================ Data Collection Datatable  ============================*/
    public static function get_dc_table($searchdata)
    {
//        (SELECT COUNT (DISTINCT f.hhno) FROM  forms AS f WHERE dist_id= c.dist_id AND f.cluster_code = c.cluster_no AND username NOT IN ('user0113','user0252') AND c.cluster_no NOT LIKE '999%' ) AS hh_collected";
        $sql = DB::connection('endline_survey')->table('clusters as c');
        $select = "c.dist_id,c.district,c.tehsil,c.cluster_no,
        (SELECT COUNT (*) FROM bl_randomised WHERE dist_id=c.dist_id AND hh02=c.cluster_no AND (bl_randomised.col_flag IS NULL OR bl_randomised.col_flag ='0')) AS hh_randomized,
        (SELECT COUNT (DISTINCT f.hhid) FROM  Form AS f WHERE f.dist_id= c.dist_id AND f.a101 = c.cluster_no AND username NOT IN ('user0113','user0252') AND c.cluster_no NOT LIKE '999%' ) AS hh_collected";


        $sql->select(DB::raw($select));

        if (isset($searchdata['type']) && $searchdata['type'] == 'c') {

            $sql->whereRaw("(SELECT COUNT (DISTINCT f.hhid) FROM  Form AS f WHERE f.dist_id= c.dist_id AND f.a101 = c.cluster_no AND
            username NOT IN ('user0113','user0252')
            AND c.cluster_no NOT LIKE '999%' ) >= 20");
        } elseif (isset($searchdata['type']) && $searchdata['type'] == 'i') {
            $sql->whereRaw("c.randomized = '1'");
            $sql->whereRaw("(SELECT COUNT (DISTINCT f.hhid) FROM  Form AS f WHERE f.dist_id= c.dist_id AND f.a101 = c.cluster_no
             AND username NOT LIKE ('user%')
             AND c.cluster_no NOT LIKE '999%' ) < 20");

        } elseif (isset($searchdata['type']) && $searchdata['type'] == 'r') {
            $sql->whereRaw("c.randomized = '1'");
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
        $sql->where('c.cluster_no', 'NOT LIKE', '999%');
       // $sql->where('c.cluster_no','2180462');
        $sql->groupBy('c.dist_id', 'c.district', 'c.tehsil', 'c.cluster_no');
        $sql->orderBy('c.dist_id', 'ASC');
        $data = $sql->get();

        return $data;
    }

    public static function get_dc_forms($searchdata)
    {
        $sql = DB::connection('endline_survey')->table('Form');
        $select = "DISTINCT hhid,a101 as cluster_code,MIN (istatus) AS istatus";

        $sql->select(DB::raw($select));


        if (isset($searchdata['district']) && $searchdata['district'] != '') {
            $dist = $searchdata['district'];
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->whereRaw("dist_id = '" . trim($d) . "'");
                }
            });
        }
        /*   $sql->where(function ($query) {
               $query->where('col_flag')
                   ->orWhere('col_flag', '=', '0') ;
           });*/
        $sql->where(function ($query) {
            $query->whereNotIn('username', ['dmu@aku', 'user0001', 'user0002', 'test1234', 'afg12345', 'user0113', 'user0123', 'user0211', 'user0234', 'user0252', 'user0414', 'user0432', 'user0434'])
                ->orWhere('username');
        });
        $sql->whereRaw("istatus !='' ");
       // $sql->where('a101','2180462');
        $sql->groupBy('a101', 'hhid');
        $sql->orderBy('a101', 'ASC');
        $sql->orderBy('hhid', 'ASC');
        $data = $sql->get();
        return $data;
    }

    public static function get_dc_forms_status($searchdata)
    {
        $sql = DB::connection('endline_survey')->table('Form as f');
        $select = "DISTINCT f.hhid,f.a101 as cluster_code,MIN (f.istatus) AS istatus,f.istatus96x AS Other_Status,c.district,c.tehsil";

        $sql->select(DB::raw($select))->leftJoin('clusters as c', 'f.a101', '=', 'c.cluster_no');
        if (isset($searchdata['district']) && $searchdata['district'] != '') {
            $dist = $searchdata['district'];
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->whereRaw("f.dist_id = '" . trim($d) . "'");
                }
            });
        }
        if (isset($searchdata['cluster']) && $searchdata['cluster'] != '') {
            $cluster = trim($searchdata['cluster']);
            $sql->where('f.a101', '=', $cluster);
        }
        if (isset($searchdata['type']) && $searchdata['type'] != '' && $searchdata['type'] == 'c') {
            $sql->where('istatus', '=', 1);
        } elseif (isset($searchdata['type']) && $searchdata['type'] != '' && $searchdata['type'] == 'not_c') {
            $sql->where('istatus', '!=', 1);
        }
        /*   $sql->where(function ($query) {
               $query->where('col_flag')
                   ->orWhere('col_flag', '=', '0') ;
           });*/
        $sql->where(function ($query) {
            $query->whereNotIn('f.username', ['dmu@aku', 'user0001', 'user0002', 'test1234', 'afg12345', 'user0113', 'user0123', 'user0211', 'user0234', 'user0252', 'user0414', 'user0432', 'user0434'])
                ->orWhere('f.username');
        });
       // $sql->whereRaw("f.istatus !='' ");
       // $sql->whereRaw("not EXISTS ( select * from FORM where a101=f.a101 and f.hhid=hhid and istatus=1)");
        $sql->groupBy('f.a101', 'f.hhid', 'c.district', 'c.tehsil','f.istatus96x');
        $sql->orderBy('f.a101', 'ASC');
        $sql->orderBy('f.hhid', 'ASC');
        $data = $sql->get();
        return $data;
    }

    public static function get_visited_count($searchdata)
    {
        $sql = DB::connection('endline_survey')->table('Form');
        $select = "DISTINCT clusterCode,hhid,COUNT(*) visit_count";
        $sql->select(DB::raw($select));
        $sql->where('clusterCode', '=', trim($searchdata['cluster']));
        $sql->groupBy('clusterCode', 'hhid');
        $sql->orderBy('hhid', 'ASC');

        $data = $sql->get();
        return $data;
    }

    public static function get_Not_visited_count_by_cluster($hh02Values)
    {
        return DB::connection('endline_survey')->table('bl_randomised as BL')
            ->select('BL.hh02', DB::raw('COUNT(*) as count'))
            ->whereIn('BL.hh02', $hh02Values)
            ->where(function ($query) {
                $query->where('col_flag', 0)->orWhereNull('col_flag');
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('Form as FO')
                    ->whereColumn('BL.hh02', 'FO.clusterCode')
                    ->whereColumn('BL.hhno', 'FO.hhid');
            })
            ->groupBy('BL.hh02')
            ->havingRaw('COUNT(*) < 20')
            ->get()->toArray();
    }

    public static function notVisit_detail($searchdata)
    {
        return DB::connection('endline_survey')->table('clusters as c')
            ->select('bl.hhno','district','tehsil','cluster_no')
            ->join('bl_randomised as bl','bl.hh02','=','c.cluster_no')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('Form as FO')
                    ->whereColumn('BL.hh02', 'FO.clusterCode')
                    ->whereColumn('BL.hhno', 'FO.hhid');
            })
            ->where('c.cluster_no', $searchdata['cluster'])->get()->toArray();
    }

}
