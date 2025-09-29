@extends('layouts.simple.master')
@section('title',  trans('lang.lhw_main_summary_main_heading')  )

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>{{ trans('lang.lhw_main_summary_main_heading') }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ trans('lang.lhw_main_summary_main_heading') }}</li>
@endsection

@section('content')
    <div class="container-fluid">

        @include('lhw.filters')

        <section class="summary_section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <h5>Summary</h5>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-12">
                                    <table class="table  table-responsive table-hover table-bordered">
                                        <thead>
                                        <tr>
                                            <th>District</th>
                                            <th>Tehsil</th>
                                            <th>Total HFCs</th>
                                            <th>Contacted HFCs</th>
                                            <th>Contacted HFCs %</th>
                                            <th>Total LHWs</th>
                                            <th>Contacted LHWs</th>
                                            <th>Contacted LHWs %</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                        $total_tehsil=0;
                                        $total_hfc=0;
                                        $contacted_hfc=0;
                                        $total_lhw=0;
                                        $contacted_lhw=0;
                                        @endphp
                                        @if(isset($data['result']) && $data['result']!='')
                                            @foreach($data['result'] as $summary)
                                                @php
                                                    $total_tehsil+=$summary['total_tehsil'];
                                                    $total_hfc+=$summary['total_hfc'];
                                                    $contacted_hfc+=$summary['contacted_hfc'];
                                                    $total_lhw+=$summary['total_lhw'];
                                                    $contacted_lhw+=$summary['contacted_lhw'];
                                                @endphp
                                                <tr class="red">
                                                    <td class="p-1">{{$summary['district']}}</td>
                                                    <td class="p-1">{{isset($data['district_slug']) && $data['district_slug'] !=''
                                                && isset($summary->tehsil) ? $summary->tehsil :$summary['total_tehsil']}}</td>
                                                    <td class="p-1">{{$summary['total_hfc']}}</td>
                                                    <td class="p-1">{{$summary['contacted_hfc']}}</td>
                                                    <td class="p-1">{{$summary['percent_hfc']}}%</td>
                                                    <td class="p-1">{{$summary['total_lhw']}}</td>
                                                    <td class="p-1">{{$summary['contacted_lhw']}}</td>
                                                    <td class="p-1">{{$summary['percent_lhw']}}%</td>
                                                </tr>
                                            @endforeach
                                            <tr class="">
                                                <td class="p-1 text-primary">Total</td>
                                                <td class="p-1 text-primary">{{$total_tehsil}}</td>
                                                <td class="p-1 text-primary">{{$total_hfc}}</td>
                                                <td class="p-1 text-primary">{{$contacted_hfc}}</td>
                                                <td class="p-1 text-primary">{{Round(($contacted_hfc/$total_hfc)*100,2)}}%</td>
                                                <td class="p-1 text-primary">{{$total_lhw}}</td>
                                                <td class="p-1 text-primary">{{$contacted_lhw}}</td>
                                                <td class="p-1 text-primary">{{Round(($contacted_lhw/$total_lhw)*100,2)}}%</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>District</th>
                                            <th>Tehsil</th>
                                            <th>Total HFCs</th>
                                            <th>Contacted HFCs</th>
                                            <th>Contacted HFCs %</th>
                                            <th>Total LHWs</th>
                                            <th>Contacted LHWs</th>
                                            <th>Contacted LHWs %</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

@endsection
