<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SessionModel extends Model
{
    use HasFactory;
    // public static function getData(){
    //     return DB::table(DB::raw('(
    //         SELECT
    //             districtName,
    //             lhwCode,
    //             lhwName,
    //             COUNT(*) AS vhc_count,
    //             0 AS WSG_count
    //         FROM VHCForm
    //         WHERE districtCode != 999 AND col_flag IS NULL
    //         GROUP BY districtName, lhwCode, lhwName

    //         UNION ALL

    //         SELECT
    //             districtName,
    //             lhwCode,
    //             lhwName,
    //             0 AS vhc_count,
    //             COUNT(*) AS WSG_count
    //         FROM WSGForm
    //         WHERE districtCode != 999 AND col_flag IS NULL
    //         GROUP BY districtName, lhwCode, lhwName
    //     ) AS combined'))
    //     ->select([
    //         'districtName',
    //         'lhwCode',
    //         'lhwName',
    //         DB::raw('SUM(vhc_count) AS vhc_count'),
    //         DB::raw('SUM(WSG_count) AS WSG_count')
    //     ])
    //     ->groupBy('districtName', 'lhwCode', 'lhwName')
    //     ->get();
    // }

    public static function getData($searchFilter)
    {
        // Build VHC subquery
        $vhcQuery = DB::connection('sqlsrv_uen_ph2')->table('VHCForm')
            ->select('districtName', 'lhwCode', 'lhwName', DB::raw('COUNT(*) AS vhc_count'))
            ->where('districtCode', '!=', 999)
            ->whereNull('col_flag');
            if (isset($searchFilter['dist']) && $searchFilter['dist'] != '' && $searchFilter['dist'] != 0) {
                // Note: Adjust column names based on your actual district field in VHC/WSG tables
                $vhcQuery->where('districtCode', 'like', '%'.$searchFilter['dist'].'%');
            }
            if (isset($searchFilter['lhw']) && $searchFilter['lhw'] != '' && $searchFilter['lhw'] != 0) {
                     $vhcQuery->where('lhwCode', $searchFilter['lhw']);
            }
            $vhcQuery->groupBy('districtName', 'lhwCode', 'lhwName');

        // Build WSG subquery
        $wsgQuery = DB::connection('sqlsrv_uen_ph2')->table('WSGForm')
            ->select('districtName', 'lhwCode', 'lhwName', DB::raw('COUNT(*) AS WSG_count'))
            ->where('districtCode', '!=', 999)
            ->whereNull('col_flag');
            if (isset($searchFilter['dist']) && $searchFilter['dist'] != '' && $searchFilter['dist'] != 0) {
                // Note: Adjust column names based on your actual district field in VHC/WSG tables
                $wsgQuery->where('districtCode', 'like', '%'.$searchFilter['dist'].'%');
            }
            if (isset($searchFilter['lhw']) && $searchFilter['lhw'] != '' && $searchFilter['lhw'] != 0) {
                     $wsgQuery->where('lhwCode', $searchFilter['lhw']);
            }
            $wsgQuery->groupBy('districtName', 'lhwCode', 'lhwName');

        // Main query with FULL OUTER JOIN
        $sql = DB::connection('sqlsrv_uen_ph2')
            ->table(DB::raw("({$vhcQuery->toSql()}) as v"))
            ->mergeBindings($vhcQuery)
            ->select(
                DB::raw('COALESCE(v.districtName, w.districtName) AS districtName'),
                DB::raw('COALESCE(v.lhwCode, w.lhwCode) AS lhwCode'),
                DB::raw('COALESCE(v.lhwName, w.lhwName) AS lhwName'),
                DB::raw('ISNULL(v.vhc_count, 0) AS vhc_count'),
                DB::raw('ISNULL(w.WSG_count, 0) AS WSG_count')
            )
            ->rightJoin(DB::raw("({$wsgQuery->toSql()}) as w"), function($join) {
                $join->on('v.districtName', '=', 'w.districtName')
                    ->on('v.lhwCode', '=', 'w.lhwCode')
                    ->on('v.lhwName', '=', 'w.lhwName');
            })
            ->mergeBindings($wsgQuery)
            ->union(
                DB::connection('sqlsrv_uen_ph2')
                    ->table(DB::raw("({$vhcQuery->toSql()}) as v"))
                    ->mergeBindings($vhcQuery)
                    ->select(
                        DB::raw('COALESCE(v.districtName, w.districtName) AS districtName'),
                        DB::raw('COALESCE(v.lhwCode, w.lhwCode) AS lhwCode'),
                        DB::raw('COALESCE(v.lhwName, w.lhwName) AS lhwName'),
                        DB::raw('ISNULL(v.vhc_count, 0) AS vhc_count'),
                        DB::raw('ISNULL(w.WSG_count, 0) AS WSG_count')
                    )
                    ->leftJoin(DB::raw("({$wsgQuery->toSql()}) as w"), function($join) {
                        $join->on('v.districtName', '=', 'w.districtName')
                            ->on('v.lhwCode', '=', 'w.lhwCode')
                            ->on('v.lhwName', '=', 'w.lhwName');
                    })
                    ->mergeBindings($wsgQuery)
            );


        $data = $sql->get();
        // dd($data);
        return $data;
    }
}
