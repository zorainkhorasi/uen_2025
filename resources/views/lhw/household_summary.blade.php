@extends('layouts.simple.master')
@section('title',  trans('lang.lhw_household_summary_main_heading')  )

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>{{ trans('lang.lhw_household_summary_main_heading') }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ trans('lang.lhw_household_summary_main_heading') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        @include('lhw.filters')

        <section class="summary_section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <h5>{{ trans('lang.lhw_household_summary_main_heading') }}</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive2">
                                <table class="display datatables" id="datatable_custom">
                                    <thead>
                                    <tr>
                                        <th>District</th>
                                        <th>Name of Reporting/<br>Associated Health Facility</th>
                                        <th>LHW Code</th>
                                        <th>LHW Name</th>
                                        <th>Households Identified <br><small>(against each LHW)</small></th>
                                        <th>Households Verified <br><small>(against each LHW)</small></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $total_lhw_identified=0;
                                        $total_lhw_verified=0;
                                    @endphp
                                    @if(isset($data['getMainSummary']) && $data['getMainSummary']!='')
                                        @foreach($data['getMainSummary'] as $summary)
                                            @php
                                                $total_lhw_identified+=$summary->hh_identified_each_lhw;
                                                $total_lhw_verified+=$summary->hh_verified_each_lhw;
                                            @endphp
                                            <tr>
                                                <td>{{(isset($summary->a101) && $summary->a101!=''?$summary->a101:'')}}</td>
                                                <td>{{(isset($summary->a103) && $summary->a103!=''?$summary->a103:'')}}</td>
                                                <td>{{(isset($summary->lhwCode) && $summary->lhwCode!=''?$summary->lhwCode:'')}}</td>
                                                <td>{{(isset($summary->a104n) && $summary->a104n!=''?$summary->a104n:'')}}</td>
                                                <td>{{(isset($summary->hh_identified_each_lhw) && $summary->hh_identified_each_lhw!=''?$summary->hh_identified_each_lhw:'')}}</td>
                                                <td>{{(isset($summary->hh_verified_each_lhw) && $summary->hh_verified_each_lhw!=''?$summary->hh_verified_each_lhw:'')}}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    </tbody>
                                    <tfoot>
                                    <tr class="">
                                        <td class="p-1 text-primary">Total</td>
                                        <td class="p-1 text-primary">-</td>
                                        <td class="p-1 text-primary">-</td>
                                        <td class="p-1 text-primary">-</td>
                                        <td class="p-1 text-primary">{{$total_lhw_identified}}</td>
                                        <td class="p-1 text-primary">{{$total_lhw_verified}}</td>
                                    </tr>
                                    <tr>
                                        <th>District</th>
                                        <th>Name of Reporting/<br>Associated Health Facility</th>
                                        <th>LHW Code</th>
                                        <th>LHW Name</th>
                                        <th>Households Identified <br><small>(against each LHW)</small></th>
                                        <th>Households Verified <br><small>(against each LHW)</small></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('script')
@endsection
