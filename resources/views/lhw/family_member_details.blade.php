@extends('layouts.simple.master')
@section('title',  trans('lang.lhw_family_members_details_main_heading')  )

@section('css')

@endsection

@section('style')
    <style>
        .c1 {
            background-color: #bdbdbd !important;
        }

        .c2 {
            background-color: #c9c9c9 !important;
        }

        .c3 {
            background-color: #b3b3b3 !important;
        }

        tr th {
            padding: 7px 2px !important;
            border: 1px solid black !important;
        }

        tr td {
            padding: 0 2px !important;
            border: 1px solid black !important;
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3>{{ trans('lang.lhw_family_members_details_main_heading') }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ trans('lang.lhw_family_members_details_main_heading') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        @include('lhw.filters')

        <section class="summary_section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <h5>{{ trans('lang.lhw_family_members_details_main_heading') }}</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive2">
                                <table class="display datatables" id="datatable_custom">
                                    <thead>
                                    <tr>
                                        <th rowspan="2" class="d1" width="10%">District</th>
                                        @if(isset($data['getMainSummary'][0]->tehsil) && $data['getMainSummary'][0]->tehsil!='')
                                            <th rowspan="2" class="d1" width="10%">Tehsil</th>
                                        @endif
                                        <th colspan="3" class="text-center c1" width="7%">Child 0 to 4 years</th>
                                        <th colspan="3" class="text-center c2" width="7%">Child 5 to 10 years</th>
                                        <th colspan="3" class="text-center c3" width="7%">Adolcent</th>
                                        <th rowspan="2" width="7%">Female 49+</th>
                                        <th rowspan="2" width="7%">WRA</th>
                                        <th rowspan="2" width="7%">MWRA</th>
                                        <th rowspan="2" width="7%">Male 18+</th>
                                    </tr>
                                    <tr>
                                        <th class="c1">Male</th>
                                        <th class="c1">Female</th>
                                        <th class="c1">Total</th>
                                        <th class="c2">Male</th>
                                        <th class="c2">Female</th>
                                        <th class="c2">Total</th>
                                        <th class="c3">Male</th>
                                        <th class="c3">Female</th>
                                        <th class="c3">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $total=0;
                                    @endphp
                                    @if(isset($data['getMainSummary']) && $data['getMainSummary']!='')
                                        @foreach($data['getMainSummary'] as $summary)
                                            <tr>
                                                <td class="d1"
                                                    width="10%">{{(isset($summary->district_name) && $summary->district_name!=''?$summary->district_name:'')}}</td>
                                                @if(isset($summary->tehsil) && $summary->tehsil!='')
                                                    <td class="d1" width="10%">{{$summary->tehsil}}</td>
                                                @endif
                                                <td class="c1"
                                                    width="7%">{{(isset($summary->total_0_4_male) && $summary->total_0_4_male!=''?$summary->total_0_4_male:'')}}</td>
                                                <td class="c1"
                                                    width="7%">{{(isset($summary->total_0_4_female) && $summary->total_0_4_female!=''?$summary->total_0_4_female:'')}}</td>
                                                <td class="c1"
                                                    width="7%">{{(isset($summary->child_0_4) && $summary->child_0_4!=''?$summary->child_0_4:'')}}</td>
                                                <td class="c2"
                                                    width="7%">{{(isset($summary->total_5_10_male) && $summary->total_5_10_male!=''?$summary->total_5_10_male:'')}}</td>
                                                <td class="c2"
                                                    width="7%">{{(isset($summary->total_5_10_female) && $summary->total_5_10_female!=''?$summary->total_5_10_female:'')}}</td>
                                                <td class="c2"
                                                    width="7%">{{(isset($summary->child_5_10) && $summary->child_5_10!=''?$summary->child_5_10:'')}}</td>
                                                <td class="c3"
                                                    width="7%">{{(isset($summary->total_adol_male) && $summary->total_adol_male!=''?$summary->total_adol_male:'')}}</td>
                                                <td class="c3"
                                                    width="7%">{{(isset($summary->total_adol_female) && $summary->total_adol_female!=''?$summary->total_adol_female:'')}}</td>
                                                <td class="c3"
                                                    width="7%">{{(isset($summary->adol) && $summary->adol!=''?$summary->adol:'')}}</td>
                                                <td width="7%">{{(isset($summary->female_49) && $summary->female_49!=''?$summary->female_49:'')}}</td>
                                                <td width="7%">{{(isset($summary->wra) && $summary->wra!=''?$summary->wra:'')}}</td>
                                                <td width="7%">{{(isset($summary->mwra) && $summary->mwra!=''?$summary->mwra:'')}}</td>
                                                <td width="7%">{{(isset($summary->male) && $summary->male!=''?$summary->male:'')}}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    </tbody>{{--
                                    <tfoot>
                                    <tr>
                                        <th></th>
                                        <th class="c1" width="7%">Male</th>
                                        <th class="c1" width="7%">Female</th>
                                        <th class="c1" width="7%">Total</th>
                                        <th class="c2" width="7%">Male</th>
                                        <th class="c2" width="7%">Female</th>
                                        <th class="c2" width="7%">Total</th>
                                        <th class="c3" width="7%">Male</th>
                                        <th class="c3" width="7%">Female</th>
                                        <th class="c3" width="7%">Total</th>
                                        <th width="7%"></th>
                                        <th width="7%"></th>
                                        <th width="7%"></th>
                                        <th width="7%"></th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2" class="d1" width="10%">District</th>
                                        <th colspan="3" class="text-center c1">Child 0 to 4 years</th>
                                        <th colspan="3" class="text-center c2">Child 5 to 10 years</th>
                                        <th colspan="3" class="text-center c3">Adolcent</th>
                                        <th rowspan="2" width="7%">Female 49+</th>
                                        <th rowspan="2" width="7%">WRA</th>
                                        <th rowspan="2" width="7%">MWRA</th>
                                        <th rowspan="2" width="7%">Male 18+</th>
                                    </tr>
                                    </tfoot>--}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
