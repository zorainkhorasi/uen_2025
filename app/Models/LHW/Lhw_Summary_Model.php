<?php

namespace App\Models\LHW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Lhw_Summary_Model extends Model
{
    use HasFactory;

    protected $table = 'LHWForm';

    public static function getLhwSummary($searchFilter)
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('LHWForm')->select(DB::raw("a101,
	a103 as hf_name,
	lhw.tehsil,
	lhw.hfcode,
	COUNT ( DISTINCT LHWForm.a104c ) AS total_lhw")) ->leftJoin('lhw', 'LHWForm.a104c', '=', 'lhw.lhw_code');
        $sql->groupBy('LHWForm.a101', 'LHWForm.a103','lhw.tehsil','lhw.hfcode');

        $sql->where('LHWForm.a101', 'Not like', '%test%');
        $sql->where('LHWForm.username', 'Not like', '%test%');
        $sql->where('LHWForm.username', 'Not like', '%moom8015%');
        $sql->where('LHWForm.username', 'Not like', '%user%');
        $sql->whereNotIn('LHWForm.col_id', [165, 145, 147, 138, 98]);
        $sql->where('lhw.dist_id', 'not like', '9%');
        $sql->where(function ($query) {
            $query->where('LHWForm.colflag')
                ->orWhere('LHWForm.colflag', '=', '0');
        });
        $sql->where(function ($query) {
            $query->where('lhw.colflag')
                ->orWhere('lhw.colflag', '=', '0');
        });


        if (isset(Auth::user()->district) && Auth::user()->district != '' && Auth::user()->district != '0') {
            $dist = Auth::user()->district;
            $sql->where(function ($query) use ($dist) {
                $exp_dist = explode(',', $dist);
                foreach ($exp_dist as $d) {
                    $query->orWhere('LHWForm.a104c', 'like', trim($d).'%');
                }
            });
        }

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


        $data = $sql->get();
        return $data;
    }

}
