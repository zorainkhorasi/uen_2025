<?php

namespace App\Http\Controllers\HFA_RS;

use App\Http\Controllers\Controller;
use App\Models\Custom_Model;
use App\Models\HFA_RS\Hfa_App_Users_Model;
use App\Models\HFA_RS\Hfa_Summary_Model;
use App\Models\Settings_Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HFA_Summary extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $data = array();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'hfa_summary');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "HFA_Summary",
            "action" => "View HFA_Summary -> Function: HFA_Summary/index()",
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

            $searchdata = array();
            $searchdata['district'] = '';
            $searchdata['pageLevel'] = 1;
            if (isset($data['permission'][0]->CanViewAllDetail) && $data['permission'][0]->CanViewAllDetail != 1
                && isset(Auth::user()->district) && Auth::user()->district != 0) {
                $searchdata['district'] = Auth::user()->district;
            }

            /*==========Total=============*/
            $getHealthFacility = Hfa_Summary_Model::getHealthFacility($searchdata, '');
          //  echo  1;die;
            $overall_dist_array = array();
            $totalcluster = 0;
            foreach ($getHealthFacility as $k => $v) {
                $dist_id = $v->fieldId;
                $overall_dist_array[$dist_id]['fieldId'] = $v->fieldId;
                $overall_dist_array[$dist_id]['fieldName'] = $v->fieldName;
                $overall_dist_array[$dist_id]['tot']['count'] = $v->totalCnt;
                $overall_dist_array[$dist_id]['tot']['public'] = 0;
                $overall_dist_array[$dist_id]['tot']['private'] = 0;
                $overall_dist_array[$dist_id]['dc']['total'] = 0;
                $overall_dist_array[$dist_id]['dc']['public'] = 0;
                $overall_dist_array[$dist_id]['dc']['private'] = 0;
                $totalcluster += $overall_dist_array[$dist_id]['tot']['count'];
            }
            $data['total'] = $overall_dist_array;
            $data['totalcluster'] = $totalcluster;

            /*==========Public=============*/
            $getHealthFacility_Public = Hfa_Summary_Model::getHealthFacility($searchdata, 'Public');
            $totalcluster_Public = 0;
            foreach ($getHealthFacility_Public as $kk => $vv) {
                $dist_id_Public = $vv->fieldId;
                $data['total'][$dist_id_Public]['tot']['public'] = $vv->totalCnt;
                $totalcluster_Public += $vv->totalCnt;
            }
            $data['totalcluster_Public'] = $totalcluster_Public;

            /*==========Private=============*/
            $getHealthFacility_Private = Hfa_Summary_Model::getHealthFacility($searchdata, 'Private');
            $totalcluster_Private = 0;
            foreach ($getHealthFacility_Private as $kk => $vv) {
                $dist_id_Private = $vv->fieldId;
                $data['total'][$dist_id_Private]['tot']['private'] = $vv->totalCnt;
                $totalcluster_Private += $vv->totalCnt;
            }
            $data['totalcluster_private'] = $totalcluster_Private;

            /*==========Data Collected=============*/
            $getHealthFacility_DC = Hfa_Summary_Model::getHealthFacility_DC($searchdata, '');
            $totalcluster_DC = 0;
            foreach ($getHealthFacility_DC as $kk => $vv) {
                $dist_id_DC = $vv->fieldId;
                $data['total'][$dist_id_DC]['dc']['total'] = $vv->totalCnt;
                $totalcluster_DC += $vv->totalCnt;
            }
            $data['totalcluster_dc'] = $totalcluster_DC;

            /*==========Data Collected Public=============*/
            $getHealthFacility_DC_Public = Hfa_Summary_Model::getHealthFacility_DC($searchdata, 'Public');
            foreach ($getHealthFacility_DC_Public as $kk_Public => $vv_Public) {
                $dist_id_DC_Public = $vv_Public->fieldId;
                $data['total'][$dist_id_DC_Public]['dc']['public'] = $vv_Public->totalCnt;
            }
            /*==========Data Collected private=============*/
            $getHealthFacility_DC_private = Hfa_Summary_Model::getHealthFacility_DC($searchdata, 'Private');
            foreach ($getHealthFacility_DC_private as $kk_private => $vv_private) {
                $dist_id_DC_private = $vv_private->fieldId;
                $data['total'][$dist_id_DC_private]['dc']['private'] = $vv_private->totalCnt;
            }
           /* echo '<pre>';
            print_r($data);
            echo '<pre>';
            exit();*/
            return view('hfa_rs.hfa_summary', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }

    public function hfa_summary_detail_table()
    {
        $data = array();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'hfa_summary');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "HFA Summary Detail Table",
            "action" => "View hfa_summary_detail_table -> Function: hfa_summary_detail_table/index()",
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

            $searchdata = array();
            $searchdata['district'] = '';
            if (isset($data['permission'][0]->CanViewAllDetail) && $data['permission'][0]->CanViewAllDetail != 1
                && isset(Auth::user()->district) && Auth::user()->district != 0) {
                $searchdata['district'] = Auth::user()->district;
            }
            if (isset(request()->id) && request()->id != '' && !empty(request()->id)) {
                $searchdata['dist_id'] = request()->id;
            }
            $getData = Hfa_Summary_Model::getHealthFacility_detail($searchdata, '');

            $data['getData'] = $getData;
            return view('hfa_rs.hfa_summary_datatable', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }



}
