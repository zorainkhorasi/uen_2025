@extends('layouts.simple.master')
@section('title',  trans('lang.rsd_main_heading')  )

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset(config('global.asset_path').'/css/vendors/owlcarousel.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset(config('global.asset_path').'/css/vendors/rating.css')}}">
    <style>
        .mismatch {
            float: right;
            cursor: pointer;
        }
    </style>
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>{{ trans('lang.rsd_main_heading') }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ trans('lang.rsd_main_heading') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div>
            <div class="row product-page-main p-0">
                @php
                    if(isset( $data['rsdData'][0]) &&  $data['rsdData'][0]!=''){
                            $rsd_data= $data['rsdData'][0];
                    }else{
                            $rsd_data= '';
                    }
                    if(isset( $data['PhcReport'][0]) &&  $data['PhcReport'][0]!=''){
                            $PhcReport_data= $data['PhcReport'][0];
                    }else{
                            $PhcReport_data= '';
                    }

                        $total=0;
                        $correct=0;
                        $NA=0;
                        $sno=1;
                @endphp
                <input type="hidden" id="hfCode" name="hfCode" value="{{ request()->id }}">
                <div class="col-xl-7 col-md-6 ">
                    <div class="card">
                        <div class="card-header project-list">
                            <h5>{{ trans('lang.rsd_dt_heading') }}</h5>
                            <span>{{ trans('lang.rsd_dt_paragraph') }}</span>
                        </div>
                        <input type="hidden" id="colId" name="colId"
                               value="<?php echo(isset($rsd_data->col_id) && $rsd_data->col_id != '' ? $rsd_data->col_id : '0') ?>">
                        <input type="hidden" id="hfCode" name="hfCode"
                               value="<?php echo(isset($rsd_data->hfCode) && $rsd_data->hfCode != '' ? $rsd_data->hfCode : (isset($data['hfCode']) && $data['hfCode']!=''?$data['hfCode']:'')) ?>">
                        <input type="hidden" id="reportingMonth" name="reportingMonth"
                               value="<?php echo(isset($rsd_data->reportingMonth) && $rsd_data->reportingMonth != '' ? $rsd_data->reportingMonth : (isset($data['date']) && $data['date']!=''?$data['date']:'')) ?>">
                        <input type="hidden" id="hf_type" name="hf_type" value="<?php echo $data['hf_type'] ?>">
                        <div class="card-body">
                            <div class="table-responsive2">
                                <table class=" table-bordered" id="datatable_custom">
                                    <thead>
                                    <tr>
                                        <th width="5%">S#</th>
                                        <th width="10%">Variable (RSD)</th>
                                        <th width="35%">Indicator</th>
                                        <th width="20%">DHIS</th>
                                        <th width="20%">RSD</th>
                                        <th width="10%">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $arr = array(
                                        array('rsd_key' => 'f101', 'rsd_label' => 'Number of Antenatal Care Visits (ANC-1)', 'dhis_key' => 's801'),
                                        array('rsd_key' => 'f102', 'rsd_label' => 'Number of Antenatal Care Re-visits (ANC-2/3/4 or more)', 'dhis_key' => 's803'),
                                        array('rsd_key' => 'f103', 'rsd_label' => 'ANC-1 Women with Hb<10g/dl', 'dhis_key' => 's802'),
                                        array('rsd_key' => 'f104', 'rsd_label' => 'Number of Postnatal Care Visits (PNC-1)', 'dhis_key' => 's806'),
                                        array('rsd_key' => 'f105', 'rsd_label' => 'Number of Postnatal Care Re-visits (PNC-2/3/4 or more)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f106', 'rsd_label' => 'Number of Ultra Sounds for ANC', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f201', 'rsd_label' => 'Total Obs/Gyne Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f202', 'rsd_label' => 'OBS/GYN, Referred Cases Attended (Referral In)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f203', 'rsd_label' => 'OBS/GYN, Referred Cases (Referral Out)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f301', 'rsd_label' => 'Normal Vaginal Delivery (NVD)', 'dhis_key' => 's813'),
                                        array('rsd_key' => 'f302', 'rsd_label' => 'Assisted Vaginal (Forceps/Vacuum) Delivery', 'dhis_key' => 's814'),
                                        array('rsd_key' => 'f303', 'rsd_label' => 'Caesarean Section (C-Section)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f304', 'rsd_label' => 'Number of Women provided Misoprostol after delivery', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f305', 'rsd_label' => 'Number of Ante partum Hemorrhage (APH) Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f306', 'rsd_label' => 'Death due to Ante partum Hemorrhage (APH)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f307', 'rsd_label' => 'Number of Ectopic Pregnancies Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f308', 'rsd_label' => 'Death due to Ectopic Pregnancies ', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f309', 'rsd_label' => 'Number of Postpartum Hemorrhage (PPH) Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f310', 'rsd_label' => 'Death due to Postpartum Hemorrhage (PPH)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f311', 'rsd_label' => 'Number of Pre-Eclampsia/Eclampsia (PE/E) Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f312', 'rsd_label' => 'Death due to Pre-Eclampsia/Eclampsia (PE/E)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f313', 'rsd_label' => 'Number of Prolonged Obstetric Labor (POL) Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f314', 'rsd_label' => 'Death due to Prolonged Obstetric Labor (POL)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f315', 'rsd_label' => 'Number of Puerperal Sepsis Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f316', 'rsd_label' => 'Death due to Puerperal Sepsis', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f317', 'rsd_label' => 'Number of Rupture Uterus Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f318', 'rsd_label' => 'Death due to Rupture Uterus', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f319', 'rsd_label' => 'Number of Live Births (LBs) in the facility', 'dhis_key' => 's816'),
                                        array('rsd_key' => 'f320', 'rsd_label' => 'Number of Newborns to whom Chlorhexidine Gel is applied to Umbilical Stumps', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f321', 'rsd_label' => 'Number of Live Births with Low Birth Weight (<2.5kg or <2500 grams)', 'dhis_key' => 's817'),
                                        array('rsd_key' => 'f322', 'rsd_label' => 'Number of Still Births (SBs) in the facility', 'dhis_key' => 's821'),
                                        array('rsd_key' => 'f323', 'rsd_label' => 'Number of Intra Uterine Deaths (IUDs) in the facility', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f324', 'rsd_label' => 'Number of Newborns with Birth Asphyxia ', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f325', 'rsd_label' => 'Number of Newborns with Birth Asphyxia were successfully resuscitated ', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f326', 'rsd_label' => 'Number of Pre-term Births (PTB)/Prematurity', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f327', 'rsd_label' => 'Number of Neonatal Deaths (0-28 days) in the facility', 'dhis_key' => 's823'),
                                        array('rsd_key' => 'f328', 'rsd_label' => 'Birth Trauma Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f329', 'rsd_label' => 'Death due to Birth Trauma', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f330', 'rsd_label' => 'Death due to Birth Asphyxia Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f331', 'rsd_label' => 'Bacterial Sepsis Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f332', 'rsd_label' => 'Death due to Bacterial Sepsis', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f333', 'rsd_label' => 'Congenital Abnormality Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f334', 'rsd_label' => 'Death due to Congenital Abnormality', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f335', 'rsd_label' => 'Prematurity Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f336', 'rsd_label' => 'Death due to Prematurity', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f337', 'rsd_label' => 'Hypothermia Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f338', 'rsd_label' => 'Death due to Hypothermia', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f339', 'rsd_label' => 'Pneumonia <1 month Cases', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f340', 'rsd_label' => 'Death due to Pneumonia <1 month', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f341', 'rsd_label' => 'Death due to Low Birth Weight (<2.5kg or <2500 grams)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f342', 'rsd_label' => 'Family Planning Counselling', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f401', 'rsd_label' => 'Total Family Planning Visits', 'dhis_key' => 's701'),
                                        array('rsd_key' => 'f402', 'rsd_label' => 'Total Family Planning Visits referred by LHWs', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f403', 'rsd_label' => 'Family Planning clients received COC Cycles', 'dhis_key' => 's703'),
                                        array('rsd_key' => 'f404', 'rsd_label' => 'Family Planning Clients received POP cycles', 'dhis_key' => 's704'),
                                        array('rsd_key' => 'f405', 'rsd_label' => 'Family Planning Clients received DMPA Injectable', 'dhis_key' => 's705'),
                                        array('rsd_key' => 'f406', 'rsd_label' => 'Family Planning Clients received Net-En Injectable', 'dhis_key' => 's706'),
                                        array('rsd_key' => 'f407', 'rsd_label' => 'Family Planning Clients received Sayana Press Injectable (At Facility)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f408', 'rsd_label' => 'Family Planning Clients received Condoms', 'dhis_key' => 's707'),
                                        array('rsd_key' => 'f409', 'rsd_label' => 'Family Planning Clients received IUCDs', 'dhis_key' => 's708'),
                                        array('rsd_key' => 'f410', 'rsd_label' => 'Family Planning Clients received PPIUCDs (from Labour Room)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f411', 'rsd_label' => 'Family Planning Clients received Implants (from OPD)', 'dhis_key' => 's712'),
                                        array('rsd_key' => 'f412', 'rsd_label' => 'Family Planning Clients received Implants (PPFP/PAFP) from Labour Room', 'dhis_key' => 's712'),
                                        array('rsd_key' => 'f413', 'rsd_label' => 'Family Planning Clients undergo Vasectomy', 'dhis_key' => 's711'),
                                        array('rsd_key' => 'f414', 'rsd_label' => 'Family Planning Clients undergo Tubal Ligation', 'dhis_key' => 's710'),
                                        array('rsd_key' => 'f415', 'rsd_label' => 'Clients counsel for Family Planning in Maternal OPD (ANC & PNC)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f416', 'rsd_label' => 'Clients counsel for Family Planning in Pediatric OPD', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f417', 'rsd_label' => 'Clients counsel for Family Planning in Immunization Room', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f418', 'rsd_label' => 'Clients identified for Family Planning Counselling in pediatric Clinic', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f419', 'rsd_label' => 'Clients identified for Immunization at OPD Clinic (ANC/PNC)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f501', 'rsd_label' => 'Pediatric OPD (1-11 months, Male + Female)', 'dhis_key' => 's301m_b'),
                                        array('rsd_key' => 'f502', 'rsd_label' => 'Pediatric OPD (12-59 months, Male + Female)', 'dhis_key' => 's301m_c'),
                                        array('rsd_key' => 'f503', 'rsd_label' => 'Paeds Referred Cases Attended (Referral In)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f504', 'rsd_label' => 'Paeds Referred Cases (Referral Out)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f505', 'rsd_label' => 'Number of Diarrhea Cases <5 years OPD', 'dhis_key' => ''),
                                        array('rsd_key' => 'f506', 'rsd_label' => 'Number of Dysentery Cases <5 years OPD', 'dhis_key' => ''),
                                        array('rsd_key' => 'f507', 'rsd_label' => 'Number of Pneumonia Cases <5 years OPD', 'dhis_key' => 's403'),
                                        array('rsd_key' => 'f508', 'rsd_label' => 'Number of Diarrhea Cases <5 years Indoor', 'dhis_key' => ''),
                                        array('rsd_key' => 'f509', 'rsd_label' => 'Death due to Diarrhea Cases <5 years Indoor', 'dhis_key' => ''),
                                        array('rsd_key' => 'f510', 'rsd_label' => 'Number of Dysentery Cases <5 years Indoor', 'dhis_key' => ''),
                                        array('rsd_key' => 'f511', 'rsd_label' => 'Death due to Dysentery Cases <5 years Indoor', 'dhis_key' => ''),
                                        array('rsd_key' => 'f512', 'rsd_label' => 'Number of Pneumonia Cases <5 years Indoor', 'dhis_key' => 's1205_a'),
                                        array('rsd_key' => 'f513', 'rsd_label' => 'Death due to Pneumonia Cases <5 years Indoor', 'dhis_key' => 's1205_b'),
                                        array('rsd_key' => 'f514', 'rsd_label' => 'Number of Infant Death (1-11 months)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f515', 'rsd_label' => 'Number of Child Death (1-4 years)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f516', 'rsd_label' => 'Number of Child Death (5-14 years)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f601', 'rsd_label' => 'Pregnant Women given TT-2 Vaccine', 'dhis_key' => 's503'),
                                        array('rsd_key' => 'f602', 'rsd_label' => 'Children <12 months received 3rd Dose of Pentavalent Vaccine (Penta-3)', 'dhis_key' => 's501'),
                                        array('rsd_key' => 'f603', 'rsd_label' => 'Children <12 months received 1st Dose of Measles Vaccine (Measles-1)', 'dhis_key' => 's502'),
                                        array('rsd_key' => 'f701', 'rsd_label' => 'Number of Health Care Providers trained on clinical management and psychological support on Gender Based Violence (GBV)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f702a', 'rsd_label' => 'Physical', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f702b', 'rsd_label' => 'Sexual', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f703a', 'rsd_label' => 'Came as Him/ Herself', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f703b', 'rsd_label' => 'Were referred by LHWs', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f703c', 'rsd_label' => 'Were referred by the police', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f704', 'rsd_label' => 'Number of cases of violence (by type of case) which have received basic set of health services ', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f705', 'rsd_label' => 'Number of deaths resulting from Gender Based Violence (GBV)', 'dhis_key' => 'NA'),
                                        array('rsd_key' => 'f8011', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                                        array('rsd_key' => 'f8021', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                                        array('rsd_key' => 'f8031', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                                        array('rsd_key' => 'f8041', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                                        array('rsd_key' => 'f8051', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                                        array('rsd_key' => 'f8061', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                                        array('rsd_key' => 'f8071', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                                        array('rsd_key' => 'f8081', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),
                                        array('rsd_key' => 'f8091', 'rsd_label' => 'Stock-Out ', 'dhis_key' => ''),


                                    );
                                    foreach ( $data['arr'] as $key=>$row){
                                    $total++;
                                    $rsd_label =  $data['arr'][$key]['rsd_label'];
                                    $PhcReport_var =  $data['arr'][$key]['dhis_key'];
                                    $rsd_var =  $data['arr'][$key]['rsd_key'];
                                    $PhcReport_value = (isset($PhcReport_data->$PhcReport_var) && $PhcReport_data->$PhcReport_var != '' ? $PhcReport_data->$PhcReport_var : '');
                                    $rsd_value = (isset($rsd_data->$rsd_var) && $rsd_data->$rsd_var != '' ? $rsd_data->$rsd_var : '');
                                    $rsd = $rsd_value;
                                    if ($rsd_value == $PhcReport_value) {
                                        $correct++;
                                        $match = '<span class="badge badge-success">Matched</span>';
                                    } elseif ($PhcReport_value == '') {
                                        $NA++;
                                        $match = '<span class="badge badge-info">Not Available</span>';
                                    } else {
                                        $match = '<span class="badge badge-danger">Mismatched</span>';
                                        $rsd = '<div class="input-group change_mismatch hide">
                                                <input class="form-control rsd_value" type="text"
                                                       data-rsdoldValue="' . $rsd_value . '"
                                                       value="' . $rsd_value . '">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="saveRsd(this)">
                                                        <i data-feather="save" class="txt-primary edit"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="span_mismatch">' . $rsd_value . '</span>
                                            <span class="mismatch" onclick="editRsd(this)">
                                                <i data-feather="edit-3" class="txt-secondary edit"></i>
                                            </span>';
                                    }
                                    ?>
                                    <tr>
                                        <?php
                                        ?>
                                        <td><?php echo $sno++; ?></td>
                                        <td class="variable_name"><?php echo $rsd_var; ?></td>
                                        <td class="variable_label"><?php echo $rsd_label; ?></td>
                                        <td class="dhis"
                                            data-dhisVaribale="<?php echo $PhcReport_var; ?>"><?php echo $PhcReport_value; ?></td>
                                        <td class="rsd">
                                            <?php echo $rsd; ?>
                                        </td>
                                        <td class="matching">
                                            <?php echo $match; ?>
                                        </td>
                                    </tr>
                                    <?php }?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>S#</th>
                                        <th>Variable (RSD)</th>
                                        <th>Indicator</th>
                                        <th>DHIS</th>
                                        <th>RSD</th>
                                        <th>Status</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="p-t-15">
                                <h4>Data Accuracy = <?php echo round(($correct / $total) * 100, 2); ?>%</h4>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-xl-5  col-md-6">
                     <div class="card">
                         <div class="card-body">
                             <div class="product-slider owl-carousel owl-theme" id="sync1">
                                 <?php
                                 $url = 'https://vcoe1.aku.edu/uen_ph2/api/uploads/';
                                 $list = '';
                                 $list_icon = '';
                                 if (isset($rsd_data->f1image) && $rsd_data->f1image != '') {
                                     $exp1 = explode(';', $rsd_data->f1image);
                                     foreach ($exp1 as $i1) {
                                         $image_name = trim(substr($i1, strpos($i1, "-") + 1));
                                         if (isset($image_name) && $image_name != '') {
                                             $img1 = $url . $image_name;
                                             $list .= '<div class="item"><img src="' . $img1 . '" alt="f1image"><p>f1image</p></div>';
                                             $list_icon .= '<div class="item"><img src="' . $img1 . '" alt="f1image"><p>f1image</p></div>';
                                         }
                                     }
                                 }
                                 if (isset($rsd_data->f2image) && $rsd_data->f2image != '') {
                                     $exp2 = explode(';', $rsd_data->f2image);
                                     foreach ($exp2 as $i2) {
                                         $image_name = trim(substr($i2, strpos($i2, "-") + 1));
                                         if (isset($image_name) && $image_name != '') {
                                             $img2 = $url . $image_name;
                                             $list .= '<div class="item"><img src="' . $img2 . '" alt="f2image"><p>f2image</p></div>';
                                             $list_icon .= '<div class="item"><img src="' . $img2 . '" alt="f2image"><p>f2image</p></div>';
                                         }
                                     }
                                 }
                                 if (isset($rsd_data->f3image) && $rsd_data->f3image != '') {
                                     $exp3 = explode(';', $rsd_data->f3image);
                                     foreach ($exp3 as $i3) {
                                         $image_name = trim(substr($i3, strpos($i3, "-") + 1));
                                         if (isset($image_name) && $image_name != '') {
                                             $img3 = $url . $image_name;
                                             $list .= '<div class="item"><img src="' . $img3 . '" alt="f3image"><p>f3image</p></div>';
                                             $list_icon .= '<div class="item"><img src="' . $img3 . '" alt="f3image"><p>f3image</p></div>';
                                         }
                                     }
                                 }
                                 if (isset($rsd_data->f5image) && $rsd_data->f5image != '') {
                                     $exp5 = explode(';', $rsd_data->f5image);
                                     foreach ($exp5 as $i5) {
                                         $image_name = trim(substr($i5, strpos($i5, "-") + 1));
                                         if (isset($image_name) && $image_name != '') {
                                             $img5 = $url . $image_name;
                                             $list .= '<div class="item"><img src="' . $img5 . '" alt="f5image"><p>f5image</p></div>';
                                             $list_icon .= '<div class="item"><img src="' . $img5 . '" alt="f5image"><p>f5image</p></div>';
                                         }
                                     }
                                 }
                                 if (isset($rsd_data->f6image) && $rsd_data->f6image != '') {
                                     $exp6 = explode(';', $rsd_data->f6image);
                                     foreach ($exp6 as $i6) {
                                         $image_name = trim(substr($i6, strpos($i6, "-") + 1));
                                         if (isset($image_name) && $image_name != '') {
                                             $img6 = $url . $image_name;
                                             $list .= '<div class="item"><img src="' . $img6 . '" alt="f6image"><p>f6image</p></div>';
                                             $list_icon .= '<div class="item"><img src="' . $img6 . '" alt="f6image"><p>f6image</p></div>';
                                         }
                                     }
                                 }
                                 if (isset($rsd_data->f7image) && $rsd_data->f7image != '') {
                                     $exp7 = explode(';', $rsd_data->f7image);
                                     foreach ($exp7 as $i7) {
                                         $image_name = trim(substr($i7, strpos($i7, "-") + 1));
                                         if (isset($image_name) && $image_name != '') {
                                             $img7 = $url . $image_name;
                                             $list .= '<div class="item"><img src="' . $img7 . '" alt="f7image"><p>f7image</p></div>';
                                             $list_icon .= '<div class="item"><img src="' . $img7 . '" alt="f7image"><p>f7image</p></div>';
                                         }
                                     }
                                 }
                                 if (isset($rsd_data->f8image) && $rsd_data->f8image != '') {
                                     $exp8 = explode(';', $rsd_data->f8image);
                                     foreach ($exp8 as $i8) {
                                         $image_name = trim(substr($i8, strpos($i8, "-") + 1));
                                         if (isset($image_name) && $image_name != '') {
                                             $img8 = $url . $image_name;
                                             $list .= '<div class="item"><img src="' . $img8 . '" alt="f8image"><p>f8image</p></div>';
                                             $list_icon .= '<div class="item"><img src="' . $img8 . '" alt="f8image"><p>f8image</p></div>';
                                         }
                                     }
                                 }

                                 ?>
                                 <?php echo $list; ?>
                             </div>
                             <div class="owl-carousel owl-theme" id="sync2">
                                 <?php echo $list_icon; ?>
                             </div>
                         </div>
                     </div>
                 </div> --}}
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{asset(config('global.asset_path').'/js/owlcarousel/owl.carousel.js')}}"></script>
    <script src="{{asset(config('global.asset_path').'/js/ecommerce.js')}}"></script>
    <script>
        function editRsd(obj) {
            $('.change_mismatch').removeClass('hide');
            $('.span_mismatch').addClass('hide');
            $('.mismatch').addClass('hide');
        }

        function saveRsd(obj) {
            var flag = 0;
            var div = $(obj).parents('tr');
            var data = {};
            data['colId'] = $('#colId').val();
            data['hfCode'] = $('#hfCode').val();
            data['reportingMonth'] = $('#reportingMonth').val();
            data['hf_type'] = $('#hf_type').val();
            data['dhis_variable'] = div.find('.dhis').attr('data-dhisvaribale');
            data['dhis_value'] = div.find('.dhis').text();
            data['variable_name'] = div.find('.variable_name').text();
            data['variable_label'] = div.find('.variable_label').text();
            data['rsd_oldValue'] = div.find('.rsd_value').attr('data-rsdoldvalue');
            data['rsd_newValue'] = div.find('.rsd_value').val();
            if (data['colId'] == '' || data['colId'] == undefined) {
                flag = 1;
                toastMsg('Error', 'Invalid Id', 'danger');
                return false;
            }
            if (data['rsd_newValue'] == '' || data['rsd_newValue'] == undefined) {
                flag = 1;
                toastMsg('Error', 'Invalid Value', 'danger');
                return false;
            }
            if (flag == 0) {
                showloader();
                CallAjax('{{ route('editRsd') }}', data, 'POST', function (result) {
                    hideloader();
                    if (result !== '' && JSON.parse(result).length > 0) {
                        var response = JSON.parse(result);
                        try {
                            toastMsg(response[0], response[1], response[2]);
                            if (response[0] === 'Success') {
                                hideModal('addModal');
                                $('.change_mismatch').addClass('hide');
                                $('.mismatch').removeClass('hide');
                                $('.span_mismatch').removeClass('hide').text(data['rsd_newValue']);
                            }
                        } catch (e) {
                        }
                    } else {
                        toastMsg('Error', 'Something went wrong while uploading data', 'danger');
                    }
                });
            }

        }
    </script>
@endsection
