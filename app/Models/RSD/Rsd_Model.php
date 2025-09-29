<?php

namespace App\Models\RSD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rsd_Model extends Model
{
    use HasFactory;

    protected $table = 'users_app';

    public static function getRsdData($searchdata)
    {
        //        $sql->whereIn(DB::raw(' SUBSTRING ( facility_id, 1, 3 )'), [113, 123]);

        $start = 0;
        $length = 500000;
        $sql = DB::connection('sqlsrv_uen_ph2')->table('HealthFacility')->select(DB::raw('*'))->distinct();
        if (isset($searchdata['province']) && $searchdata['province'] != '' && $searchdata['province'] != null) {
            $sql->where('province', '=', $searchdata['province']);
        }
        if (isset($searchdata['type']) && $searchdata['type'] != '' && $searchdata['type'] != null) {
            $sql->where('hf_type', '=', $searchdata['type']);
        }
        if (isset($searchdata['search']) && $searchdata['search'] != '' && $searchdata['search'] != null && $searchdata['search'] != ' ') {
            $search = trim($searchdata['search']);
            $sql->where(function ($query) use ($search) {
                $query->where('province', 'like', '%' . $search . '%')
                    ->orWhere('district', 'like', '%' . $search . '%')
                    ->orWhere('tehsil', 'like', '%' . $search . '%')
                    ->orWhere('uc_name', 'like', '%' . $search . '%')
                    ->orWhere('uc_id', 'like', '%' . $search . '%')
                    ->orWhere('hf_name', 'like', '%' . $search . '%')
                    ->orWhere('hfcode', 'like', '%' . $search . '%');
            });
        }

        if (isset($searchdata['start']) && $searchdata['start'] != '' && $searchdata['start'] != null) {
            $start = $searchdata['start'];
        }
        if (isset($searchdata['length']) && $searchdata['length'] != '' && $searchdata['length'] != null) {
            $length = $searchdata['length'];
        }
        if (isset($searchdata['orderby']) && $searchdata['orderby'] != '' && $searchdata['orderby'] != null) {
            $sql->orderBy($searchdata['orderby'], $searchdata['ordersort']);
        }

        $sql->offset($start)->limit($length);
        $data = $sql->get();
        return $data;
    }

    public static function getCntRsdData($searchdata)
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('HealthFacility')->select(DB::raw('*'))->distinct();
        if (isset($searchdata['province']) && $searchdata['province'] != '' && $searchdata['province'] != null) {
            $sql->where('province', '=', $searchdata['province']);
        }
        if (isset($searchdata['type']) && $searchdata['type'] != '' && $searchdata['type'] != null) {
            $sql->where('hf_type', '=', $searchdata['type']);
        }
        if (isset($searchdata['search']) && $searchdata['search'] != '' && $searchdata['search'] != null && $searchdata['search'] != ' ') {
            $search = trim($searchdata['search']);
            $sql->where(function ($query) use ($search) {
                $query->where('province', 'like', '%' . $search . '%')
                    ->orWhere('district', 'like', '%' . $search . '%')
                    ->orWhere('tehsil', 'like', '%' . $search . '%')
                    ->orWhere('uc_name', 'like', '%' . $search . '%')
                    ->orWhere('uc_id', 'like', '%' . $search . '%')
                    ->orWhere('hf_name', 'like', '%' . $search . '%')
                    ->orWhere('hfcode', 'like', '%' . $search . '%');
            });
        }
        $data = $sql->get();
        return $data;
    }

    /*=====================Facility Detail=====================*/

    public static function getRsd_facilityData($searchdata)
    {
        $start = 0;
        $length = 500000;
        $sql = DB::connection('sqlsrv_uen_ph2')->table('formRSD')->select(DB::raw("FormRSD.hfCode,
	FormRSD.hfName,
	HealthFacility.hf_type,
	FormRSD.districtCode,
	FormRSD.districtName,
	FormRSD.reportingMonth,
	SUBSTRING ( reportingMonth, PATINDEX( '%[0-9]%', reportingMonth ), LEN( reportingMonth ) ) AS YEAR,
	(
	CASE WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'jan' THEN
			1
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'feb' THEN
			2
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'mar' THEN
			3
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'apr' THEN
			4
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'may' THEN
			5
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'jun' THEN
			6
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'jul' THEN
			7
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'aug' THEN
			8
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'sep' THEN
			9
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'oct' THEN
			10
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'nov' THEN
			11
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'dec' THEN
			12 ELSE 13
		END
		) AS MONTH"))
            ->leftJoin('HealthFacility', 'FormRSD.hfCode', '=', 'HealthFacility.hfcode');
        if (isset($searchdata['hfCode']) && $searchdata['hfCode'] != '' && $searchdata['hfCode'] != null) {
            $sql->where('FormRSD.hfCode', '=', $searchdata['hfCode']);
        }
        if (isset($searchdata['search']) && $searchdata['search'] != '' && $searchdata['search'] != null && $searchdata['search'] != ' ') {
            $search = trim($searchdata['search']);
            $sql->where(function ($query) use ($search) {
                $query->where('FormRSD.hfCode', 'like', '%' . $search . '%')
                    ->orWhere('FormRSD.hfName', 'like', '%' . $search . '%')
                    ->orWhere('FormRSD.districtCode', 'like', '%' . $search . '%')
                    ->orWhere('FormRSD.districtName', 'like', '%' . $search . '%')
                    ->orWhere('FormRSD.reportingMonth', 'like', '%' . $search . '%');
            });
        }

        if (isset($searchdata['start']) && $searchdata['start'] != '' && $searchdata['start'] != null) {
            $start = $searchdata['start'];
        }
        if (isset($searchdata['length']) && $searchdata['length'] != '' && $searchdata['length'] != null) {
            $length = $searchdata['length'];
        }
        $sql->groupBy('FormRSD.hfCode',
            'FormRSD.hfName',
            'FormRSD.districtCode',
            'HealthFacility.hf_type',
            'FormRSD.districtName',
            'FormRSD.reportingMonth');
        if (isset($searchdata['orderby']) && $searchdata['orderby'] != '' && $searchdata['orderby'] != null) {
            $sql->orderBy(DB::raw("cast(SUBSTRING(reportingMonth, PATINDEX('%[0-9]%', reportingMonth), LEN(reportingMonth)) as int) , MONTH "), 'asc');
        }

        $sql->offset($start)->limit($length);
        $data = $sql->get();
        return $data;
    }

    public static function getCntRsd_facilityData($searchdata)
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('formRSD')->select(DB::raw('FormRSD.hfCode,
	FormRSD.hfName,
	FormRSD.districtCode,
	HealthFacility.hf_type,
	FormRSD.districtName,
	FormRSD.reportingMonth'))->leftJoin('HealthFacility', 'FormRSD.hfCode', '=', 'HealthFacility.hfcode');
        if (isset($searchdata['hfCode']) && $searchdata['hfCode'] != '' && $searchdata['hfCode'] != null) {
            $sql->where('FormRSD.hfCode', '=', $searchdata['hfCode']);
        }
        if (isset($searchdata['search']) && $searchdata['search'] != '' && $searchdata['search'] != null && $searchdata['search'] != ' ') {
            $search = trim($searchdata['search']);
            $sql->where(function ($query) use ($search) {
                $query->where('FormRSD.hfCode', 'like', '%' . $search . '%')
                    ->orWhere('FormRSD.hfName', 'like', '%' . $search . '%')
                    ->orWhere('FormRSD.districtCode', 'like', '%' . $search . '%')
                    ->orWhere('FormRSD.districtName', 'like', '%' . $search . '%')
                    ->orWhere('FormRSD.reportingMonth', 'like', '%' . $search . '%');
            });
        }
        $sql->groupBy('FormRSD.hfCode',
            'FormRSD.hfName',
            'FormRSD.districtCode',
            'HealthFacility.hf_type',
            'FormRSD.districtName',
            'FormRSD.reportingMonth');
        $data = $sql->get();
        return $data;
    }

    /*=====================month comparion Detail=====================*/
    public static function getRsd_comparisonData($searchdata)
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('formRSD')->select(DB::raw("*"));
        if (isset($searchdata['hfCode']) && $searchdata['hfCode'] != '' && $searchdata['hfCode'] != null) {
            $sql->where('FormRSD.hfCode', '=', $searchdata['hfCode']);
        }
        if (isset($searchdata['date']) && $searchdata['date'] != '' && $searchdata['date'] != null) {
            $exp = explode('-', $searchdata['date']);
            $sql->where(DB::raw("(
	CASE

			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'jan' THEN
			1
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'feb' THEN
			2
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'mar' THEN
			3
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'apr' THEN
			4
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'may' THEN
			5
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'jun' THEN
			6
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'jul' THEN
			7
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'aug' THEN
			8
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'sep' THEN
			9
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'oct' THEN
			10
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'nov' THEN
			11
			WHEN LOWER ( SUBSTRING ( reportingMonth, 0, CHARINDEX( '-', reportingMonth ) ) ) = 'dec' THEN
			12 ELSE 12
	END
	) "), '=', $exp[0]);
            $sql->where(DB::raw("cast(SUBSTRING(reportingMonth, PATINDEX('%[0-9]%', reportingMonth), LEN(reportingMonth)) as int) "), '=', $exp[1]);
        }
        $data = $sql->get();
        return $data;
    }

    public static function getPhcReportData($searchdata, $table)
    {
        $sql = DB::connection('sqlsrv_phcreport')->table($table)->select(DB::raw("*"));
        $sql->where('facility_id', '=', $searchdata['hfCode']);
        if (isset($searchdata['date']) && $searchdata['date'] != '' && $searchdata['date'] != null) {
            $exp = explode('-', $searchdata['date']);
            if ($exp[0] == '1') {
                $m = 'January';
            } elseif ($exp[0] == '2') {
                $m = 'February';
            } elseif ($exp[0] == '3') {
                $m = 'March';
            } elseif ($exp[0] == '4') {
                $m = 'April';
            } elseif ($exp[0] == '5') {
                $m = 'May';
            } elseif ($exp[0] == '6') {
                $m = 'June';
            } elseif ($exp[0] == '7') {
                $m = 'July';
            } elseif ($exp[0] == '8') {
                $m = 'August';
            } elseif ($exp[0] == '9') {
                $m = 'September';
            } elseif ($exp[0] == '10') {
                $m = 'October';
            } elseif ($exp[0] == '11') {
                $m = 'November';
            } elseif ($exp[0] == '12') {
                $m = 'December';
            }
//            $exp[1]='2018';
            $my = $m . '-' . $exp[1];
            $sql->where('month_year', '=', $my);
        }
        $data = $sql->get();
        return $data;
    }

    public static function getRsdLog($searchdata)
    {
        $sql = DB::connection('sqlsrv_uen_ph2')->table('FormRSD_edit')->select('*');
        $sql->where('FormRSD_edit.hfCode', '=', $searchdata['hfCode']);
        if (isset($searchdata['date']) && $searchdata['date'] != '' && $searchdata['date'] != null) {
            $exp = explode('-', $searchdata['date']);
            if ($exp[0] == '1') {
                $m = 'January';
            } elseif ($exp[0] == '2') {
                $m = 'Feb';
            } elseif ($exp[0] == '3') {
                $m = 'Mar';
            } elseif ($exp[0] == '4') {
                $m = 'Apr';
            } elseif ($exp[0] == '5') {
                $m = 'May';
            } elseif ($exp[0] == '6') {
                $m = 'Jun';
            } elseif ($exp[0] == '7') {
                $m = 'Jul';
            } elseif ($exp[0] == '8') {
                $m = 'Aug';
            } elseif ($exp[0] == '9') {
                $m = 'Sep';
            } elseif ($exp[0] == '10') {
                $m = 'Oct';
            } elseif ($exp[0] == '11') {
                $m = 'Nov';
            } elseif ($exp[0] == '12') {
                $m = 'Dec';
            }
            $my = $m . '-' . $exp[1];
            $sql->where('FormRSD_edit.reportingMonthYear', '=', $my);
        }
        $data = $sql->get();
        return $data;
    }

}
