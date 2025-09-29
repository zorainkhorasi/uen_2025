@extends('layouts.simple.master')
@section('title',  trans('lang.health_care_providers_main_heading')  )

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>{{ trans('lang.health_care_providers_main_heading') }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ trans('lang.health_care_providers_main_heading') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax data source array start-->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header project-list">
                        <h5>{{ trans('lang.health_care_providers_dt_heading') }}</h5>
                        <span>{{ trans('lang.health_care_providers_dt_paragraph') }}</span>
                        @if(isset($data['permission'][0]->CanAdd) && $data['permission'][0]->CanAdd == 1)
                            <span><a class="btn btn-primary addbtn" href="javascript:void(0)"
                                     data-uk-modal="{target:'#addModal'}" id="add"> <i data-feather="plus-square"> </i>Create New Provider</a></span>
                        @endif

                    </div>
                    <div class="card-body">
                        <div class="table-responsive2">
                            <table class="display datatables" id="datatable_custom">

                                <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>Provider Code</th>
                                    <th>Provider Name</th>
                                    <th>District</th>
                                    <th>Tehsil</th>
                                    <th>Type</th>
                                    <th>Health Facility Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $sno = 1
                                @endphp
                                @foreach ($data['data'] as $keys=>$value)
                                    <tr data-id="{{ $value->id }}">
                                        <td>{{$sno++}}</td>
                                        <td>{{(isset($value->provider_code) && $value->provider_code!=''?$value->provider_code:'')}}</td>
                                        <td>{{(isset($value->provider_name) && $value->provider_name!=''?$value->provider_name:'')}}</td>
                                        <td>{{(isset($value->district) && $value->district!=''?$value->district:'')}}</td>
                                        <td>{{(isset($value->tehsil) && $value->tehsil!=''?$value->tehsil:'')}}</td>
                                        <td>{{(isset($value->hf_type) && $value->hf_type!=''?$value->hf_type:'')}}</td>
                                        <td>{{(isset($value->hf_name) && $value->hf_name!=''?$value->hf_name:'')}}</td>

                                        <td data-id="{{ $value->id }}"
                                            data-provider_code="{{ $value->provider_code }}"
                                            data-provider_name="{{ $value->provider_name }}">
                                            @if(isset($data['permission'][0]->CanDelete) && $data['permission'][0]->CanDelete == 1)
                                                <a href="javascript:void(0)" data-original-title="Delete"
                                                   title="Delete"
                                                   onclick="getDelete(this)">
                                                    <i data-feather="trash-2" class="txt-danger"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>SNo</th>
                                    <th>Provider Code</th>
                                    <th>Provider Name</th>
                                    <th>District</th>
                                    <th>Tehsil</th>
                                    <th>Type</th>
                                    <th>Health Facility Name</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Add Modal-->
                        @if(isset($data['permission'][0]->CanAdd) && $data['permission'][0]->CanAdd == 1)
                            <div class="modal fade text-left" id="addModal" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel_add"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content ">
                                        <div class="loader-box hide loader">
                                            <div class="loader-1 myloader"></div>
                                            <div class="myloader"> Loading..</div>
                                        </div>
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white" id="myModalLabel_add">Add Health Care
                                                Providers</h4>
                                            <button class="btn-close white" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body  ">

                                            <div class="mb-1 form-group">
                                                <label class="col-form-label" for="district">District: </label>
                                                <select class="form-control col-sm-12" id="district" required
                                                        onchange="changeDistrict()">
                                                    <option value="0">Select District</option>
                                                    @if (isset($data['districts']) && $data['districts'] != '')
                                                        @foreach ($data['districts'] as $keys=>$d)
                                                            <option
                                                                value="{{(isset($d->dist_id) && $d->dist_id!=''?$d->dist_id:$d->district)}}"
                                                                data-province="{{(isset($d->province) && $d->province!=''?$d->province:'')}}"
                                                                data-pro_id="{{(isset($d->pro_id) && $d->pro_id!=''?$d->pro_id:'')}}"
                                                                data-district="{{(isset($d->dist_id) && $d->dist_id!=''?$d->dist_id:'')}}"
                                                            >
                                                                {{(isset($d->district) && $d->district!=''?$d->district:'')}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="mb-1 form-group">
                                                <label class="col-form-label" for="tehsil">Tehsil: </label>
                                                <select class="form-control col-sm-12" id="tehsil" required
                                                        onchange="changeTehsil()">
                                                    <option value="0" data-tehsil="0">Select Tehsil</option>
                                                </select>
                                            </div>
                                            <div class="mb-1 form-group">
                                                <label class="col-form-label" for="hfcode">Health Facility: </label>
                                                <select class="form-control col-sm-12" id="hfcode" required>
                                                    <option value="0" data-hfname="0">Select Health Facility</option>
                                                </select>
                                            </div>
                                            <div class="mb-1 form-group">
                                                <label class="col-form-label" for="hf_type">Type: </label>
                                                <select class="form-control col-sm-12" id="hf_type" required>
                                                    <option value="0">Select Type</option>
                                                    <option value="Public">Public</option>
                                                    <option value="Private">Private</option>
                                                </select>
                                            </div>
                                            <div class="mb-1 form-group">
                                                <label class="col-form-label" for="provider_name">Provider
                                                    Name: </label>
                                                <input type="text" class="form-control provider_name" minlength="8"
                                                       id="provider_name" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-secondary" data-bs-dismiss="modal"
                                                    aria-label="Close" data-dismiss="modal">Close
                                            </button>
                                            <button type="button" class="btn btn-primary mybtn" onclick="addData()">Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Delete Modal-->
                        @if(isset($data['permission'][0]->CanDelete) && $data['permission'][0]->CanDelete == 1)
                            <!-- Delete Modal-->
                            <div class="modal fade text-left" id="deleteModal" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel_delete"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content ">
                                        <div class="loader-box hide loader">
                                            <div class="loader-1 myloader"></div>
                                            <div class="myloader"> Loading..</div>
                                        </div>
                                        <div class="modal-header bg-primary white">
                                            <h4 class="modal-title white" id="myModalLabel_delete">Delete Provider</h4>
                                            <input type="hidden" id="hiddenDeleteId" name="hiddenDeleteId" value="">
                                            <button class="btn-close white" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body  ">
                                            <div class="px-3">
                                                <p>Are you sure, you want to delete it?</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-secondary" data-bs-dismiss="modal"
                                                    aria-label="Close" data-dismiss="modal">Close
                                            </button>
                                            <button type="button" class="btn btn-primary mybtn" onclick="deleteData()">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Ajax data source array end-->
        </div>
    </div>

    <input type="hidden"
           value="{{ isset($data['district_slug']) && $data['district_slug'] !='' ? $data['district_slug'] :'0'}}"
           id="hidden_slug_district" name="hidden_slug_district">
    <input type="hidden"
           value="{{ isset($data['tehsil_slug']) && $data['tehsil_slug'] !='' ? $data['tehsil_slug'] :'0'}}"
           id="hidden_slug_teshil" name="hidden_slug_teshil">
    <input type="hidden" value="{{ isset($data['hfc_slug']) && $data['hfc_slug'] !='' ? $data['hfc_slug'] :'0'}}"
           id="hidden_slug_hf" name="hidden_slug_hf">
@endsection

@section('script')

    <script>
        $(document).ready(function () {
            $('.myselect2').select2({
                dropdownParent: $('#addModal')
            });
            $('.addbtn').click(function () {
                $('#addModal').modal('show');
            });
            $('#datatable_custom').DataTable({
                "displayLength": 50,
                "oSearch": {"sSearch": " "},
                autoFill: false,
                attr: {
                    autocomplete: 'off'
                },
                initComplete: function () {
                    $(this.api().table().container()).find('input[type="search"]').parent().wrap('<form>').parent().attr('autocomplete', 'off').css('overflow', 'hidden').css('margin', 'auto');
                },
            });
        });

        function changeDistrict() {
            var data = {};
            data['district'] = $('#district').val();
            if (data['district'] != '' && data['district'] != undefined && data['district'] != '0' && data['district'] != '$1') {
                CallAjax('{{ url('/Summary/changeDistrict/') }}', data, 'POST', function (res) {
                    var teshil = $('#hidden_slug_teshil').val();
                    var items = '<option value="0">Select All</option>';
                    if (res != '' && JSON.parse(res).length > 0) {
                        var response = JSON.parse(res);
                        try {
                            $.each(response, function (i, v) {
                                items += '<option value="' + v.tehsil_id + '"  ' + (teshil == v.tehsil_id ? 'selected' : '') + ' data-tehsil="' + v.tehsil + '">' + v.tehsil + ' (' + v.tehsil_id + ')</option>';
                            })
                        } catch (e) {
                        }
                    }
                    $('#tehsil').html('').html(items);
                    setTimeout(function () {
                        changeTehsil();
                    }, 200);
                });
            } else {
                $('#tehsil').html('');
            }
        }

        function changeTehsil() {
            var data = {};
            data['tehsil'] = $('#tehsil').val();
            if (data['tehsil'] != '' && data['tehsil'] != undefined && data['tehsil'] != '0' && data['tehsil'] != '$1') {
                CallAjax('{{ url('/Summary/changeTehsil/') }}', data, 'POST', function (res) {
                    var hfc = $('#hidden_slug_hf').val();
                    var items = '<option value="0">Select All</option>';
                    if (res != '' && JSON.parse(res).length > 0) {
                        var response = JSON.parse(res);
                        try {
                            $.each(response, function (i, v) {
                                items += '<option value="' + v.hfcode + '"  ' + (hfc == v.hfcode ? 'selected' : '') + ' data-hfname="' + v.hf_name + '">' + v.hf_name + ' (' + v.hfcode + ')</option>';
                            })
                        } catch (e) {
                        }
                    }
                    $('#hfcode').html('').html(items);
                });
            } else {
                $('#hfcode').html('');
            }
        }

        function addData() {
            $('#district').css('border', '1px solid #babfc7');
            $('#tehsil').css('border', '1px solid #babfc7');
            $('#hfcode').css('border', '1px solid #babfc7');
            $('#type').css('border', '1px solid #babfc7');
            $('#provider_name').css('border', '1px solid #babfc7');
            var flag = 0;
            var data = {};
            data['province'] = $('#district').find('option:selected').attr('data-province');
            data['pro_id'] = $('#district').find('option:selected').attr('data-pro_id');
            data['district'] = $('#district').find('option:selected').attr('data-district');
            data['dist_id'] = $('#district').val();
            data['tehsil'] = $('#tehsil').find('option:selected').attr('data-tehsil');
            data['tehsil_id'] = $('#tehsil').val();
            data['hf_name'] = $('#hfcode').find('option:selected').attr('data-hfname');
            data['hfcode'] = $('#hfcode').val();
            data['hf_type'] = $('#hf_type').val();
            data['provider_name'] = $('#provider_name').val();
            if (data['district'] == '' || data['district'] == undefined || data['district'] == 0) {
                $('#district').css('border', '1px solid red');
                flag = 1;
                toastMsg('District', 'Invalid District', 'danger');
                return false;
            }
            if (data['tehsil'] == '' || data['tehsil'] == undefined || data['tehsil'] == 0) {
                $('#tehsil').css('border', '1px solid red');
                flag = 1;
                toastMsg('Tehsil', 'Invalid Tehsil', 'danger');
                return false;
            }
            if (data['hfcode'] == '' || data['hfcode'] == undefined || data['hfcode'] == 0) {
                $('#hfcode').css('border', '1px solid red');
                flag = 1;
                toastMsg('Health Facility', 'Invalid Health Facility', 'danger');
                return false;
            }
            if (data['hf_type'] == '' || data['hf_type'] == undefined || data['hf_type'] == 0) {
                $('#hf_type').css('border', '1px solid red');
                flag = 1;
                toastMsg('Type', 'Invalid Health Facility Type', 'danger');
                return false;
            }
            if (data['provider_name'] == '' || data['provider_name'] == undefined || data['provider_name'] == 0) {
                $('#provider_name').css('border', '1px solid red');
                flag = 1;
                toastMsg('Provider Name', 'Invalid Provider Name', 'danger');
                return false;
            }
            if (flag == 0) {
                showloader();
                CallAjax('{{ url('/health_care_providers/addHC_provider') }}', data, 'POST', function (result) {
                    hideloader();
                    if (result !== '' && JSON.parse(result).length > 0) {
                        var response = JSON.parse(result);
                        try {
                            toastMsg(response[0], response[1], response[2]);
                            if (response[0] === 'Success') {
                                hideModal('addModal');
                                setTimeout(function () {
                                    window.location.reload();
                                }, 700);
                            }
                        } catch (e) {
                        }
                    } else {
                        toastMsg('Error', 'Something went wrong while uploading data', 'danger');
                    }
                });
            } else {
                toastMsg('User', 'Something went wrong', 'danger');
            }
        }

        function getDelete(obj) {
            $('#hiddenDeleteId').val($(obj).parents('tr').attr('data-id'));
            showModal('deleteModal');
        }

        function deleteData() {
            var data = {};
            data['id'] = $('#hiddenDeleteId').val();
            if (data['id'] == '' || data['id'] == undefined) {
                $('#hiddenDeleteId').css('border', '1px solid red');
                toastMsg('Error', 'Invalid User Id', 'danger');
                return false;
            } else {
                showloader();
                CallAjax('{{ url('/health_care_providers/deleteHC_provider') }}', data, 'POST', function (result) {
                    hideloader();
                    if (result !== '' && JSON.parse(result).length > 0) {
                        var response = JSON.parse(result);
                        try {
                            toastMsg(response[0], response[1], response[2]);
                            if (response[0] === 'Success') {
                                hideModal('deleteModal');
                                setTimeout(function () {
                                    window.location.reload();
                                }, 700);
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
