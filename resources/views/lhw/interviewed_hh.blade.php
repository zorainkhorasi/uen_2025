@extends('layouts.simple.master')
@section('title',  trans('lang.lhw_interviewed_hh_main_heading')  )

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>{{ trans('lang.lhw_interviewed_hh_main_heading') }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ trans('lang.lhw_interviewed_hh_main_heading') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        @include('lhw.filters')

        <section class="summary_section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <h5>{{ trans('lang.lhw_interviewed_hh_main_heading') }}</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive2">
                                <table class="display datatables" id="datatable_custom">
                                    <thead>
                                    <tr>
                                        <th>District</th>
                                        @if(isset($data['getMainSummary'][0]->tehsil) && $data['getMainSummary'][0]->tehsil!='')
                                            <th>Tehsil</th>
                                        @endif
                                        <th>Adolcent - Available</th>
                                        <th>Adolcent - Interviewed</th>
                                        <th>Male - Available</th>
                                        <th>Male - Interviewed</th>
                                        <th>MWRA - Available</th>
                                        <th>MWRA - Interviewed</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $total=0;
                                    @endphp
                                    @if(isset($data['getMainSummary']) && $data['getMainSummary']!='')
                                        @foreach($data['getMainSummary'] as $summary)
                                            <tr>
                                                <td>{{(isset($summary->district_name) && $summary->district_name!=''?$summary->district_name:'')}}</td>
                                                @if(isset($summary->tehsil) && $summary->tehsil!='')
                                                    <td>{{$summary->tehsil}}</td>
                                                @endif
                                                <td>{{(isset($summary->avail_adol) && $summary->avail_adol!=''?$summary->avail_adol:'')}}</td>
                                                <td>{{(isset($summary->interviewed_adol) && $summary->interviewed_adol!=''?$summary->interviewed_adol:'')}}</td>
                                                <td>{{(isset($summary->avail_male) && $summary->avail_male!=''?$summary->avail_male:'')}}</td>
                                                <td>{{(isset($summary->interviewed_male) && $summary->interviewed_male!=''?$summary->interviewed_male:'')}}</td>
                                                <td>{{(isset($summary->avail_mwra) && $summary->avail_mwra!=''?$summary->avail_mwra:'')}}</td>
                                                <td>{{(isset($summary->interviewed_mwra) && $summary->interviewed_mwra!=''?$summary->interviewed_mwra:'')}}</td>

                                            </tr>
                                        @endforeach
                                    @endif

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>District</th>
                                        @if(isset($data['getMainSummary'][0]->tehsil) && $data['getMainSummary'][0]->tehsil!='')
                                            <th>Tehsil</th>
                                        @endif
                                        <th>Adolcent - Available</th>
                                        <th>Adolcent - Interviewed</th>
                                        <th>Male - Available</th>
                                        <th>Male - Interviewed</th>
                                        <th>MWRA - Available</th>
                                        <th>MWRA - Interviewed</th>
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
