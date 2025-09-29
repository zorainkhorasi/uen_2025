<?php

namespace App\Http\Controllers\RSD;

use App\Http\Controllers\Controller;
use App\Models\App_Users_Model;
use App\Models\Custom_Model;
use App\Models\RSD\Rsd_Model;
use App\Models\Settings_Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Rsd extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = array();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'rsd');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Rsd",
            "action" => "View Rsd -> Function: Rsd/index()",
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
            return view('rsd.index', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }

    public function getRsdData(Request $request)
    {
        $result = array();

        $orderindex = (isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : '');
        $orderby = (isset($_REQUEST['columns'][$orderindex]['name']) ? $_REQUEST['columns'][$orderindex]['name'] : '');
        $searchData = array();
        $searchData['province'] = (isset($_REQUEST['province']) && $request->input('province') != '' && $request->input('province') != 0 ? $request->input('province') : 0);
        $searchData['type'] = $request->input('type');
        $searchData['start'] = (isset($_REQUEST['start']) && $_REQUEST['start'] != '' && $_REQUEST['start'] != 0 ? $_REQUEST['start'] : 0);
        $searchData['length'] = (isset($_REQUEST['length']) && $_REQUEST['length'] != '' ? $_REQUEST['length'] : '');
        $searchData['search'] = (isset($_REQUEST['search']['value']) && $_REQUEST['search']['value'] != '' ? $_REQUEST['search']['value'] : '');
        $searchData['orderby'] = (isset($orderby) && $orderby != '' ? $orderby : 'hfcode');
        $searchData['ordersort'] = (isset($_REQUEST['order'][0]['dir']) && $_REQUEST['order'][0]['dir'] != '' ? $_REQUEST['order'][0]['dir'] : 'asc');

        $totalsearchData = array();
        $totalsearchData['start'] = 0;
        $totalsearchData['length'] = 10000000;
        $totalsearchData['province'] = $searchData['province'];
        $totalsearchData['type'] = $searchData['type'];
        $totalsearchData['search'] = $searchData['search'];

        $data = Rsd_Model::getRsdData($searchData);
        $getCntRsdData = Rsd_Model::getCntRsdData($totalsearchData);

        $result["draw"] = (isset($_REQUEST['draw']) && $_REQUEST['draw'] != '' ? $_REQUEST['draw'] : 0);;
        $result["recordsTotal"] = $getCntRsdData->count();
        $result["recordsFiltered"] = $getCntRsdData->count();
        $result["data"] = $data;


        return json_encode($result);
    }

    /*=====================================facility Detail=====================================*/

    public function facilityDetail(Request $request)
    {
        $data = array();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'rsd');
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Rsd facilityDetail",
            "action" => "View Rsd facilityDetail -> Function: Rsd/facilityDetail()",
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
            return view('rsd.facilityDetail', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }

    public function facilityDetailData(Request $request)
    {
        $result = array();

        $orderindex = (isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : '');
        $orderby = (isset($_REQUEST['columns'][$orderindex]['name']) ? $_REQUEST['columns'][$orderindex]['name'] : '');
        $searchData = array();
        $searchData['hfCode'] = (isset($_REQUEST['hfCode']) && $request->input('hfCode') != '' && $request->input('hfCode') != 0 ? $request->input('hfCode') : 0);
        $searchData['start'] = (isset($_REQUEST['start']) && $_REQUEST['start'] != '' && $_REQUEST['start'] != 0 ? $_REQUEST['start'] : 0);
        $searchData['length'] = (isset($_REQUEST['length']) && $_REQUEST['length'] != '' ? $_REQUEST['length'] : '');
        $searchData['search'] = (isset($_REQUEST['search']['value']) && $_REQUEST['search']['value'] != '' ? $_REQUEST['search']['value'] : '');
        $searchData['orderby'] = (isset($orderby) && $orderby != '' ? $orderby : 'reportingMonth');
        $searchData['ordersort'] = (isset($_REQUEST['order'][0]['dir']) && $_REQUEST['order'][0]['dir'] != '' ? $_REQUEST['order'][0]['dir'] : 'asc');

        $totalsearchData = array();
        $totalsearchData['start'] = 0;
        $totalsearchData['length'] = 10000000;
        $totalsearchData['hfCode'] = $searchData['hfCode'];
        $totalsearchData['search'] = $searchData['search'];

        $data = Rsd_Model::getRsd_facilityData($searchData);
        $getCntRsdData = Rsd_Model::getCntRsd_facilityData($totalsearchData);

        $result["draw"] = (isset($_REQUEST['draw']) && $_REQUEST['draw'] != '' ? $_REQUEST['draw'] : 0);;
        $result["recordsTotal"] = $getCntRsdData->count();
        $result["recordsFiltered"] = $getCntRsdData->count();
        $result["data"] = $data;
        return json_encode($result);
    }

    /*=====================================month Detail Facilities=====================================*/
    public function comparison(Request $request)
    {
        $searchFilter = array();
        $searchFilter['hfCode'] = request()->id;
        $searchFilter['date'] = request()->date;
        $searchFilter['type'] = request()->type;
        $data = array();
        $data['permission'] = Settings_Model::getUserRights(Auth::user()->idGroup, '', 'rsd');
        $arr = array();
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Rsd comparison",
            "action" => "View Rsd comparison -> Function: Rsd/comparison()",
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

            $pro = $searchFilter['hfCode'][0];

            //echo $pro;die;
            $table = '';
            if ($searchFilter['type'] == 'Primary') {
                if ($pro == 1) {
                    $table = 'v_punjab_p';
                } elseif ($pro == 2) {
                    $table = 'v_sindh_p';
                }elseif ($pro ==3) {
                    $table = 'v_kp_p';
                } elseif ($pro ==4) {
                    $table = 'v_balochistan_p';
                }
                if ($pro == 1 || $pro == 4 || $pro==3) {
                    $arr = array(
                        array('rsd_key' => 'f101ax', 'rsd_label' => 'Number of Antenatal Care Visits (ANC-1)', 'dhis_key' => 's801'),
                        array('rsd_key' => 'f102ax', 'rsd_label' => 'Number of Antenatal Care Re-visits (ANC-2/3/4 or more)', 'dhis_key' => 's803'),
                        array('rsd_key' => 'f103ax', 'rsd_label' => 'ANC-1 Women with Hb<10g/dl', 'dhis_key' => 's802'),
                        array('rsd_key' => 'f104ax', 'rsd_label' => 'Number of Postnatal Care Visits (PNC-1)', 'dhis_key' => 's806'),
                        array('rsd_key' => 'f301ax', 'rsd_label' => 'Normal Vaginal Delivery (NVD)', 'dhis_key' => 's813'),
                        array('rsd_key' => 'f302ax', 'rsd_label' => 'Assisted Vaginal (Forceps/Vacuum) Delivery', 'dhis_key' => 's814'),
                        array('rsd_key' => 'f319ax', 'rsd_label' => 'Number of Live Births (LBs) in the facility', 'dhis_key' => 's816'),
                        array('rsd_key' => 'f321ax', 'rsd_label' => 'Number of Live Births with Low Birth Weight (<2.5kg or <2500 grams)', 'dhis_key' => 's817'),
                        array('rsd_key' => 'f322ax', 'rsd_label' => 'Number of Still Births (SBs) in the facility', 'dhis_key' => 's821'),
                        array('rsd_key' => 'f323ax', 'rsd_label' => 'Number of Intra Uterine Deaths (IUDs) in the facility', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f325ax', 'rsd_label' => 'Number of Newborns with Birth Asphyxia were successfully resuscitated ', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f327ax', 'rsd_label' => 'Number of Neonatal Deaths (0-28 days) in the facility', 'dhis_key' => 's823'),
                        array('rsd_key' => 'f339ax', 'rsd_label' => 'Pneumonia <1 month Cases', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f340ax', 'rsd_label' => 'Death due to Pneumonia <1 month', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f341ax', 'rsd_label' => 'Death due to Low Birth Weight (<2.5kg or <2500 grams)', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f342ax', 'rsd_label' => 'Family Planning Counselling', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f401ax', 'rsd_label' => 'Total Family Planning Visits', 'dhis_key' => 's701'),
                        array('rsd_key' => 'f402ax', 'rsd_label' => 'Total Family Planning Visits referred by LHWs', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f403ax', 'rsd_label' => 'Family Planning clients received COC Cycles', 'dhis_key' => 's703'),
                        array('rsd_key' => 'f404ax', 'rsd_label' => 'Family Planning Clients received POP cycles', 'dhis_key' => 's704'),
                        array('rsd_key' => 'f405ax', 'rsd_label' => 'Family Planning Clients received DMPA Injectable', 'dhis_key' => 's705'),
                        array('rsd_key' => 'f406ax', 'rsd_label' => 'Family Planning Clients received Net-En Injectable', 'dhis_key' => 's706'),
                        array('rsd_key' => 'f407ax', 'rsd_label' => 'Family Planning Clients received Sayana Press Injectable (At Facility)', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f408ax', 'rsd_label' => 'Family Planning Clients received Condoms', 'dhis_key' => 's707'),
                        array('rsd_key' => 'f409ax', 'rsd_label' => 'Family Planning Clients received IUCDs', 'dhis_key' => 's708'),
                        array('rsd_key' => 'f411ax', 'rsd_label' => 'Family Planning Clients received Implants (from OPD)', 'dhis_key' => 's712'),
                        array('rsd_key' => 'f412ax', 'rsd_label' => 'Family Planning Clients received Implants (PPFP/PAFP) from Labour Room', 'dhis_key' => 's712'),
                        array('rsd_key' => 'f413ax', 'rsd_label' => 'Family Planning Clients undergo Vasectomy', 'dhis_key' => 's711'),
                        array('rsd_key' => 'f414ax', 'rsd_label' => 'Family Planning Clients undergo Tubal Ligation', 'dhis_key' => 's710'),
                        array('rsd_key' => 'f415ax', 'rsd_label' => 'Clients counsel for Family Planning in Maternal OPD (ANC & PNC)', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f416ax', 'rsd_label' => 'Clients counsel for Family Planning in Pediatric OPD', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f417ax', 'rsd_label' => 'Clients counsel for Family Planning in Immunization Room', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f418ax', 'rsd_label' => 'Clients identified for Family Planning Counselling in pediatric Clinic', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f419ax', 'rsd_label' => 'Clients identified for Immunization at OPD Clinic (ANC/PNC)', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f501ax', 'rsd_label' => 'Pediatric OPD (1-11 months, Male + Female)', 'dhis_key' => 's301m_b'),
                        array('rsd_key' => 'f502ax', 'rsd_label' => 'Pediatric OPD (12-59 months, Male + Female)', 'dhis_key' => 's301m_c'),
                        array('rsd_key' => 'f503ax', 'rsd_label' => 'Paeds Referred Cases Attended (Referral In)', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f507ax', 'rsd_label' => 'Number of Pneumonia Cases <5 years OPD', 'dhis_key' => 's403'),
                        array('rsd_key' => 'f512ax', 'rsd_label' => 'Number of Pneumonia Cases <5 years Indoor', 'dhis_key' => 's1205_a'),
                        array('rsd_key' => 'f513ax', 'rsd_label' => 'Death due to Pneumonia Cases <5 years Indoor', 'dhis_key' => 's1205_b'),
                        array('rsd_key' => 'f514ax', 'rsd_label' => 'Number of Infant Death (1-11 months)', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f515ax', 'rsd_label' => 'Number of Child Death (1-4 years)', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f516ax', 'rsd_label' => 'Number of Child Death (5-14 years)', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f601ax', 'rsd_label' => 'Pregnant Women given TT-2 Vaccine', 'dhis_key' => 's503'),
                        array('rsd_key' => 'f602ax', 'rsd_label' => 'Children <12 months received 3rd Dose of Pentavalent Vaccine (Penta-3)', 'dhis_key' => 's501'),
                        array('rsd_key' => 'f603ax', 'rsd_label' => 'Children <12 months received 1st Dose of Measles Vaccine (Measles-1)', 'dhis_key' => 's502'),
                        array('rsd_key' => 'f701ax', 'rsd_label' => 'Number of Health Care Providers trained on clinical management and psychological support on Gender Based Violence (GBV)', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f702aax', 'rsd_label' => 'Physical', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f702bax', 'rsd_label' => 'Sexual', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f703aax', 'rsd_label' => 'Came as Him/ Herself', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f703bax', 'rsd_label' => 'Were referred by LHWs', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f703cax', 'rsd_label' => 'Were referred by the police', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f704ax', 'rsd_label' => 'Number of cases of violence (by type of case) which have received basic set of health services ', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f705ax', 'rsd_label' => 'Number of deaths resulting from Gender Based Violence (GBV)', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f8011ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8021ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8031ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8041ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8051ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8061ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8071ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8081ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8091ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),


                    );
                } elseif ($pro == 2) {
                    $arr = array(
                        array('rsd_key' => 'f101ax', 'rsd_label' => 'Number of Antenatal Care Visits (ANC-1)', 'dhis_key' => 's801'),
                        array('rsd_key' => 'f102ax', 'rsd_label' => 'Number of Antenatal Care Re-visits (ANC-2/3/4 or more)', 'dhis_key' => 'anc2+anc3+anc4'),
                        array('rsd_key' => 'f103ax', 'rsd_label' => 'ANC-1 Women with Hb<10g/dl', 'dhis_key' => 's802'),
                        array('rsd_key' => 'f104ax', 'rsd_label' => 'Number of Postnatal Care Visits (PNC-1)', 'dhis_key' => 's806'),
                        array('rsd_key' => 'f105ax', 'rsd_label' => 'Number of Postnatal Care Re-visits (PNC-2/3/4 or more)', 'dhis_key' => 'pnc2+pnc3+pnc4'),
                        array('rsd_key' => 'f106ax', 'rsd_label' => 'Number of Ultra Sounds for ANC', 'dhis_key' => ''),
                        array('rsd_key' => 'f301ax', 'rsd_label' => 'Normal Vaginal Delivery (NVD)', 'dhis_key' => 's813'),
                        array('rsd_key' => 'f302ax', 'rsd_label' => 'Assisted Vaginal (Forceps/Vacuum) Delivery', 'dhis_key' => 's814'),
                        array('rsd_key' => 'f303ax', 'rsd_label' => 'Caesarean Section (C-Section)', 'dhis_key' => 's810'),
                        array('rsd_key' => 'f304ax', 'rsd_label' => 'Number of Women provided Misoprostol after delivery', 'dhis_key' => 'PW_given_Misoprostol_Tablet'),
                        array('rsd_key' => 'f319ax', 'rsd_label' => 'Number of Live Births (LBs) in the facility', 'dhis_key' => 's816'),
                        array('rsd_key' => 'f320ax', 'rsd_label' => 'Number of Newborns to whom Chlorhexidine Gel is applied to Umbilical Stumps', 'dhis_key' => 'Neonates_received_ChlorohexidineCHX'),
                        array('rsd_key' => 'f321ax', 'rsd_label' => 'Number of Live Births with Low Birth Weight (<2.5kg or <2500 grams)', 'dhis_key' => 's817'),
                        array('rsd_key' => 'f322ax', 'rsd_label' => 'Number of Still Births (SBs) in the facility', 'dhis_key' => 's821'),
                        array('rsd_key' => 'f323ax', 'rsd_label' => 'Number of Intra Uterine Deaths (IUDs) in the facility', 'dhis_key' => ''),
                        array('rsd_key' => 'f324ax', 'rsd_label' => 'Number of Newborns with Birth Asphyxia ', 'dhis_key' => 'asphyxia'),
                        array('rsd_key' => 'f325ax', 'rsd_label' => 'Number of Newborns with Birth Asphyxia were successfully resuscitated ', 'dhis_key' => ''),
                        array('rsd_key' => 'f326ax', 'rsd_label' => 'Number of Pre-term Births (PTB)/Prematurity', 'dhis_key' => 'preterm_birth'),
                        array('rsd_key' => 'f327ax', 'rsd_label' => 'Number of Neonatal Deaths (0-28 days) in the facility', 'dhis_key' => 's823'),
                        array('rsd_key' => 'f330ax', 'rsd_label' => 'Death due to Birth Asphyxia Cases', 'dhis_key' => 'ba_ndis'),
                        array('rsd_key' => 'f331ax', 'rsd_label' => 'Bacterial Sepsis Cases', 'dhis_key' => 'sepsis'),
                        array('rsd_key' => 'f332ax', 'rsd_label' => 'Death due to Bacterial Sepsis', 'dhis_key' => 'sepsis_ndis'),
                        array('rsd_key' => 'f339ax', 'rsd_label' => 'Pneumonia <1 month Cases', 'dhis_key' => ''),
                        array('rsd_key' => 'f340ax', 'rsd_label' => 'Death due to Pneumonia <1 month', 'dhis_key' => ''),
                        array('rsd_key' => 'f342ax', 'rsd_label' => 'Family Planning Counselling', 'dhis_key' => 's714'),
                        array('rsd_key' => 'f401ax', 'rsd_label' => 'Total Family Planning Visits', 'dhis_key' => 's701'),
                        array('rsd_key' => 'f402ax', 'rsd_label' => 'Total Family Planning Visits referred by LHWs', 'dhis_key' => ''),
                        array('rsd_key' => 'f403ax', 'rsd_label' => 'Family Planning clients received COC Cycles', 'dhis_key' => 's703'),
                        array('rsd_key' => 'f404ax', 'rsd_label' => 'Family Planning Clients received POP cycles', 'dhis_key' => 's704'),
                        array('rsd_key' => 'f405ax', 'rsd_label' => 'Family Planning Clients received DMPA Injectable', 'dhis_key' => 's705'),
                        array('rsd_key' => 'f406ax', 'rsd_label' => 'Family Planning Clients received Net-En Injectable', 'dhis_key' => 's706'),
                        array('rsd_key' => 'f407ax', 'rsd_label' => 'Family Planning Clients received Sayana Press Injectable (At Facility)', 'dhis_key' => ''),
                        array('rsd_key' => 'f408ax', 'rsd_label' => 'Family Planning Clients received Condoms', 'dhis_key' => 's707'),
                        array('rsd_key' => 'f409ax', 'rsd_label' => 'Family Planning Clients received IUCDs', 'dhis_key' => 's708'),
                        array('rsd_key' => 'f410ax', 'rsd_label' => 'Family Planning Clients received PPIUCDs (from Labour Room)', 'dhis_key' => 's709'),
                        array('rsd_key' => 'f411ax', 'rsd_label' => 'Family Planning Clients received Implants (from OPD)', 'dhis_key' => 's712'),
                        array('rsd_key' => 'f412ax', 'rsd_label' => 'Family Planning Clients received Implants (PPFP/PAFP) from Labour Room', 'dhis_key' => 's712'),
                        array('rsd_key' => 'f413ax', 'rsd_label' => 'Family Planning Clients undergo Vasectomy', 'dhis_key' => 's711'),
                        array('rsd_key' => 'f414ax', 'rsd_label' => 'Family Planning Clients undergo Tubal Ligation', 'dhis_key' => 's710'),
                        array('rsd_key' => 'f415ax', 'rsd_label' => 'Clients counsel for Family Planning in Maternal OPD (ANC & PNC)', 'dhis_key' => ''),
                        array('rsd_key' => 'f416ax', 'rsd_label' => 'Clients counsel for Family Planning in Pediatric OPD', 'dhis_key' => ''),
                        array('rsd_key' => 'f417ax', 'rsd_label' => 'Clients counsel for Family Planning in Immunization Room', 'dhis_key' => ''),
                        array('rsd_key' => 'f418ax', 'rsd_label' => 'Clients identified for Family Planning Counselling in pediatric Clinic', 'dhis_key' => ''),
                        array('rsd_key' => 'f419ax', 'rsd_label' => 'Clients identified for Immunization at OPD Clinic (ANC/PNC)', 'dhis_key' => ''),
                        array('rsd_key' => 'f501ax', 'rsd_label' => 'Pediatric OPD (1-11 months, Male + Female)', 'dhis_key' => 's301m_b + s302f_b'),
                        array('rsd_key' => 'f502ax', 'rsd_label' => 'Pediatric OPD (12-59 months, Male + Female)', 'dhis_key' => 's301m_c + s302f_c'),
                        array('rsd_key' => 'f503ax', 'rsd_label' => 'Paeds Referred Cases Attended (Referral In)', 'dhis_key' => ''),
                        array('rsd_key' => 'f505ax', 'rsd_label' => 'Number of Diarrhea Cases <5 years OPD', 'dhis_key' => 'dia1'),
                        array('rsd_key' => 'f506ax', 'rsd_label' => 'Number of Dysentery Cases <5 years OPD', 'dhis_key' => 'dia2'),
                        array('rsd_key' => 'f507ax', 'rsd_label' => 'Number of Pneumonia Cases <5 years OPD', 'dhis_key' => 's403'),
                        array('rsd_key' => 'f508ax', 'rsd_label' => 'Number of Diarrhea Cases <5 years Indoor', 'dhis_key' => 'dia_a1'),
                        array('rsd_key' => 'f509ax', 'rsd_label' => 'Death due to Diarrhea Cases <5 years Indoor', 'dhis_key' => 'dia_d1'),
                        array('rsd_key' => 'f510ax', 'rsd_label' => 'Number of Dysentery Cases <5 years Indoor', 'dhis_key' => 'dia_a2'),
                        array('rsd_key' => 'f511ax', 'rsd_label' => 'Death due to Dysentery Cases <5 years Indoor', 'dhis_key' => 'dia_d2'),
                        array('rsd_key' => 'f512ax', 'rsd_label' => 'Number of Pneumonia Cases <5 years Indoor', 'dhis_key' => 's1205_a'),
                        array('rsd_key' => 'f513ax', 'rsd_label' => 'Death due to Pneumonia Cases <5 years Indoor', 'dhis_key' => 's1205_b'),
                        array('rsd_key' => 'f514ax', 'rsd_label' => 'Number of Infant Death (1-11 months)', 'dhis_key' => ''),
                        array('rsd_key' => 'f515ax', 'rsd_label' => 'Number of Child Death (1-4 years)', 'dhis_key' => ''),
                        array('rsd_key' => 'f516ax', 'rsd_label' => 'Number of Child Death (5-14 years)', 'dhis_key' => ''),
                        array('rsd_key' => 'f601ax', 'rsd_label' => 'Pregnant Women given TT-2 Vaccine', 'dhis_key' => 's503'),
                        array('rsd_key' => 'f602ax', 'rsd_label' => 'Children <12 months received 3rd Dose of Pentavalent Vaccine (Penta-3)', 'dhis_key' => 's501'),
                        array('rsd_key' => 'f603ax', 'rsd_label' => 'Children <12 months received 1st Dose of Measles Vaccine (Measles-1)', 'dhis_key' => 's502'),
                        array('rsd_key' => 'f701ax', 'rsd_label' => 'Number of Health Care Providers trained on clinical management and psychological support on Gender Based Violence (GBV)', 'dhis_key' => ''),
                        array('rsd_key' => 'f702aax', 'rsd_label' => 'Physical', 'dhis_key' => ''),
                        array('rsd_key' => 'f702bax', 'rsd_label' => 'Sexual', 'dhis_key' => ''),
                        array('rsd_key' => 'f703aax', 'rsd_label' => 'Came as Him/ Herself', 'dhis_key' => ''),
                        array('rsd_key' => 'f703bax', 'rsd_label' => 'Were referred by LHWs', 'dhis_key' => ''),
                        array('rsd_key' => 'f703cax', 'rsd_label' => 'Were referred by the police', 'dhis_key' => ''),
                        array('rsd_key' => 'f704ax', 'rsd_label' => 'Number of cases of violence (by type of case) which have received basic set of health services ', 'dhis_key' => ''),
                        array('rsd_key' => 'f705ax', 'rsd_label' => 'Number of deaths resulting from Gender Based Violence (GBV)', 'dhis_key' => ''),
                        array('rsd_key' => 'f8011ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8021ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8031ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8041ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8051ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8061ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8071ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8081ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8091ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                    );
                }

            }
            elseif ($searchFilter['type'] == 'Secondary') {
                if ($pro == 1) {
                    $table = 'v_punjab_s';
                } elseif ($pro == 2) {
                    $table = 'v_sindh_s';
                } elseif ($pro == 3) {
                    $table = 'v_kp_s';
                } elseif ($pro == 4) {
                    $table = 'v_balochistan_s';
                }
                if ($pro == 1 || $pro == 4 || $pro==3 ) {
                    $arr = array(
                        array('rsd_key' => 'f101ax', 'rsd_label' => 'Number of Antenatal Care Visits (ANC-1)', 'dhis_key' => 's801'),
                        array('rsd_key' => 'f102ax', 'rsd_label' => 'Number of Antenatal Care Re-visits (ANC-2/3/4 or more)', 'dhis_key' => 's803'),
                        array('rsd_key' => 'f103ax', 'rsd_label' => 'ANC-1 Women with Hb<10g/dl', 'dhis_key' => 's802'),
                        array('rsd_key' => 'f104ax', 'rsd_label' => 'Number of Postnatal Care Visits (PNC-1)', 'dhis_key' => 's804'),
                        array('rsd_key' => 'f105ax', 'rsd_label' => 'Number of Postnatal Care Re-visits (PNC-2/3/4 or more)', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f106ax', 'rsd_label' => 'Number of Ultra Sounds for ANC', 'dhis_key' => ''),
                        array('rsd_key' => 'f201ax', 'rsd_label' => 'Total Obs/Gyne Cases', 'dhis_key' => 's3_1111'),
                        array('rsd_key' => 'f202ax', 'rsd_label' => 'OBS/GYN, Referred Cases Attended (Referral In)', 'dhis_key' => 's3_1114'),
                        array('rsd_key' => 'f203ax', 'rsd_label' => 'OBS/GYN, Referred Cases (Referral Out)', 'dhis_key' => 's13a_406'),
                        array('rsd_key' => 'f301ax', 'rsd_label' => 'Normal Vaginal Delivery (NVD)', 'dhis_key' => 's805'),
                        array('rsd_key' => 'f302ax', 'rsd_label' => 'Assisted Vaginal (Forceps/Vacuum) Delivery', 'dhis_key' => 's806'),
                        array('rsd_key' => 'f303ax', 'rsd_label' => 'Caesarean Section (C-Section)', 'dhis_key' => 's810'),
                        array('rsd_key' => 'f304ax', 'rsd_label' => 'Number of Women provided Misoprostol after delivery', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f305ax', 'rsd_label' => 'Number of Ante partum Hemorrhage (APH) Cases', 'dhis_key' => 's13b_38a'),
                        array('rsd_key' => 'f306ax', 'rsd_label' => 'Death due to Ante partum Hemorrhage (APH)', 'dhis_key' => 's13b_38b'),
                        array('rsd_key' => 'f307ax', 'rsd_label' => 'Number of Ectopic Pregnancies Cases', 'dhis_key' => 's13b_40a'),
                        array('rsd_key' => 'f308ax', 'rsd_label' => 'Death due to Ectopic Pregnancies ', 'dhis_key' => 's13b_40b'),
                        array('rsd_key' => 'f309ax', 'rsd_label' => 'Number of Postpartum Hemorrhage (PPH) Cases', 'dhis_key' => 's13b_41a'),
                        array('rsd_key' => 'f310ax', 'rsd_label' => 'Death due to Postpartum Hemorrhage (PPH)', 'dhis_key' => 's13b_41b'),
                        array('rsd_key' => 'f311ax', 'rsd_label' => 'Number of Pre-Eclampsia/Eclampsia (PE/E) Cases', 'dhis_key' => 's13b_42a'),
                        array('rsd_key' => 'f312ax', 'rsd_label' => 'Death due to Pre-Eclampsia/Eclampsia (PE/E)', 'dhis_key' => 's13b_42b'),
                        array('rsd_key' => 'f313ax', 'rsd_label' => 'Number of Prolonged Obstetric Labor (POL) Cases', 'dhis_key' => 's13b_43a'),
                        array('rsd_key' => 'f314ax', 'rsd_label' => 'Death due to Prolonged Obstetric Labor (POL)', 'dhis_key' => 's13b_43b'),
                        array('rsd_key' => 'f315ax', 'rsd_label' => 'Number of Puerperal Sepsis Cases', 'dhis_key' => 's13b_44a'),
                        array('rsd_key' => 'f316ax', 'rsd_label' => 'Death due to Puerperal Sepsis', 'dhis_key' => 's13b_44b'),
                        array('rsd_key' => 'f317ax', 'rsd_label' => 'Number of Rupture Uterus Cases', 'dhis_key' => 's13b_45a'),
                        array('rsd_key' => 'f318ax', 'rsd_label' => 'Death due to Rupture Uterus', 'dhis_key' => 's13b_45b'),
                        array('rsd_key' => 'f319ax', 'rsd_label' => 'Number of Live Births (LBs) in the facility', 'dhis_key' => 's807'),
                        array('rsd_key' => 'f320ax', 'rsd_label' => 'Number of Newborns to whom Chlorhexidine Gel is applied to Umbilical Stumps', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f321ax', 'rsd_label' => 'Number of Live Births with Low Birth Weight (<2.5kg or <2500 grams)', 'dhis_key' => 's808'),
                        array('rsd_key' => 'f322ax', 'rsd_label' => 'Number of Still Births (SBs) in the facility', 'dhis_key' => 's809'),
                        array('rsd_key' => 'f323ax', 'rsd_label' => 'Number of Intra Uterine Deaths (IUDs) in the facility', 'dhis_key' => ''),
                        array('rsd_key' => 'f324ax', 'rsd_label' => 'Number of Newborns with Birth Asphyxia ', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f325ax', 'rsd_label' => 'Number of Newborns with Birth Asphyxia were successfully resuscitated ', 'dhis_key' => ''),
                        array('rsd_key' => 'f326ax', 'rsd_label' => 'Number of Pre-term Births (PTB)/Prematurity', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f327ax', 'rsd_label' => 'Number of Neonatal Deaths (0-28 days) in the facility', 'dhis_key' => 's811+s815+s812+s813+s814+s816'),
                        array('rsd_key' => 'f328ax', 'rsd_label' => 'Birth Trauma Cases', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f329ax', 'rsd_label' => 'Death due to Birth Trauma', 'dhis_key' => 's812'),
                        array('rsd_key' => 'f330ax', 'rsd_label' => 'Death due to Birth Asphyxia Cases', 'dhis_key' => 's811'),
                        array('rsd_key' => 'f331ax', 'rsd_label' => 'Bacterial Sepsis Cases', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f332ax', 'rsd_label' => 'Death due to Bacterial Sepsis', 'dhis_key' => 's813'),
                        array('rsd_key' => 'f333ax', 'rsd_label' => 'Congenital Abnormality Cases', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f334ax', 'rsd_label' => 'Death due to Congenital Abnormality', 'dhis_key' => 's814'),
                        array('rsd_key' => 'f335ax', 'rsd_label' => 'Prematurity Cases', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f336ax', 'rsd_label' => 'Death due to Prematurity', 'dhis_key' => 's815'),
                        array('rsd_key' => 'f337ax', 'rsd_label' => 'Hypothermia Cases', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f338ax', 'rsd_label' => 'Death due to Hypothermia', 'dhis_key' => 's816'),
                        array('rsd_key' => 'f339ax', 'rsd_label' => 'Pneumonia <1 month Cases', 'dhis_key' => ''),
                        array('rsd_key' => 'f340ax', 'rsd_label' => 'Death due to Pneumonia <1 month', 'dhis_key' => ''),
                        array('rsd_key' => 'f341ax', 'rsd_label' => 'Death due to Low Birth Weight (<2.5kg or <2500 grams)', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f342ax', 'rsd_label' => 'Family Planning Counselling', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f401ax', 'rsd_label' => 'Total Family Planning Visits', 'dhis_key' => 's701'),
                        array('rsd_key' => 'f402ax', 'rsd_label' => 'Total Family Planning Visits referred by LHWs', 'dhis_key' => ''),
                        array('rsd_key' => 'f403ax', 'rsd_label' => 'Family Planning clients received COC Cycles', 'dhis_key' => 's702'),
                        array('rsd_key' => 'f404ax', 'rsd_label' => 'Family Planning Clients received POP cycles', 'dhis_key' => 's703'),
                        array('rsd_key' => 'f405ax', 'rsd_label' => 'Family Planning Clients received DMPA Injectable', 'dhis_key' => 's704'),
                        array('rsd_key' => 'f406ax', 'rsd_label' => 'Family Planning Clients received Net-En Injectable', 'dhis_key' => 's705'),
                        array('rsd_key' => 'f407ax', 'rsd_label' => 'Family Planning Clients received Sayana Press Injectable (At Facility)', 'dhis_key' => ''),
                        array('rsd_key' => 'f408ax', 'rsd_label' => 'Family Planning Clients received Condoms', 'dhis_key' => 's706'),
                        array('rsd_key' => 'f409ax', 'rsd_label' => 'Family Planning Clients received IUCDs', 'dhis_key' => 's707'),
                        array('rsd_key' => 'f410ax', 'rsd_label' => 'Family Planning Clients received PPIUCDs (from Labour Room)', 'dhis_key' => 'NA'),
                        array('rsd_key' => 'f411ax', 'rsd_label' => 'Family Planning Clients received Implants (from OPD)', 'dhis_key' => 's710'),
                        array('rsd_key' => 'f412ax', 'rsd_label' => 'Family Planning Clients received Implants (PPFP/PAFP) from Labour Room', 'dhis_key' => 's710'),
                        array('rsd_key' => 'f413ax', 'rsd_label' => 'Family Planning Clients undergo Vasectomy', 'dhis_key' => 's709'),
                        array('rsd_key' => 'f414ax', 'rsd_label' => 'Family Planning Clients undergo Tubal Ligation', 'dhis_key' => 's708'),
                        array('rsd_key' => 'f415ax', 'rsd_label' => 'Clients counsel for Family Planning in Maternal OPD (ANC & PNC)', 'dhis_key' => ''),
                        array('rsd_key' => 'f416ax', 'rsd_label' => 'Clients counsel for Family Planning in Pediatric OPD', 'dhis_key' => ''),
                        array('rsd_key' => 'f417ax', 'rsd_label' => 'Clients counsel for Family Planning in Immunization Room', 'dhis_key' => ''),
                        array('rsd_key' => 'f418ax', 'rsd_label' => 'Clients identified for Family Planning Counselling in pediatric Clinic', 'dhis_key' => ''),
                        array('rsd_key' => 'f419ax', 'rsd_label' => 'Clients identified for Immunization at OPD Clinic (ANC/PNC)', 'dhis_key' => ''),
                        array('rsd_key' => 'f501ax', 'rsd_label' => 'Pediatric OPD (1-11 months, Male + Female)', 'dhis_key' => 's3_401 + s3_406'),
                        array('rsd_key' => 'f502ax', 'rsd_label' => 'Pediatric OPD (12-59 months, Male + Female)', 'dhis_key' => 's3_402 + s3_407'),
                        array('rsd_key' => 'f503ax', 'rsd_label' => 'Paeds Referred Cases Attended (Referral In)', 'dhis_key' => ''),
                        array('rsd_key' => 'f504ax', 'rsd_label' => 'Paeds Referred Cases (Referral Out)', 'dhis_key' => 's13a_306'),
                        array('rsd_key' => 'f505ax', 'rsd_label' => 'Number of Diarrhea Cases <5 years OPD', 'dhis_key' => ''),
                        array('rsd_key' => 'f506ax', 'rsd_label' => 'Number of Dysentery Cases <5 years OPD', 'dhis_key' => ''),
                        array('rsd_key' => 'f507ax', 'rsd_label' => 'Number of Pneumonia Cases <5 years OPD', 'dhis_key' => 's403'),
                        array('rsd_key' => 'f508ax', 'rsd_label' => 'Number of Diarrhea Cases <5 years Indoor', 'dhis_key' => ''),
                        array('rsd_key' => 'f509ax', 'rsd_label' => 'Death due to Diarrhea Cases <5 years Indoor', 'dhis_key' => ''),
                        array('rsd_key' => 'f510ax', 'rsd_label' => 'Number of Dysentery Cases <5 years Indoor', 'dhis_key' => ''),
                        array('rsd_key' => 'f511ax', 'rsd_label' => 'Death due to Dysentery Cases <5 years Indoor', 'dhis_key' => ''),
                        array('rsd_key' => 'f512ax', 'rsd_label' => 'Number of Pneumonia Cases <5 years Indoor', 'dhis_key' => 's13b_03a'),
                        array('rsd_key' => 'f513ax', 'rsd_label' => 'Death due to Pneumonia Cases <5 years Indoor', 'dhis_key' => 's13b_03b'),
                        array('rsd_key' => 'f514ax', 'rsd_label' => 'Number of Infant Death (1-11 months)', 'dhis_key' => 's904'),
                        array('rsd_key' => 'f515ax', 'rsd_label' => 'Number of Child Death (1-4 years)', 'dhis_key' => ''),
                        array('rsd_key' => 'f516ax', 'rsd_label' => 'Number of Child Death (5-14 years)', 'dhis_key' => ''),
                        array('rsd_key' => 'f601ax', 'rsd_label' => 'Pregnant Women given TT-2 Vaccine', 'dhis_key' => 's503'),
                        array('rsd_key' => 'f602ax', 'rsd_label' => 'Children <12 months received 3rd Dose of Pentavalent Vaccine (Penta-3)', 'dhis_key' => 's501'),
                        array('rsd_key' => 'f603ax', 'rsd_label' => 'Children <12 months received 1st Dose of Measles Vaccine (Measles-1)', 'dhis_key' => 's502'),
                        array('rsd_key' => 'f701ax', 'rsd_label' => 'Number of Health Care Providers trained on clinical management and psychological support on Gender Based Violence (GBV)', 'dhis_key' => ''),
                        array('rsd_key' => 'f702aax', 'rsd_label' => 'Physical', 'dhis_key' => ''),
                        array('rsd_key' => 'f702bax', 'rsd_label' => 'Sexual', 'dhis_key' => ''),
                        array('rsd_key' => 'f703aax', 'rsd_label' => 'Came as Him/ Herself', 'dhis_key' => ''),
                        array('rsd_key' => 'f703bax', 'rsd_label' => 'Were referred by LHWs', 'dhis_key' => ''),
                        array('rsd_key' => 'f703cax', 'rsd_label' => 'Were referred by the police', 'dhis_key' => ''),
                        array('rsd_key' => 'f704ax', 'rsd_label' => 'Number of cases of violence (by type of case) which have received basic set of health services ', 'dhis_key' => ''),
                        array('rsd_key' => 'f705ax', 'rsd_label' => 'Number of deaths resulting from Gender Based Violence (GBV)', 'dhis_key' => ''),
                        array('rsd_key' => 'f8011ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8021ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8031ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8041ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8051ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8061ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8071ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8081ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8091ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                    );
                } elseif ($pro == 2) {
                    $arr = array(
                        array('rsd_key' => 'f101ax', 'rsd_label' => 'Number of Antenatal Care Visits (ANC-1)', 'dhis_key' => 's801'),
                        array('rsd_key' => 'f102ax', 'rsd_label' => 'Number of Antenatal Care Re-visits (ANC-2/3/4 or more)', 'dhis_key' => 'anc2+anc3+anc4'),
                        array('rsd_key' => 'f103ax', 'rsd_label' => 'ANC-1 Women with Hb<10g/dl', 'dhis_key' => 's802'),
                        array('rsd_key' => 'f104ax', 'rsd_label' => 'Number of Postnatal Care Visits (PNC-1)', 'dhis_key' => 's804'),
                        array('rsd_key' => 'f105ax', 'rsd_label' => 'Number of Postnatal Care Re-visits (PNC-2/3/4 or more)', 'dhis_key' => 'pnc2+pnc3+pnc4'),
                        array('rsd_key' => 'f106ax', 'rsd_label' => 'Number of Ultra Sounds for ANC', 'dhis_key' => ''),
                        array('rsd_key' => 'f201ax', 'rsd_label' => 'Total Obs/Gyne Cases', 'dhis_key' => 's3_1111'),
                        array('rsd_key' => 'f202ax', 'rsd_label' => 'OBS/GYN, Referred Cases Attended (Referral In)', 'dhis_key' => 's3_1114'),
                        array('rsd_key' => 'f203ax', 'rsd_label' => 'OBS/GYN, Referred Cases (Referral Out)', 'dhis_key' => 's13a_406'),
                        array('rsd_key' => 'f301ax', 'rsd_label' => 'Normal Vaginal Delivery (NVD)', 'dhis_key' => 's805'),
                        array('rsd_key' => 'f302ax', 'rsd_label' => 'Assisted Vaginal (Forceps/Vacuum) Delivery', 'dhis_key' => 's806'),
                        array('rsd_key' => 'f303ax', 'rsd_label' => 'Caesarean Section (C-Section)', 'dhis_key' => 's810'),
                        array('rsd_key' => 'f304ax', 'rsd_label' => 'Number of Women provided Misoprostol after delivery', 'dhis_key' => 'PW_given_Misoprostol_Tablet'),
                        array('rsd_key' => 'f305ax', 'rsd_label' => 'Number of Ante partum Hemorrhage (APH) Cases', 'dhis_key' => 's13b_38a'),
                        array('rsd_key' => 'f306ax', 'rsd_label' => 'Death due to Ante partum Hemorrhage (APH)', 'dhis_key' => 's13b_38b'),
                        array('rsd_key' => 'f307ax', 'rsd_label' => 'Number of Ectopic Pregnancies Cases', 'dhis_key' => 's13b_40a'),
                        array('rsd_key' => 'f308ax', 'rsd_label' => 'Death due to Ectopic Pregnancies ', 'dhis_key' => 's13b_40b'),
                        array('rsd_key' => 'f309ax', 'rsd_label' => 'Number of Postpartum Hemorrhage (PPH) Cases', 'dhis_key' => 's13b_41a'),
                        array('rsd_key' => 'f310ax', 'rsd_label' => 'Death due to Postpartum Hemorrhage (PPH)', 'dhis_key' => 's13b_41b'),
                        array('rsd_key' => 'f311ax', 'rsd_label' => 'Number of Pre-Eclampsia/Eclampsia (PE/E) Cases', 'dhis_key' => 's13b_42a'),
                        array('rsd_key' => 'f312ax', 'rsd_label' => 'Death due to Pre-Eclampsia/Eclampsia (PE/E)', 'dhis_key' => 's13b_42b'),
                        array('rsd_key' => 'f313ax', 'rsd_label' => 'Number of Prolonged Obstetric Labor (POL) Cases', 'dhis_key' => 's13b_43a'),
                        array('rsd_key' => 'f314ax', 'rsd_label' => 'Death due to Prolonged Obstetric Labor (POL)', 'dhis_key' => 's13b_43b'),
                        array('rsd_key' => 'f315ax', 'rsd_label' => 'Number of Puerperal Sepsis Cases', 'dhis_key' => 's13b_44a'),
                        array('rsd_key' => 'f316ax', 'rsd_label' => 'Death due to Puerperal Sepsis', 'dhis_key' => 's13b_44b'),
                        array('rsd_key' => 'f317ax', 'rsd_label' => 'Number of Rupture Uterus Cases', 'dhis_key' => 's13b_45a'),
                        array('rsd_key' => 'f318ax', 'rsd_label' => 'Death due to Rupture Uterus', 'dhis_key' => 's13b_45b'),
                        array('rsd_key' => 'f319ax', 'rsd_label' => 'Number of Live Births (LBs) in the facility', 'dhis_key' => 's807'),
                        array('rsd_key' => 'f320ax', 'rsd_label' => 'Number of Newborns to whom Chlorhexidine Gel is applied to Umbilical Stumps', 'dhis_key' => 'Neonates_received_ChlorhexidineCHX'),
                        array('rsd_key' => 'f321ax', 'rsd_label' => 'Number of Live Births with Low Birth Weight (<2.5kg or <2500 grams)', 'dhis_key' => 's808'),
                        array('rsd_key' => 'f322ax', 'rsd_label' => 'Number of Still Births (SBs) in the facility', 'dhis_key' => 's809'),
                        array('rsd_key' => 'f323ax', 'rsd_label' => 'Number of Intra Uterine Deaths (IUDs) in the facility', 'dhis_key' => ''),
                        array('rsd_key' => 'f324ax', 'rsd_label' => 'Number of Newborns with Birth Asphyxia ', 'dhis_key' => 'asphyxia'),
                        array('rsd_key' => 'f325ax', 'rsd_label' => 'Number of Newborns with Birth Asphyxia were successfully resuscitated ', 'dhis_key' => ''),
                        array('rsd_key' => 'f326ax', 'rsd_label' => 'Number of Pre-term Births (PTB)/Prematurity', 'dhis_key' => 'preterm_birth'),
                        array('rsd_key' => 'f327ax', 'rsd_label' => 'Number of Neonatal Deaths (0-28 days) in the facility', 'dhis_key' => ''),
                        array('rsd_key' => 'f328ax', 'rsd_label' => 'Birth Trauma Cases', 'dhis_key' => 'Birth_Trauma_Number_of_Admission'),
                        array('rsd_key' => 'f329ax', 'rsd_label' => 'Death due to Birth Trauma', 'dhis_key' => 's812'),
                        array('rsd_key' => 'f330ax', 'rsd_label' => 'Death due to Birth Asphyxia Cases', 'dhis_key' => 's811'),
                        array('rsd_key' => 'f331ax', 'rsd_label' => 'Bacterial Sepsis Cases', 'dhis_key' => 'Bacterial_Sepsis_Number_of_Admission'),
                        array('rsd_key' => 'f332ax', 'rsd_label' => 'Death due to Bacterial Sepsis', 'dhis_key' => 's813'),
                        array('rsd_key' => 'f333ax', 'rsd_label' => 'Congenital Abnormality Cases', 'dhis_key' => 'Congenital_Abnormality_Number_of_Admission'),
                        array('rsd_key' => 'f334ax', 'rsd_label' => 'Death due to Congenital Abnormality', 'dhis_key' => 's814'),
                        array('rsd_key' => 'f335ax', 'rsd_label' => 'Prematurity Cases', 'dhis_key' => 'Prematurity_Number_of_Admission'),
                        array('rsd_key' => 'f336ax', 'rsd_label' => 'Death due to Prematurity', 'dhis_key' => 's815'),
                        array('rsd_key' => 'f337ax', 'rsd_label' => 'Hypothermia Cases', 'dhis_key' => 'Hypothermia_Number_of_Admission'),
                        array('rsd_key' => 'f338ax', 'rsd_label' => 'Death due to Hypothermia', 'dhis_key' => 's816'),
                        array('rsd_key' => 'f339ax', 'rsd_label' => 'Pneumonia <1 month Cases', 'dhis_key' => ''),
                        array('rsd_key' => 'f340ax', 'rsd_label' => 'Death due to Pneumonia <1 month', 'dhis_key' => ''),
                        array('rsd_key' => 'f341ax', 'rsd_label' => 'Death due to Low Birth Weight (<2.5kg or <2500 grams)', 'dhis_key' => 'Low_Birth_Weight_Number_of_Deaths'),
                        array('rsd_key' => 'f342ax', 'rsd_label' => 'Family Planning Counselling', 'dhis_key' => 'Counseling_provided_on_FP'),
                        array('rsd_key' => 'f401ax', 'rsd_label' => 'Total Family Planning Visits', 'dhis_key' => 's701'),
                        array('rsd_key' => 'f402ax', 'rsd_label' => 'Total Family Planning Visits referred by LHWs', 'dhis_key' => ''),
                        array('rsd_key' => 'f403ax', 'rsd_label' => 'Family Planning clients received COC Cycles', 'dhis_key' => 's702'),
                        array('rsd_key' => 'f404ax', 'rsd_label' => 'Family Planning Clients received POP cycles', 'dhis_key' => 's703'),
                        array('rsd_key' => 'f405ax', 'rsd_label' => 'Family Planning Clients received DMPA Injectable', 'dhis_key' => 's704'),
                        array('rsd_key' => 'f406ax', 'rsd_label' => 'Family Planning Clients received Net-En Injectable', 'dhis_key' => 's705'),
                        array('rsd_key' => 'f407ax', 'rsd_label' => 'Family Planning Clients received Sayana Press Injectable (At Facility)', 'dhis_key' => ''),
                        array('rsd_key' => 'f408ax', 'rsd_label' => 'Family Planning Clients received Condoms', 'dhis_key' => 's706'),
                        array('rsd_key' => 'f409ax', 'rsd_label' => 'Family Planning Clients received IUCDs', 'dhis_key' => 's707'),
                        array('rsd_key' => 'f410ax', 'rsd_label' => 'Family Planning Clients received PPIUCDs (from Labour Room)', 'dhis_key' => 's707_1'),
                        array('rsd_key' => 'f411ax', 'rsd_label' => 'Family Planning Clients received Implants (from OPD)', 'dhis_key' => 's710'),
                        array('rsd_key' => 'f412ax', 'rsd_label' => 'Family Planning Clients received Implants (PPFP/PAFP) from Labour Room', 'dhis_key' => 's710'),
                        array('rsd_key' => 'f413ax', 'rsd_label' => 'Family Planning Clients undergo Vasectomy', 'dhis_key' => 's709'),
                        array('rsd_key' => 'f414ax', 'rsd_label' => 'Family Planning Clients undergo Tubal Ligation', 'dhis_key' => 's708'),
                        array('rsd_key' => 'f415ax', 'rsd_label' => 'Clients counsel for Family Planning in Maternal OPD (ANC & PNC)', 'dhis_key' => ''),
                        array('rsd_key' => 'f416ax', 'rsd_label' => 'Clients counsel for Family Planning in Pediatric OPD', 'dhis_key' => ''),
                        array('rsd_key' => 'f417ax', 'rsd_label' => 'Clients counsel for Family Planning in Immunization Room', 'dhis_key' => ''),
                        array('rsd_key' => 'f418ax', 'rsd_label' => 'Clients identified for Family Planning Counselling in pediatric Clinic', 'dhis_key' => ''),
                        array('rsd_key' => 'f419ax', 'rsd_label' => 'Clients identified for Immunization at OPD Clinic (ANC/PNC)', 'dhis_key' => ''),
                        array('rsd_key' => 'f501ax', 'rsd_label' => 'Pediatric OPD (1-11 months, Male + Female)', 'dhis_key' => 's3_401 + s3_406'),
                        array('rsd_key' => 'f502ax', 'rsd_label' => 'Pediatric OPD (12-59 months, Male + Female)', 'dhis_key' => 's3_402 + s3_407'),
                        array('rsd_key' => 'f503ax', 'rsd_label' => 'Paeds Referred Cases Attended (Referral In)', 'dhis_key' => ''),
                        array('rsd_key' => 'f504ax', 'rsd_label' => 'Paeds Referred Cases (Referral Out)', 'dhis_key' => 's13a_306'),
                        array('rsd_key' => 'f505ax', 'rsd_label' => 'Number of Diarrhea Cases <5 years OPD', 'dhis_key' => 'dia1'),
                        array('rsd_key' => 'f506ax', 'rsd_label' => 'Number of Dysentery Cases <5 years OPD', 'dhis_key' => 'dia2'),
                        array('rsd_key' => 'f507ax', 'rsd_label' => 'Number of Pneumonia Cases <5 years OPD', 'dhis_key' => 's403'),
                        array('rsd_key' => 'f508ax', 'rsd_label' => 'Number of Diarrhea Cases <5 years Indoor', 'dhis_key' => 'dia_a1'),
                        array('rsd_key' => 'f509ax', 'rsd_label' => 'Death due to Diarrhea Cases <5 years Indoor', 'dhis_key' => 'dia_d1'),
                        array('rsd_key' => 'f510ax', 'rsd_label' => 'Number of Dysentery Cases <5 years Indoor', 'dhis_key' => 'dia_a2'),
                        array('rsd_key' => 'f511ax', 'rsd_label' => 'Death due to Dysentery Cases <5 years Indoor', 'dhis_key' => 'dia_d2'),
                        array('rsd_key' => 'f512ax', 'rsd_label' => 'Number of Pneumonia Cases <5 years Indoor', 'dhis_key' => 's13b_03a'),
                        array('rsd_key' => 'f513ax', 'rsd_label' => 'Death due to Pneumonia Cases <5 years Indoor', 'dhis_key' => 's13b_03b'),
                        array('rsd_key' => 'f514ax', 'rsd_label' => 'Number of Infant Death (1-11 months)', 'dhis_key' => ''),
                        array('rsd_key' => 'f515ax', 'rsd_label' => 'Number of Child Death (1-4 years)', 'dhis_key' => ''),
                        array('rsd_key' => 'f516ax', 'rsd_label' => 'Number of Child Death (5-14 years)', 'dhis_key' => ''),
                        array('rsd_key' => 'f601ax', 'rsd_label' => 'Pregnant Women given TT-2 Vaccine', 'dhis_key' => 's503'),
                        array('rsd_key' => 'f602ax', 'rsd_label' => 'Children <12 months received 3rd Dose of Pentavalent Vaccine (Penta-3)', 'dhis_key' => 's501'),
                        array('rsd_key' => 'f603ax', 'rsd_label' => 'Children <12 months received 1st Dose of Measles Vaccine (Measles-1)', 'dhis_key' => 's502'),
                        array('rsd_key' => 'f701ax', 'rsd_label' => 'Number of Health Care Providers trained on clinical management and psychological support on Gender Based Violence (GBV)', 'dhis_key' => ''),
                        array('rsd_key' => 'f702aax', 'rsd_label' => 'Physical', 'dhis_key' => ''),
                        array('rsd_key' => 'f702bax', 'rsd_label' => 'Sexual', 'dhis_key' => ''),
                        array('rsd_key' => 'f703aax', 'rsd_label' => 'Came as Him/ Herself', 'dhis_key' => ''),
                        array('rsd_key' => 'f703bax', 'rsd_label' => 'Were referred by LHWs', 'dhis_key' => ''),
                        array('rsd_key' => 'f703cax', 'rsd_label' => 'Were referred by the police', 'dhis_key' => ''),
                        array('rsd_key' => 'f704ax', 'rsd_label' => 'Number of cases of violence (by type of case) which have received basic set of health services ', 'dhis_key' => ''),
                        array('rsd_key' => 'f705ax', 'rsd_label' => 'Number of deaths resulting from Gender Based Violence (GBV)', 'dhis_key' => ''),
                        array('rsd_key' => 'f8011ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8021ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8031ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8041ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8051ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8061ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8071ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8081ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                        array('rsd_key' => 'f8091ax', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),

                    );
                }
            } else {
                return view('errors/500');
            }

            $data['arr'] = $arr;
            $data['rsdData'] = Rsd_Model::getRsd_comparisonData($searchFilter);
            $data['PhcReport'] = Rsd_Model::getPhcReportData($searchFilter, $table);
//            $data['RsdLog'] = Rsd_Model::getRsdLog($searchFilter);
            $data['hf_type'] = $searchFilter['type'];
            return view('rsd.comparison', ['data' => $data]);
        } else {
            $trackarray["mainResult"] = "Error";
            $trackarray["result"] = "View Error - Access denied";
            Custom_Model::trackLogs($trackarray, "all_logs");
            return view('errors/403');
        }
    }

    public function editRsd(Request $request)
    {
        $id = $request->input('colId');
        $array = array();
        if (isset($id) && $id != '') {

            $array['formRSD_col_id'] = $request->input('colId');
            $array['hfCode'] = $request->input('hfCode');
            $array['hf_type'] = $request->input('hf_type');
            $array['reportingMonthYear'] = $request->input('reportingMonth');
            $array['dhis_variable'] = $request->input('dhis_variable');
            $array['dhis_value'] = $request->input('dhis_value');
            $array['rsd_variable'] = $request->input('variable_name');
            $array['variable_label'] = $request->input('variable_label');
            $array['rsd_oldvalue'] = $request->input('rsd_oldValue');
            $array['rsd_newvalue'] = $request->input('rsd_newValue');
            $array['colflag'] = 0;
            $array['createdBy'] = auth()->id();
            $array['createdDateTime'] = date('Y-m-d H:i:s');
            if (DB::connection('sqlsrv_uen_ph2')->table('FormRSD_edit')->insert($array)) {
                $edit_array = array();
                $edit_array[$array['rsd_variable']] = $array['rsd_newvalue'];
                $updateQuery = DB::connection('sqlsrv_uen_ph2')->table('FormRSD')
                    ->where('col_id', $id)
                    ->where('hfCode', $array['hfCode'])
                    ->where('reportingMonth', $array['reportingMonthYear'])
                    ->update($edit_array);
                if ($updateQuery) {
                    $result = array('Success', 'Successfully Inserted', 'success');
                } else {
                    $result = array('Error', 'Something went wrong in inserting data', 'danger');
                }
            } else {
                $result = array('Error', 'Something went wrong in inserting data', 'danger');
            }
        } else {
            $result = array('Error', 'Invalid Id', 'danger');
        }
        /*==========Log=============*/
        $trackarray = array(
            "activityName" => "Rs_App_Users",
            "action" => "Edit Rs_App_Users -> Function: Rs_App_Users/editAppUsers()",
            "mainResult" => $result[0],
            "result" => $result[1],
            "PostData" => $array,
            "affectedKey" => 'col_id=' . (isset($id) && $id != '' ? $id : ''),
            "idUser" => Auth::user()->id,
            "username" => Auth::user()->username,
        );
        Custom_Model::trackLogs($trackarray, "all_logs");
        /*==========Log=============*/
        return json_encode($result);
    }
}
