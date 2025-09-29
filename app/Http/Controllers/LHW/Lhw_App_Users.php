<?php

namespace App\Http\Controllers\LHW;

use App\Http\Controllers\Controller;
use App\Models\Custom_Model;
use App\Models\LHW\LHW_App_Users_Model;
use App\Models\Settings_Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Lhw_App_Users extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = array();
        $data["districts"] = LHW_App_Users_Model::getDistricts();
        $data["data"] = LHW_App_Users_Model::getAllData();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'lhw_app_users');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Lhw_App_Users",
            "action" => "View Lhw_App_Users -> Function: Lhw_App_Users/index()",
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
            return view('lhw.lhw_app_users', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }


    public function addAppUsers(Request $request)
    {
        $newPassword = $request->input('userPassword');
        $salt = openssl_random_pseudo_bytes(16);
        $userPasswordenc = Custom_Model::genPassword($newPassword, $salt, 'sha1');

        $array = array();
        $array['full_name'] = $request->input('fullName');
        $array['username'] = $request->input('userName');
        $array['password'] = $request->input('userPassword');
        $array['passwordenc'] = $userPasswordenc;
        $array['pwdExpiry'] = date('Y-m-d', strtotime('+90 days'));
        $array['dist_id'] = $request->input('district');
        $array['designation'] = $request->input('designation');
        $array['enabled'] = 1;
        $array['isNewUser'] = 1;
        $array['colflag'] = 0;
        $array['attempt'] = 0;
        $array['createdBy'] = auth()->id();
        $array['createdDateTime'] = date('Y-m-d H:i:s');
        $checkName = LHW_App_Users_Model::checkName($array['username']);
        if (count($checkName) == 0) {
            if (DB::connection('sqlsrv_uen_ph2')->table('AppUser')->insert($array)) {
                $result = array('Success', 'Successfully Inserted', 'success');
            } else {
                $result = array('Error', 'Something went wrong in inserting data', 'danger');
            }
        } else {
            $result = array('Error', 'User Name already exist', 'danger');
        }
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Lhw_App_Users",
            "action" => "Add Lhw_App_Users -> Function: Lhw_App_Users/addAppUsers()",
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

    public function getUserData(Request $request)
    {
        $id = $request->input('id');
        $getUserData = LHW_App_Users_Model::getUserDetails($id);
        return json_encode($getUserData);
    }

    public function editAppUsers(Request $request)
    {
        $id = $request->input('id');
        $array = array();
        if (isset($id) && $id != '') {
            $array['full_name'] = $request->input('fullName');
            $array['dist_id'] = $request->input('district');
            $array['designation'] = $request->input('designation');
            $array['attempt'] = 0;
            $array['updateBy'] = auth()->id();
            $array['updatedDateTime'] = date('Y-m-d H:i:s');
            $updateQuery = DB::connection('sqlsrv_uen_ph2')->table('AppUser')
                ->where('id', $id)
                ->update($array);
            if ($updateQuery) {
                $result = array('Success', 'Successfully Edited', 'success');
            } else {
                $result = array('Error', 'Something went wrong in editing data', 'danger');
            }
        } else {
            $result = array('Error', 'Invalid User Id', 'danger');
        }
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Lhw_App_Users",
            "action" => "Edit Lhw_App_Users -> Function: Lhw_App_Users/editAppUsers()",
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

    public function resetPwd(Request $request)
    {
        $id = $request->input('id');
        $array = array();
        if (isset($id) && $id != '' && isset($_POST['userPassword']) && $_POST['userPassword'] != '') {
            $newPassword = $request->input('userPassword');
            $salt = openssl_random_pseudo_bytes(16);
            $userPasswordenc = Custom_Model::genPassword($newPassword, $salt, 'sha1');
            $array['password'] = $request->input('userPassword');
            $array['passwordenc'] = $userPasswordenc;
            $array['pwdExpiry'] = date('Y-m-d', strtotime('+90 days'));
            $array['isNewUser'] = 1;
            $array['attempt'] = 0;
            $array['updateBy'] = auth()->id();
            $array['updatedDateTime'] = date('Y-m-d H:i:s');
            $updateQuery = DB::connection('sqlsrv_uen_ph2')->table('AppUser')
                ->where('id', $id)
                ->update($array);
            if ($updateQuery) {
                $result = array('Success', 'Successfully Edited', 'success');
            } else {
                $result = array('Error', 'Something went wrong in editing data', 'danger');
            }
        } else {
            $result = array('Error', 'Invalid User', 'danger');
        }
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Lhw_App_Users",
            "action" => "Edit Lhw_App_Users -> Function: Lhw_App_Users/resetPwd()",
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

    public function editAppUsers_gr_()
    {
        $getAllData = LHW_App_Users_Model::getAllData();
        foreach ($getAllData as $k => $u) {
            $id = $u->id;
            $newPassword = $u->password;
            $array = array();
            $salt = openssl_random_pseudo_bytes(16);
            $userPasswordenc = Custom_Model::genPassword($newPassword, $salt, 'sha1');
            $array['passwordenc'] = $userPasswordenc;
            $array['pwdExpiry'] = date('Y-m-d', strtotime('+90 days'));
            $array['isNewUser'] = 1;
            $array['attempt'] = 0;
            $array['updateBy'] = auth()->id();
            $array['updatedDateTime'] = date('Y-m-d H:i:s');
           /* $updateQuery = DB::connection('sqlsrv_uen_ph2')->table('AppUser')
                ->where('id', $id)
                ->update($array);
            if ($updateQuery) {
                $result = array('Success', 'Successfully Edited', 'success');
            } else {
                $result = array('Error', 'Something went wrong in editing data', 'danger');
            }*/

            /*==========Log=============*/
            $trackarray = array(
                "activityName" => "Lhw_App_Users",
                "action" => "Edit Lhw_App_Users -> Function: Lhw_App_Users/editAppUsers()",
                "mainResult" => $result[0],
                "result" => $result[1],
                "PostData" => $array,
                "affectedKey" => 'id=' . $id,
                "idUser" => Auth::user()->id,
                "username" => Auth::user()->username,
            );
            Custom_Model::trackLogs($trackarray, "all_logs");
            /*==========Log=============*/
        }

        return json_encode($result);
    }

    public function deleteAppUsers(Request $request)
    {
        $id = $request->input('id');
        $array = array();
        if (isset($id) && $id != '') {

            $array['colflag'] = 1;
            $array['enabled'] = 0;
            $array['deleteBy'] = auth()->id();
            $array['deletedDateTime'] = date('Y-m-d H:i:s');
            $updateQuery = DB::connection('sqlsrv_uen_ph2')->table('AppUser')
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
            "activityName" => "Lhw_App_Users",
            "action" => "Delete Lhw_App_Users -> Function: Lhw_App_Users/deleteAppUsers()",
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
