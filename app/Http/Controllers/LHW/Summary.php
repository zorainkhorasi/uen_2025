<?php

namespace App\Http\Controllers\LHW;

use App\Http\Controllers\Controller;
use App\Models\Custom_Model;
use App\Models\LHW\Filters_Modal;
use App\Models\LHW\LHW_App_Users_Model;
use App\Models\LHW\Summary_Model;
use App\Models\Settings_Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Summary extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = array();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'lhw_main_summary');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Summary",
            "action" => "View LHW Main Summary -> Function: Summary/index()",
            "PostData" => "",
            "affectedKey" => "",
            "idUser" => Auth::user()->id,
            "username" => Auth::user()->username,
        );
        /*==========Log=============*/
        if ($data['permission'][0]->CanView == 1) {
            $data['district_slug'] = (isset($_GET['d']) && $_GET['d'] != '' ? $_GET['d'] : 0);
            $data['tehsil_slug'] = (isset($_GET['t']) && $_GET['t'] != '' ? $_GET['t'] : 0);
            $data['hfc_slug'] = (isset($_GET['h']) && $_GET['h'] != '' ? $_GET['h'] : 0);
            $data['lhw_slug'] = (isset($_GET['l']) && $_GET['l'] != '' ? $_GET['l'] : 0);

            $searchFilter = array();
            $searchFilter['dist'] = $data['district_slug'];
            $searchFilter['tehsil'] = $data['tehsil_slug'];
            $searchFilter['hfc'] = $data['hfc_slug'];
            $searchFilter['lhw'] = $data['lhw_slug'];

            $data["districts"] = LHW_App_Users_Model::getDistricts();
            $res = array();
            $data["getMainSummary"] = Summary_Model::getMainSummary($searchFilter);
            foreach ($data["getMainSummary"] as $k => $v) {
                $key = $v->dist_id;
                if (isset($searchFilter['dist']) && $searchFilter['dist'] != '' && $searchFilter['dist'] != 0 &&
                    (!isset($searchFilter['tehsil']) || $searchFilter['tehsil'] == '' || $searchFilter['tehsil'] == 0)) {
                    $key = $v->tehsil_id;
                    $res[$key]['district'] = (isset($v->tehsil) && $v->tehsil != '' ? $v->tehsil : '');
                } else if (isset($searchFilter['tehsil']) && $searchFilter['tehsil'] != '' && $searchFilter['tehsil'] != 0 &&
                    (!isset($searchFilter['hfc']) || $searchFilter['hfc'] == '' || $searchFilter['hfc'] == 0)) {
                    $key = $v->hfcode;
                    $res[$key]['district'] = (isset($v->hf_name) && $v->hf_name != '' ? $v->hf_name : '');
                } else {
                    $res[$key]['district'] = (isset($v->district) && $v->district != '' ? $v->district : '');
                }
                $res[$key]['dist_id'] = $v->dist_id;
                $res[$key]['total_tehsil'] = (isset($v->total_tehsil) && $v->total_tehsil != '' ? $v->total_tehsil : 0);
                $res[$key]['total_hfc'] = (isset($v->total_hfc) && $v->total_hfc != '' ? $v->total_hfc : 0);
                $res[$key]['contacted_hfc'] = 0;
                $res[$key]['percent_hfc'] = 0;
                $res[$key]['total_lhw'] = (isset($v->total_lhw) && $v->total_lhw != '' ? $v->total_lhw : 0);
                $res[$key]['contacted_lhw'] = 0;
                $res[$key]['percent_lhw'] = 0;
            }

            $data["getMainActivitySummary"] = Summary_Model::getMainActivitySummary($searchFilter);

            foreach ($data["getMainActivitySummary"] as $k => $v) {
                $key_a = $v->dist_id;
                if (isset($searchFilter['dist']) && $searchFilter['dist'] != '' && $searchFilter['dist'] != 0 &&
                    (!isset($searchFilter['tehsil']) || $searchFilter['tehsil'] == '' || $searchFilter['tehsil'] == 0)) {
                    $key_a = $v->tehsil_id;
                } else if (isset($searchFilter['tehsil']) && $searchFilter['tehsil'] != '' && $searchFilter['tehsil'] != 0 &&
                    (!isset($searchFilter['hfc']) || $searchFilter['hfc'] == '' || $searchFilter['hfc'] == 0)) {
                    $key_a = $v->hfcode;
                }

                $res[$key_a]['contacted_hfc'] = (isset($v->contacted_hfc) && $v->contacted_hfc != '' ? $v->contacted_hfc : 0);
                $res[$key_a]['percent_hfc'] = Round(($res[$key_a]['contacted_hfc'] / $res[$key_a]['total_hfc']) * 100, 2);
                $res[$key_a]['contacted_lhw'] = (isset($v->contacted_lhw) && $v->contacted_lhw != '' ? $v->contacted_lhw : 0);
                $res[$key_a]['percent_lhw'] = Round(($res[$key_a]['contacted_lhw'] / $res[$key_a]['total_lhw']) * 100, 2);
            }
            /* echo '<pre>';
             print_r($res);
             echo '<pre>';
             exit();*/
            $data["result"] = $res;
            $trackarray["mainResult"] = "Success";
            $trackarray["result"] = "View Success";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('lhw.summary', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }

    public function changeDistrict(Request $request)
    {
        $id = $request->input('district');
        $getData = Filters_Modal::getTehsilByDistrict($id);
        return json_encode($getData);
    }

    public function changeTehsil(Request $request)
    {
        $id = $request->input('tehsil');
        $getData = Filters_Modal::getHFByTehsil($id);
        return json_encode($getData);
    }


}
