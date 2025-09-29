@extends('layouts.simple.master')
@section('title',  trans('lang.rsd_main_heading')  )

@section('css')

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
        <div class="row">
            <!-- Ajax data source array start-->
            <div class="col-sm-3">
                <div class="product-sidebar">
                    <div class="filter-section">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0 f-w-600">Filters<span class="pull-right"><i
                                            class="fa fa-chevron-down toggle-data"></i></span></h6>
                            </div>
                            <div class="left-filter">
                                <div class="card-body filter-cards-view animate-chk ">
                                    <div class="product-filter">
                                        <h6 class="f-w-600">Province</h6>
                                        <div class="checkbox-animated mt-0">
                                            <label class="d-block" for="edo-ani1">
                                                <input class="radio_animated" id="rsd_province1" name="rsd_province"
                                                       checked="checked"
                                                       type="radio" data-original-title="Punjab" title="Punjab"
                                                       value="Khyber Pakhtunkhwa"
                                                       data-bs-original-title="Punjab">Khyber Pakhtunkhwa
                                            </label>
                                           {{-- <label class="d-block" for="rsd_province2">
                                                <input class="radio_animated" id="rsd_province2" name="rsd_province"
                                                       type="radio" data-original-title="Sindh" title="Sindh"
                                                       value="Sindh"
                                                       data-bs-original-title="Sindh">Sindh
                                            </label>
                                            <label class="d-block" for="rsd_province3">
                                                <input class="radio_animated" id="rsd_province3" name="rsd_province"
                                                       type="radio" data-original-title="Balochistan"
                                                       value="Balochistan"
                                                       title="Balochistan" data-bs-original-title="Balochistan">Balochistan
                                            </label>--}}
                                        </div>
                                    </div>
                                    <div class="product-filter">
                                        <h6 class="f-w-600">Type</h6>
                                        <div class="checkbox-animated mt-0">
                                            <label class="d-block" for="rsd_type1">
                                                <input class="radio_animated" id="rsd_type1" name="rsd_type"
                                                       checked="checked"
                                                       type="radio" data-original-title="Primary" title="Primary"
                                                       value="Primary"
                                                       data-bs-original-title="Primary">Primary
                                            </label>
                                            <label class="d-block" for="rsd_type2">
                                                <input class="radio_animated" id="rsd_type2" name="rsd_type"
                                                       type="radio" data-original-title="Secondary" title="Secondary"
                                                       value="Secondary"
                                                       data-bs-original-title="Secondary">Secondary
                                            </label>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary text-center w-100" type="button"
                                            onclick="getData()"
                                            data-bs-original-title="Search" title="Search">Search Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <div class="card">
                    <div class="card-header project-list">
                        <h5>{{ trans('lang.rsd_dt_heading') }}</h5>
                        <span>{{ trans('lang.rsd_dt_paragraph') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive2">
                            <table class="display datatables" id="datatable_custom">
                                <thead>
                                <tr>
                                    <th>HF Code</th>
                                    <th>HF Name</th>
                                    <th>Province</th>
                                    <th>District</th>
                                    <th>Tehsil</th>
                                    <th>UC</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>HF Code</th>
                                    <th>HF Name</th>
                                    <th>Province</th>
                                    <th>District</th>
                                    <th>Tehsil</th>
                                    <th>UC</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Ajax data source array end-->
        </div>
    </div>

@endsection

@section('script')

    <script>
        $(document).ready(function () {
            getData();
        });

        function getData() {
            var flag = 0;
            var data = {};
            data['province'] = $('input[name="rsd_province"]:checked').val();
            data['type'] = $('input[name="rsd_type"]:checked').val();
            if (data['province'] == '' || data['province'] == undefined) {
                flag = 1;
                toastMsg('Province', 'Please select province', 'danger');
                return false;
            }
            if (data['type'] == '' || data['type'] == undefined) {
                flag = 1;
                toastMsg('Type', 'Please select type', 'danger');
                return false;
            }
            if (flag == 0) {
                showloader();
                $('#datatable_custom').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,

                    oSearch: {"sSearch": " "},
                    autoFill: false,
                    attr: {
                        autocomplete: 'off'
                    },
                    initComplete: function () {
                        $(this.api().table().container()).find('input[type="search"]').parent().wrap('<form>').parent().attr('autocomplete', 'off').css('overflow', 'hidden').css('margin', 'auto');
                        setTimeout(function () {
                            hideloader();
                        }, 500);
                    },


                    ajax: {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        "url": '{{route('getRsdData')}}',
                        "method": "POST",
                        "data": data
                    },
                    columns: [
                        {"data": "hfcode", "class": "hfcode"},
                        {"data": "hf_name", "class": "hf_name"},
                        {"data": "province", "class": "province"},
                        {"data": "district", "class": "district"},
                        {"data": "tehsil", "class": "tehsil"},
                        {"data": "uc_name", "class": "uc_name"},
                        {"data": "hf_type", "class": "hf_type"},
                        {
                            "data": "hfcode",
                            "render": function (myhfcode, type, row, meta) {
                                return '<a href="{{route('facilityDetail')}}/' + myhfcode + '" class="btn btn-sm btn-secondary text-center" target="_blank">View Months</a>';
                            },
                        }
                    ],
                    order: [[0, "asc"]],
                    displayLength: 50,
                    lengthMenu: [25, 50, 75, 100],
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'copyHtml5', text: 'Copy', className: 'btn btn-sm btn-primary'

                        }, {
                            extend: 'csvHtml5', text: 'CSV', className: 'btn btn-sm btn-primary'
                        },
                        {
                            extend: 'pdfHtml5', text: 'PDF', className: 'btn btn-sm btn-primary'
                        }
                    ]
                });


            }

        }
    </script>
@endsection
