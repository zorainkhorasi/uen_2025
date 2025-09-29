<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\AD\Health_Care_Providers_Model;
use App\Models\Custom_Model;
use App\Models\LHW\Filters_Modal;
use App\Models\Settings_Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Health_Care_Providers extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = array();
        $data["districts"] = Health_Care_Providers_Model::getDistricts();
        $data["data"] = Health_Care_Providers_Model::getAllData();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'Health_care_providers');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Health_care_providers",
            "action" => "View Health_care_providers -> Function: Health_care_providers/index()",
            "PostData" => "",
            "affectedKey" => "",
            "idUser" => Auth::user()->id,
            "username" => Auth::user()->username,
        );
        /*==========Log=============*/
        if ($data['permission'][0]->CanView == 1) {
            $trackarray["mainResult"] = "Success";
            $trackarray["result"] = "View Success";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('ad.health_care_providers', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }

    public function addHC_provider(Request $request)
    {
        $array = array();
        $array['province'] = $request->input('province');
        $array['pro_id'] = $request->input('pro_id');
        $array['district'] = $request->input('district');
        $array['dist_id'] = $request->input('dist_id');
        $array['tehsil'] = $request->input('tehsil');
        $array['tehsil_id'] = $request->input('tehsil_id');
        $array['hf_name'] = $request->input('hf_name');
        $array['hfcode'] = $request->input('hfcode');
        $array['hf_type'] = $request->input('hf_type');
        $array['provider_name'] = $request->input('provider_name');
        $array['colflag'] = 0;
        $getMaxProviderCode = Health_care_providers_Model::getMaxProviderCode($array['hfcode']);

        $maxProviderCode = '01';
        if (isset($getMaxProviderCode[0]->max_provider_code) && $getMaxProviderCode[0]->max_provider_code != '') {
            $maxProviderCode = (int)$getMaxProviderCode[0]->max_provider_code + 1;
        } else {
            $maxProviderCode = $array['hfcode'] . '01';
        }
        $array['provider_code'] = (int)$maxProviderCode;
//        $array['createdBy'] = auth()->id();
//        $array['createdDateTime'] = date('Y-m-d H:i:s');
        $checkName = Health_care_providers_Model::checkName($array['provider_name']);
        if (count($checkName) == 0) {
            if (DB::connection('sqlsrv_uen_ph2')->table('healthcare_providers')->insert($array)) {
                $result = array('Success', 'Successfully Inserted', 'success');
            } else {
                $result = array('Error', 'Something went wrong in inserting data', 'danger');
            }
        } else {
            $result = array('Error', 'Provider Name already exist', 'danger');
        }
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Health_care_providers",
            "action" => "Add Health_care_providers -> Function: AD/Health_care_providers/addHC_provider()",
            "mainResult" => $result[0],
            "result" => $result[1],
            "PostData" => $array,
            "affectedKey" => 'id',
            "idUser" => Auth::user()->id,
            "username" => Auth::user()->username,
        );
        Custom_Model::trackLogs($trackarray, "all_logs");
        /*==========Log=============*/

        return json_encode($result);
    }

    public function deleteHC_provider(Request $request)
    {
        $id = $request->input('id');
        $array = array();
        if (isset($id) && $id != '') {

            $array['colflag'] = 1;
//            $array['deleteBy'] = auth()->id();
//            $array['deletedDateTime'] = date('Y-m-d H:i:s');
            $updateQuery = DB::connection('sqlsrv_uen_ph2')->table('healthcare_providers')
                ->where('id', $id)
                ->update($array);
            if ($updateQuery) {
                $result = array('Success', 'Successfully Deleted', 'success');
            } else {
                $result = array('Error', 'Something went wrong in deleting data', 'danger');
            }
        } else {
            $result = array('Error', 'Invalid User Id', 'danger');
        }
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Health_care_providers",
            "action" => "Delete Health_care_providers -> Function: AD/Health_care_providers/deleteHC_provider()",
            "mainResult" => $result[0],
            "result" => $result[1],
            "PostData" => $array,
            "affectedKey" => 'id=' . $id,
            "idUser" => Auth::user()->id,
            "username" => Auth::user()->username,
        );
        Custom_Model::trackLogs($trackarray, "all_logs");
        /*==========Log=============*/

        return json_encode($result);
    }
}
