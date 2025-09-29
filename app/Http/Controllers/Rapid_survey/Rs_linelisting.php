<?php

namespace App\Http\Controllers\Rapid_survey;

use App\Http\Controllers\Controller;
use App\Models\Custom_Model;
use App\Models\Rapid_survey\linelisting_model;
use App\Models\Settings_Model;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use stdClass;

class Rs_linelisting extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = array();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'rs_linelisting');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Rs_linelisting",
            "action" => "View Rs_linelisting -> Function: Rs_linelisting/index()",
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
            if (
                isset($data['permission'][0]->CanViewAllDetail) && $data['permission'][0]->CanViewAllDetail != 1
                && isset(Auth::user()->district) && Auth::user()->district != 0
            ) {

                $searchdata['district'] = Auth::user()->district;
                // echo $searchdata['district'];die;
            }
            $getClustersProvince = linelisting_model::getClustersProvince($searchdata);



            $overall_dist_array = array();
            $totalcluster = 0;
            foreach ($getClustersProvince as $k => $v) {

                $dist_id = $v->dist_id;

                $overall_dist_array[$dist_id]['dist_id'] = $v->dist_id;
                $overall_dist_array[$dist_id]['district'] = $v->district;
                $overall_dist_array[$dist_id]['count'] = $v->totalDistrict;
                $totalcluster += $overall_dist_array[$dist_id]['count'];
            }

            $data['total'] = $overall_dist_array;
            $data['totalcluster'] = $totalcluster;

            /*==============Completed Clusters List==============*/
            $completedClusters_district = linelisting_model::completedClusters_district($searchdata);
            //echo '<pre>';print_r($completedClusters_district);die;

            if (isset($district) && $district != '') {
                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    $dist = $dist_name['district'];
                    $data['combine_ip_comp'][$dist_id]['dist_id'] = $dist_id;
                    $data['combine_ip_comp'][$dist_id]['district'] = $dist;
                    $data['combine_ip_comp'][$dist_id]['count'] = 0;
                    $data['completed'][$dist_id]['dist_id'] = $dist_id;
                    $data['completed'][$dist_id]['district'] = $dist;
                    $data['completed'][$dist_id]['count'] = 0;
                    $data['ip'][$dist_id]['dist_id'] = $dist_id;
                    $data['ip'][$dist_id]['district'] = $dist;
                    $data['ip'][$dist_id]['count'] = 0;
                    $data['r'][$dist_id]['dist_id'] = $dist_id;
                    $data['r'][$dist_id]['district'] = $dist;
                    $data['r'][$dist_id]['count'] = 0;
                }
            } else {
                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    $dist = $dist_name['district'];
                    $data['combine_ip_comp'][$dist_id]['dist_id'] = $dist_id;
                    $data['combine_ip_comp'][$dist_id]['district'] = $dist;
                    $data['combine_ip_comp'][$dist_id]['count'] = 0;

                    $data['completed'][$dist_id]['dist_id'] = $dist_id;
                    $data['completed'][$dist_id]['district'] = $dist;
                    $data['completed'][$dist_id]['count'] = 0;
                    $data['ip'][$dist_id]['dist_id'] = $dist_id;
                    $data['ip'][$dist_id]['district'] = $dist;
                    $data['ip'][$dist_id]['count'] = 0;
                    $data['r'][$dist_id]['dist_id'] = $dist_id;
                    $data['r'][$dist_id]['district'] = $dist;
                    $data['r'][$dist_id]['count'] = 0;
                }
            }

            $combine_ip_comp = 0;
            $completed = 0;
            $ip = 0;
            foreach ($completedClusters_district as $row) {
                $ke = $row->dist_id;
                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    if ($ke == $dist_id && $row->collecting_tabs != '' && $row->collecting_tabs != 0) {
                        $combine_ip_comp++;
                        $data['combine_ip_comp'][$dist_id]['count']++;
                        if ($row->collecting_tabs == $row->completed_tabs) {
                            $data['completed'][$dist_id]['count']++;
                            $completed++;
                        } else {
                            $data['ip'][$dist_id]['count']++;
                            $ip++;
                        }
                    }
                }
            }
            $data['total_completed'] = $completed;
            $data['total_ip'] = $ip;

            /*==============Remaining Clusters List==============*/
            $r = 0;
            foreach ($getClustersProvince as $row2) {
                $ke = $row2->dist_id;
                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    $dist = $dist_name['district'];
                    if ($ke == $dist_id) {
                        $data['r'][$dist_id]['count'] = $row2->totalDistrict - $data['combine_ip_comp'][$dist_id]['count'];
                        $r += $data['r'][$dist_id]['count'];
                    }
                }
            }
            $data['total_r'] = $r;
            return view('rapid_survey.linelisting', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }

    public function linelisting_detail(Request $request)
    {
        $data = array();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'rs_linelisting');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Rs_linelisting",
            "action" => "View Rs_linelisting Detail -> Function: Rs_linelisting/linelisting_detail()",
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

            $searchFilter = array();
            if (
                isset($data['permission'][0]->CanViewAllDetail) && $data['permission'][0]->CanViewAllDetail != 1
                && isset(Auth::user()->district) && Auth::user()->district != 0
            ) {
                $searchFilter['district'] = Auth::user()->district;
            }
            if (isset(request()->id) && request()->id != '' && !empty(request()->id)) {
                $searchFilter['district'] = request()->id;
            }
            $searchFilter['pageLevel'] = 2;
            $getClustersProvince = linelisting_model::getClustersProvince($searchFilter);

            $overall_dist_array = array();
            $totalcluster = 0;
            foreach ($getClustersProvince as $k => $v) {
                $dist_id = $v->tehsil;
                $overall_dist_array[$dist_id]['dist_id'] = $v->dist_id;
                $overall_dist_array[$dist_id]['district'] = $v->tehsil;
                $overall_dist_array[$dist_id]['count'] = $v->totalDistrict;
                $totalcluster += $overall_dist_array[$dist_id]['count'];
            }
            $data['total'] = $overall_dist_array;
            $data['totalcluster'] = $totalcluster;


            /*==============Completed Clusters List==============*/
            $completedClusters_district = linelisting_model::completedClusters_district($searchFilter);

            if (isset($district) && $district != '') {
                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    $dist = $dist_name['district'];
                    $data['combine_ip_comp'][$dist_id]['dist_id'] = $dist_id;
                    $data['combine_ip_comp'][$dist_id]['district'] = $dist;
                    $data['combine_ip_comp'][$dist_id]['count'] = 0;
                    $data['completed'][$dist_id]['dist_id'] = $dist_id;
                    $data['completed'][$dist_id]['district'] = $dist;
                    $data['completed'][$dist_id]['count'] = 0;
                    $data['ip'][$dist_id]['dist_id'] = $dist_id;
                    $data['ip'][$dist_id]['district'] = $dist;
                    $data['ip'][$dist_id]['count'] = 0;
                    $data['r'][$dist_id]['dist_id'] = $dist_id;
                    $data['r'][$dist_id]['district'] = $dist;
                    $data['r'][$dist_id]['count'] = 0;
                }
            } else {
                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    $dist = $dist_name['district'];
                    $data['combine_ip_comp'][$dist_id]['dist_id'] = $dist_id;
                    $data['combine_ip_comp'][$dist_id]['district'] = $dist;
                    $data['combine_ip_comp'][$dist_id]['count'] = 0;

                    $data['completed'][$dist_id]['dist_id'] = $dist_id;
                    $data['completed'][$dist_id]['district'] = $dist;
                    $data['completed'][$dist_id]['count'] = 0;
                    $data['ip'][$dist_id]['dist_id'] = $dist_id;
                    $data['ip'][$dist_id]['district'] = $dist;
                    $data['ip'][$dist_id]['count'] = 0;
                    $data['r'][$dist_id]['dist_id'] = $dist_id;
                    $data['r'][$dist_id]['district'] = $dist;
                    $data['r'][$dist_id]['count'] = 0;
                }
            }

            $combine_ip_comp = 0;
            $completed = 0;
            $ip = 0;


            foreach ($completedClusters_district as $row) {
                $ke = $row->tehsil;

                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    if ($ke == $dist_id && $row->collecting_tabs != '' && $row->collecting_tabs != 0) {
                        $combine_ip_comp++;
                        $data['combine_ip_comp'][$dist_id]['count']++;
                        if ($row->collecting_tabs == $row->completed_tabs) {
                            $data['completed'][$dist_id]['count']++;
                            $completed++;
                        } else {
                            $data['ip'][$dist_id]['count']++;
                            $ip++;
                        }
                    }
                }
            }
            $data['total_completed'] = $completed;
            $data['total_ip'] = $ip;


            /*==============Remaining Clusters List==============*/
            $r = 0;
            foreach ($getClustersProvince as $row2) {
                $ke = $row2->tehsil;
                foreach ($overall_dist_array as $dist_id => $dist_name) {
                    $dist = $dist_name['district'];
                    if ($ke == $dist_id) {
                        $data['r'][$dist_id]['count'] = $row2->totalDistrict - $data['combine_ip_comp'][$dist_id]['count'];
                        $r += $data['r'][$dist_id]['count'];
                    }
                }
            }
            $data['total_r'] = $r;
            $data['type'] = request()->type;
            $searchFilter['type'] = request()->type;
            $get_linelisting_table = linelisting_model::get_linelisting_table($searchFilter);
            $sendArr = [];
            foreach ($get_linelisting_table as $r) {
                if (isset(request()->type) && request()->type == 'i') {
                    if ($r->collecting_tabs != $r->completed_tabs) {
                        $sendArr[] = $r; //in-progress
                    }
                } elseif (isset(request()->type) && request()->type == 'c') {
                    if ($r->collecting_tabs == $r->completed_tabs) {
                        $sendArr[] = $r; //Complete
                    }
                } else {
                    $sendArr[] = $r;
                }
            }

            $data['get_linelisting_table'] = $sendArr;
            return view('rapid_survey.linelisting_table', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }

    public function systematic_randomizer(Request $request)
    {


        $sample = 20;
        if (isset($_POST['cluster_no']) && $request->input('cluster_no') != '') {
            $cluster = $request->input('cluster_no');
            $get_rand_cluster = linelisting_model::get_rand_cluster($cluster);

            $randomization_status = $get_rand_cluster[0]->randomized;
            if ($randomization_status == 1) {
                $result = array('Error', 'Cluster No ' . $cluster . ' already randomized', 'danger');
            } else {
                $chked = 0;
                $chkDuplicateTabs = linelisting_model::chkDuplicateTabs($cluster);
                if (isset($chkDuplicateTabs) && count($chkDuplicateTabs) >= 1) {
                    $chked = 1;
                }
                if ($chked == 0) {
                    $get_systematic_rand = linelisting_model::get_systematic_rand($cluster);
                    $duplicates = Collection::make($get_systematic_rand)
                        ->groupBy('hh02')
                        ->filter(function ($items) {
                            return $items->count() > 1;
                        })
                        ->all();
                    if (count($duplicates) > 0) {
                        $result = array('Duplicate Records ', 'Please Contact DMU', 'danger');
                    } else {

                        $cnt = count($get_systematic_rand);
                        if ($cnt >= 1) {
                            $cntData = count($get_systematic_rand);
                            $quotient = $this->_get_quotient($cntData, $sample);
                            $random_start = $this->_get_random_start($quotient);
                            $random_point = $random_start;
                            $index = floor($random_start);
                            if ($cntData > $sample) {
                                $ll = $sample;
                            } else {
                                $ll = $cntData;
                            }
                            $counter = 0;
                            $form_data = [];
                            for ($i = 0; $i < $ll; $i++) {


                                $form_data[] = array(
                                    'updDt' => date('Y-m-d h:i:s'),
                                    'randDT' => date('Y-m-d h:i:s'),
                                    'uid' => $get_systematic_rand[$index - 1]->_uid,
                                    'sno' => $i + 1,
                                    'hh01' => $get_systematic_rand[$index - 1]->village_name,
                                    'hh02' => $get_systematic_rand[$index - 1]->hh01,
                                    'hh03' => $get_systematic_rand[$index - 1]->hh04,
                                    'hh04' => $get_systematic_rand[$index - 1]->hh07,
                                    'hh07' => $get_systematic_rand[$index - 1]->hhid,
                                    'hh08' => $get_systematic_rand[$index - 1]->hh11,
                                    'hh10' => $get_systematic_rand[$index - 1]->hh13,
                                    'hh11' => $get_systematic_rand[$index - 1]->hh13a,
                                    'hhdt' => $get_systematic_rand[$index - 1]->dov,
                                    'total' => $cntData,
                                    'randno' => $random_start,
                                    'randomPick' => $index - 1,
                                    'quot' => $quotient,
                                    'dist_id' => $get_systematic_rand[$index - 1]->dist_id,
                                    'hhno' => $get_systematic_rand[$index - 1]->hh02,
                                    'hhss' => str_pad($get_systematic_rand[$index - 1]->hh04, 4, "0", STR_PAD_LEFT) . "-" . str_pad($get_systematic_rand[$index - 1]->hhid, 3, "0", STR_PAD_LEFT),
                                    'compid' => $get_systematic_rand[$index - 1]->hh01 . '-' . $get_systematic_rand[$index - 1]->hh02,
                                    'tabNo' => $get_systematic_rand[$index - 1]->tabNo,
                                    'user_id' => Auth::user()->id,
                                    'user_name' => Auth::user()->username,
                                    'fd_2022' => 0,
                                );
                                //DB::connection('endline_survey')->table('bl_randomised')->insert($form_data);
                                $random_point = $random_point + $quotient;
                                $index = floor($random_point);
                                $counter = $counter + 1;
                            }
                            //$numbers = collect(range(0, 19))->shuffle()->take(4);
                            if (count($form_data) == 20) {
                                foreach (collect(range(0, 19))->shuffle()->take(4) as $n) {
                                    $form_data[$n]['fd_2022'] = 1;
                                }
                            }
                            //echo '<pre>';print_r($form_data);die;
                            DB::connection('endline_survey')->table('bl_randomised')->insert($form_data);
                            $updateCluster = array();
                            $updateCluster['randomized'] = 1;
                            $editData = DB::connection('endline_survey')->table('clusters')
                                ->where('cluster_no', $cluster)
                                ->update($updateCluster);
                            if ($editData) {
                                $result = array('Success', 'Successfully Randomized', 'success');
                            } else {
                                $result = array('Error', 'Randomized added, but error in updating cluster', 'danger');
                            }
                        } else {
                            $result = array('Error', 'Cluster No ' . $cluster . ' has Zero Households', 'danger');
                        }
                    }

                } else {
                    $result = array('Error', 'Duplicate Household Found in Cluster No ' . $cluster . ', Please coordinate with DMU', 'danger');
                }
            }
        } else {
            $result = array('Error', 'Cluster not found', 'danger');
        }
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Rs_linelisting Randomization",
            "action" => "Add Rs_linelisting Randomization -> Function: Rs_linelisting/systematic_randomizer()",
            "mainResult" => $result[0],
            "result" => $result[1],
            "PostData" => array(),
            "affectedKey" => 'id',
            "idUser" => Auth::user()->id,
            "username" => Auth::user()->username,
        );
        Custom_Model::trackLogs($trackarray, "all_logs");
        /*==========Log=============*/
        return json_encode($result);
    }

    private function _get_quotient($dataset, $sample)
    {
        if ($dataset > $sample) {
            $quotient = $dataset / $sample;
        } else {
            $quotient = 1;
        }
        return $quotient;
    }

    private function _get_random_start($quotient)
    {
        $random_start = rand(1, $quotient);
        return $random_start;
    }

    public function randomized_detail(Request $request)
    {
        $data = array();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'rs_linelisting');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Rs_linelisting Randomization Detail",
            "action" => "View Rs_linelisting Randomization Detail -> Function: Rs_linelisting/randomized_detail()",
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
            $cluster = '0';
            if (isset(request()->id) && request()->id != '' && !empty(request()->id)) {
                $cluster = request()->id;
            }
            $get_randomized_table = linelisting_model::get_randomized_table($cluster);
            $data['get_randomized_table'] = $get_randomized_table;
            return view('rapid_survey.linelisting_randomized_detail', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }

    public function make_pdf(Request $request)
    {
        $data = array();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'rs_linelisting');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Rs_linelisting Randomization PDF",
            "action" => "View Rs_linelisting Randomization PDF -> Function: Rs_linelisting/make_pdf()",
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
            $cluster = '0';
            if (isset(request()->id) && request()->id != '' && !empty(request()->id)) {
                $cluster = request()->id;
            }
            $get_randomized_table = linelisting_model::get_randomized_table($cluster);
            $data['get_randomized_table'] = $get_randomized_table;
            //            return view('rapid_survey.make_pdf', ['data' => $data]);
            $pdf = PDF::loadView('rapid_survey.make_pdf', ['data' => $data]);
            return $pdf->download($cluster . '_randomization_uen_rs.pdf');
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }
}
