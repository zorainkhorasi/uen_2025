<?php

namespace App\Http\Controllers\LHW;

use App\Http\Controllers\Controller;
use App\Models\Custom_Model;
use App\Models\LHW\HH_Verified_Model;
use App\Models\LHW\Household_Summary_Model;
use App\Models\LHW\Lhw_App_Users_Model;
use App\Models\Settings_Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HH_Verified extends Controller
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
            "activityName" => "HH_Verified",
            "action" => "View HH Verified Summary -> Function: HH_Verified/index()",
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

            $searchFilter=array();
            $searchFilter['dist']=$data['district_slug'];
            $searchFilter['tehsil']=$data['tehsil_slug'];
            $searchFilter['hfc']=$data['hfc_slug'];
            $searchFilter['lhw']=$data['lhw_slug'];

            $data["districts"] = LHW_App_Users_Model::getDistricts();
            $data["getMainSummary"] = HH_Verified_Model::getData($searchFilter);

            $trackarray["mainResult"] = "Success";
            $trackarray["result"] = "View Success";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('lhw.hh_verified', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }
}
