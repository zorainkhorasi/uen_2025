@extends('layouts.simple.master')
@section('title',  trans('lang.hfa_summary_main_heading')  )
@section('css')
@endsection
@section('style')
@endsection
@section('breadcrumb-title')
    <h3>{{ trans('lang.hfa_summary_main_heading') }} - Details</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ trans('lang.hfa_summary_main_heading') }} - Details</li>
@endsection
@section('content')
    <div class="container-fluid">


        <div class="row">
            <!-- Ajax data source array start-->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header project-list">
                        <h5>Health Facility Summary</h5>
                        <span>HFA Details</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive2">
                            <table class="display datatables table-striped table-hover" id="datatable_custom">

                                <thead>
                                <tr>
                                    <th width="5%">SNo</th>
                                    <th width="10%">HF Code</th>
                                    <th width="15%">District</th>
                                    <th width="20%">Tehsil</th>
                                    <th width="20%">HF Name</th>
                                    <th width="15">Type</th>
                                    <th width="15%">DataCollected Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $s=0;
                                @endphp
                                @if (isset($data['getData']) && $data['getData'] != '')
                                    @foreach ($data['getData'] as $k=>$r)
                                        @php
                                            $s++;
                                                if(isset($r->hfcode) && $r->hfcode!=''){
                                                    $cluster_no=$r->hfcode;
                                                }else{
                                                    $cluster_no=0;
                                                }
                                        @endphp
                                        <tr>
                                            <td width="5%">{{$s}}</td>
                                            <td width="10%">{{isset($cluster_no) && $cluster_no!=''?$cluster_no:'-'}}</td>
                                            <td width="15%">{{isset($r->district) &&$r->district!=''?ucwords(strtolower($r->district)):'-'}}</td>
                                            <td width="20%">{{isset($r->tehsil) &&$r->tehsil!=''?ucwords(strtolower($r->tehsil)):'-'}}</td>
                                            <td width="20%">{{isset($r->hf_name) &&$r->hf_name!=''?ucwords(strtolower($r->hf_name)):'-'}}</td>
                                            <td width="15%">{{isset($r->hf_type) &&$r->hf_type!=''?ucwords(strtolower($r->hf_type)):'-'}}</td>
                                            <td width="15%"> @php
                                                    $status = '';
                                                    if(isset($r->dc) && $r->dc>=1 ){
                                                        $status='<a href="javascript:void(0)" class="btn btn-xs btn-success text-center">Completed</a>';
                                                    }  else{
                                                       $status='<a href="javascript:void(0)" class="btn btn-xs btn-danger text-center">Remaining</a>';
                                                    }
                                                    echo $status;
                                                @endphp</td>

                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th width="5%">SNo</th>
                                    <th width="10%">HF Code</th>
                                    <th width="15%">District</th>
                                    <th width="20%">Tehsil</th>
                                    <th width="20%">HF Name</th>
                                    <th width="15">Type</th>
                                    <th width="15%">DataCollected Status</th>
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
            $('#datatable_custom').DataTable({
                "oSearch": {"sSearch": " "},
                autoFill: false,
                attr: {
                    autocomplete: 'off'
                },
                initComplete: function () {
                    $(this.api().table().container()).find('input[type="search"]').parent().wrap('<form>').parent().attr('autocomplete', 'off').css('overflow', 'hidden').css('margin', 'auto');
                },
                displayLength: 50,
                lengthMenu: [25, 50, 75, 100],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5', text: 'Copy', className: 'btn btn-sm btn-primary'

                    }, {
                        extend: 'csvHtml5', text: 'CSV', className: 'btn btn-sm btn-primary'
                    }
                ]
            });
        });
    </script>
@endsection
