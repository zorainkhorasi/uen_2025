@extends('layouts.simple.master')
@section('title',  trans('lang.lhw_hh_verified_main_heading')  )

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>{{ trans('lang.lhw_hh_verified_main_heading') }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ trans('lang.lhw_hh_verified_main_heading') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        @include('lhw.filters')

        <section class="summary_section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <h5>{{ trans('lang.lhw_hh_verified_main_heading') }}</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive2">
                                <table class="display datatables" id="datatable_custom">
                                    <thead>
                                    <tr>
                                        <th>District</th>
                                        <th>Tehsil</th>
                                        <th>Name of Reporting/<br>Associated Health Facility</th>
                                        <th>Households<br><small>(against each HFC)</small></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $total=0;
                                    @endphp
                                    @if(isset($data['getMainSummary']) && $data['getMainSummary']!='')
                                        @foreach($data['getMainSummary'] as $summary)
                                            @php
                                                $total+=$summary->total_hh;
                                            @endphp
                                            <tr>
                                                <td>{{(isset($summary->a101) && $summary->a101!=''?$summary->a101:'')}}</td>
                                                <td>{{(isset($summary->tehsil) && $summary->tehsil!=''?$summary->tehsil:'')}}</td>
                                                <td>{{(isset($summary->hf_name) && $summary->hf_name!=''?$summary->hf_name:'')}}</td>
                                                <td>{{(isset($summary->total_hh) && $summary->total_hh!=''?$summary->total_hh:'')}}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    </tbody>
                                    <tfoot>
                                    <tr class="">
                                        <td class="p-1 text-primary">Total</td>
                                        <td class="p-1 text-primary">-</td>
                                        <td class="p-1 text-primary">-</td>
                                        <td class="p-1 text-primary">{{$total}}</td>
                                    </tr>
                                    <tr>
                                        <th>District</th>
                                        <th>Tehsil</th>
                                        <th>Name of Reporting/<br>Associated Health Facility</th>
                                        <th>Households<br><small>(against each HFC)</small></th>
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
