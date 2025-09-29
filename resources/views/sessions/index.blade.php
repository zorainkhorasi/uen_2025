@extends('layouts.simple.master')
@section('title',  trans('lang.lhw_household_summary_main_heading')  )

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Sessions Summary</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Sessions Summary</li>
@endsection

@section('content')
    <div class="container-fluid">
        @include('sessions.filters')

        <section class="summary_section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <h5>VHC & WSG Sessions Summary</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive2">
                                <table class="display datatables" id="datatable_custom">
                                    <thead>
                                    <tr>
                                        <th>District Name</th>
                                        <th>LHW Code</th>
                                        <th>LHW Name</th>
                                        <th>VHC Sessions Count</th>
                                        <th>WSG Sessions Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $total_vhc_count = 0;
                                        $total_wsg_count = 0;
                                    @endphp
                                    @if(isset($data['getMainSummary']) && $data['getMainSummary']!='')
                                        @foreach($data['getMainSummary'] as $summary)
                                            @php
                                                $total_vhc_count += $summary->vhc_count;
                                                $total_wsg_count += $summary->WSG_count;
                                            @endphp
                                            <tr>
                                                <td>{{(isset($summary->districtName) && $summary->districtName!=''?$summary->districtName:'')}}</td>
                                                <td>{{(isset($summary->lhwCode) && $summary->lhwCode!=''?$summary->lhwCode:'')}}</td>
                                                <td>{{(isset($summary->lhwName) && $summary->lhwName!=''?$summary->lhwName:'')}}</td>
                                                <td>{{(isset($summary->vhc_count) && $summary->vhc_count!=''?$summary->vhc_count:0)}}</td>
                                                <td>{{(isset($summary->WSG_count) && $summary->WSG_count!=''?$summary->WSG_count:0)}}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    </tbody>
                                    <tfoot>
                                    <tr class="">
                                        <td class="p-1 text-primary">Total</td>
                                        <td class="p-1 text-primary">-</td>
                                        <td class="p-1 text-primary">-</td>
                                        <td class="p-1 text-primary">{{$total_vhc_count}}</td>
                                        <td class="p-1 text-primary">{{$total_wsg_count}}</td>
                                    </tr>
                                    <tr>
                                        <th>District Name</th>
                                        <th>LHW Code</th>
                                        <th>LHW Name</th>
                                        <th>VHC Sessions Count</th>
                                        <th>WSG Sessions Count</th>
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
