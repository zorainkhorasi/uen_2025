<?php

namespace App\Models\HFA_RS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Hfa_Summary_Model extends Model
{
    use HasFactory;

    protected $table = 'HealthFacility';

    public static function getHealthFacility($searchdata, $hf_type)
    {
        $sql = DB::connection('UeN_HFA_EL')->table('HealthFacility');

       /* if (isset($searchdata['pageLevel']) && $searchdata['pageLevel'] != '' && $searchdata['pageLevel'] == '2') {
            $sql->select(DB::raw('dist_id as fieldId,district as fieldName,COUNT (dist_id) AS totalCnt'));
            $sql->groupBy('dist_id', 'district');
            $sql->orderBy('dist_id', 'ASC');
        } else {
            $sql->select(DB::raw('pro_id as fieldId,province as fieldName,COUNT (pro_id) AS totalCnt'));
            $sql->groupBy('pro_id', 'province');
            $sql->orderBy('pro_id', 'ASC');
        }*/
        $sql->select(DB::raw('dist_id as fieldId,district as fieldName,COUNT (dist_id) AS totalCnt'));
        $sql->groupBy('dist_id', 'district');
        $sql->orderBy('dist_id', 'ASC');
        if (isset($hf_type) && $hf_type != '') {
            $sql->where('hf_type', '=', $hf_type);
        }
        if (isset($searchdata['pro_id']) && $searchdata['pro_id'] != '' && $searchdata['pro_id'] != '0') {
            $sql->where('pro_id', '=', $searchdata['pro_id']);
        }

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
            $query->where('colflag')
                ->orWhere('colflag', '=', '0');
        });

        $data = $sql->get();
        return $data;
    }

    public static function getHealthFacility_DC($searchdata, $hf_type)
    {
        $sql = DB::connection('UeN_HFA_EL')->table('Forms')->join('HealthFacility', 'Forms.hfCode', '=', 'HealthFacility.hfcode', 'inner');

        /*if (isset($searchdata['pageLevel']) && $searchdata['pageLevel'] != '' && $searchdata['pageLevel'] == '2') {
            $sql->select(DB::raw('HealthFacility.dist_id as fieldId,HealthFacility.district as fieldName,Count (DISTINCT HealthFacility.hfCode ) AS totalCnt'));
            $sql->groupBy('HealthFacility.dist_id','HealthFacility.district');
            $sql->orderBy('HealthFacility.dist_id', 'ASC');
        } else {
            $sql->select(DB::raw('HealthFacility.pro_id as fieldId,HealthFacility.province as fieldName,Count (DISTINCT HealthFacility.hfCode ) AS totalCnt'));
            $sql->groupBy('HealthFacility.pro_id', 'HealthFacility.province');
            $sql->orderBy('HealthFacility.pro_id', 'ASC');
        }*/
        $sql->select(DB::raw('HealthFacility.dist_id as fieldId,HealthFacility.district as fieldName,Count (DISTINCT HealthFacility.hfCode ) AS totalCnt'));
        $sql->groupBy('HealthFacility.dist_id','HealthFacility.district');
        $sql->orderBy('HealthFacility.dist_id', 'ASC');
        if (isset($hf_type) && $hf_type != '') {
            $sql->where('HealthFacility.hf_type', '=', $hf_type);
        }
        if (isset($searchdata['pro_id']) && $searchdata['pro_id'] != '' && $searchdata['pro_id'] != '0') {
            $sql->where('HealthFacility.pro_id', '=', $searchdata['pro_id']);
        }

        if (isset($searchdata['district']) && $searchdata['district'] != '') {
            $dist = $searchdata['district'];
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

        $data = $sql->get();
        return $data;
    }

    public static function getHealthFacility_detail($searchdata, $hf_type)
    {
      /*SELECT
	HealthFacility.dist_id,
	HealthFacility.district,
	HealthFacility.tehsil,
	HealthFacility.hf_name,
	HealthFacility.hf_type,
	HealthFacility.hfcode,
	( SELECT COUNT ( f.hfcode ) FROM forms f WHERE f.hfCode= HealthFacility.hfcode ) AS dc
FROM
	HealthFacility
WHERE
	( HealthFacility.colflag IS NULL OR HealthFacility.colflag = 0 )
	AND HealthFacility.dist_id = 113
ORDER BY
	HealthFacility.hfcode ASC*/
        $sql = DB::connection('UeN_HFA_EL')->table('HealthFacility');
        $sql->select(DB::raw('HealthFacility.dist_id,
	HealthFacility.district,
	HealthFacility.tehsil,
	HealthFacility.hf_name,
	HealthFacility.hf_type,
	HealthFacility.hfcode,
	( SELECT COUNT ( f.hfcode ) FROM forms f WHERE f.hfCode= HealthFacility.hfcode ) AS dc '));
        $sql->orderBy('HealthFacility.hfcode', 'ASC');
        if (isset($hf_type) && $hf_type != '') {
            $sql->where('HealthFacility.hf_type', '=', $hf_type);
        }
        if (isset($searchdata['dist_id']) && $searchdata['dist_id'] != '' && $searchdata['dist_id'] != '0') {
            $sql->where('HealthFacility.dist_id', '=', $searchdata['dist_id']);
        }

        if (isset($searchdata['district']) && $searchdata['district'] != '') {
            $dist = $searchdata['district'];
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

        $data = $sql->get();
        return $data;
    }
}
