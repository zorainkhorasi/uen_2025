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

            <input type="hidden" id="hfCode" name="hfCode" value="{{ request()->id }}">
            <div class="col-sm-12">
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
                                    <th>District</th>
                                    <th>Month-Year</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>HF Code</th>
                                    <th>HF Name</th>
                                    <th>District</th>
                                    <th>Month-Year</th>
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
            var data = {};
            data['hfCode'] = $('#hfCode').val();
            if (data['hfCode'] == '' || data['hfCode'] == undefined) {
                toastMsg('hfCode', 'Invalid HF Code', 'danger');
                return false;
            } else {
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
                        "url": '{{route('facilityDetailData')}}',
                        "method": "POST",
                        "data": data
                    },

                    columns: [
                        {"data": "hfCode", "class": "hfCode"},
                        {"data": "hfName", "class": "hfName"},
                        {"data": "districtName", "class": "districtName"},
                        {"data": "reportingMonth", "class": "reportingMonth"},
                        {
                            "data": "hfCode",
                            "render": function (myhfcode, type, row, meta) {
                                var url = '{{route('comparison')}}/' + myhfcode + '/' + row.MONTH + '-' + row.YEAR + '/' + row.hf_type;
                                return '<a href="' + url + '" class="btn btn-sm btn-secondary text-center" target="_blank">View Data</a>';
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
