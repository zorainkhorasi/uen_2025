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
                               value="<?php echo(isset($rsd_data->hfCode) && $rsd_data->hfCode != '' ? $rsd_data->hfCode : (isset($data['hfCode']) && $data['hfCode'] != '' ? $data['hfCode'] : '')) ?>">
                        <input type="hidden" id="reportingMonth" name="reportingMonth"
                               value="<?php echo(isset($rsd_data->reportingMonth) && $rsd_data->reportingMonth != '' ? $rsd_data->reportingMonth : (isset($data['date']) && $data['date'] != '' ? $data['date'] : '')) ?>">
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
                                    foreach ($data['arr'] as $key=>$row){
                                    $total++;
                                    $rsd_label = $data['arr'][$key]['rsd_label'];
                                    $PhcReport_var = $data['arr'][$key]['dhis_key'];
                                    $rsd_var = $data['arr'][$key]['rsd_key'];
                                    $PhcReport_value = (isset($PhcReport_data->$PhcReport_var) && $PhcReport_data->$PhcReport_var != '' ? $PhcReport_data->$PhcReport_var : '');
                                    $rsd_value = (isset($rsd_data->$rsd_var) && $rsd_data->$rsd_var != '' ? $rsd_data->$rsd_var : '');
                                    $rsd = $rsd_value;


                                    if (str_ends_with($rsd_var, 'x')) {
                                        $rsd_var_parent = substr($rsd_var, 0, -2);
                                        $rsd_value_parent = (isset($rsd_data->$rsd_var_parent) && $rsd_data->$rsd_var_parent != '' ? $rsd_data->$rsd_var_parent : '');
                                        if ($rsd_value_parent == 2) {
                                            $rsd_value = 0;
                                            $rsd = '<span class="badge badge-primary">Not Reported</span>';
                                        } elseif ($rsd_value_parent == 3) {
                                            $rsd = '<span class="badge badge-secondary">Service Not Provided</span>';
                                        }
                                    }

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
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{asset(config('global.asset_path').'/js/owlcarousel/owl.carousel.js')}}"></script>
    <script src="{{asset(config('global.asset_path').'/js/ecommerce.js')}}"></script>
    <script>
        function editRsd(obj) {
            $(obj).parents('td').find('.change_mismatch').removeClass('hide');
            $(obj).parents('td').find('.span_mismatch').addClass('hide');
            $(obj).parents('td').find('.mismatch').addClass('hide');
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
                                $('.span_mismatch').removeClass('hide');
                                $(obj).parents('td').find('.span_mismatch').text(data['rsd_newValue']);
                                var m = '';
                                if (data['dhis_value'] == data['rsd_newValue']) {
                                    m = '<span class="badge badge-success">Matched</span>';
                                } else {
                                    m = '<span class="badge badge-danger">Mismatched</span>';
                                }
                                $(obj).parents('tr').find('.matching').html(m);
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
