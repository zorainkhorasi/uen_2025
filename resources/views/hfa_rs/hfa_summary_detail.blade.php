@extends('layouts.simple.master')
@section('title',  trans('lang.hfa_summary_main_heading')  )
@section('css')

@endsection
@section('style')
@endsection
@section('breadcrumb-title')
    <h3>{{ trans('lang.hfa_summary_main_heading') }}</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ trans('lang.hfa_summary_main_heading') }}</li>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row size-column">
            @php
                $colors = array('primary', 'warning', 'danger', 'success', 'info', 'mycolor1', 'mycolor2', 'mycolor3',
                            'danger', 'success', 'mycolor3', 'mycolor1', 'info', 'mycolor2', 'primary', 'warning',
                            'info', 'danger', 'mycolor1', 'success', 'primary', 'warning', 'mycolor2', 'mycolor3',
                            'primary', 'warning', 'danger', 'success', 'info', 'mycolor1', 'mycolor2', 'mycolor3',
                            'danger', 'success', 'mycolor3', 'mycolor1', 'info', 'mycolor2', 'primary', 'warning',
                            'info', 'danger', 'mycolor1', 'success', 'primary', 'warning', 'mycolor2', 'mycolor3');
            @endphp
            <div class="col-xl-3 col-md-3 col-sm-12">
                <div class="card total-users">
                    <div class="card-header card-no-border">
                        <h5>Total</h5>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="fa fa-spin fa-cog"></i></li>
                                <li><i class="icofont icofont-maximize full-card"></i></li>
                                <li><i class="icofont icofont-minus minimize-card"></i></li>
                                <li onclick="total_chart()"><i class="icofont icofont-refresh reload-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="apex-chart-container goal-status text-center row">
                            <div class="rate-card col-xl-12">
                                <div class="goal-chart">
                                    <div id="total_cluster"></div>
                                </div>
                                <div class="goal-end-point">
                                    <ul>
                                        <li class="mt-0 pt-0">
                                            <h6 class="font-primary">Total</h6>
                                            <h6 class="f-w-400">
                                                @if (isset($data['totalcluster']) && $data['totalcluster'] != ''&& $data['totalcluster'] != '0')
                                                    {{$data['totalcluster']}}
                                                @else
                                                    @php
                                                      $data['totalcluster']=100;
                                                    @endphp
                                                    {{$data['totalcluster']}}
                                                @endif
                                            </h6>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @php
                                $s=0;
                            @endphp
                            @if (isset($data['total']) && $data['total'] != '')
                                @foreach ($data['total'] as $keys=>$total)
                                    @php
                                        $s++;
                                    @endphp
                                    <div class="project-status mt-4">
                                        <a href="javascript:void(0)">
                                            <div class="media mb-0">
                                                <div class="media-body"><h6 class="text-start"><span
                                                            class="font-primary">{{$total['fieldName']}}</span></h6>
                                                </div>
                                                <p class="text-end">{{$total['count']}}</p>
                                            </div>
                                            <div class="progress" style="height: 10px">
                                                <div class="progress-bar-animated mysmall_text bg-{{$colors[$s]}}"
                                                     role="progressbar"
                                                     style="width: 100%"
                                                     aria-valuenow="{{$total['count']}}" aria-valuemin="0"
                                                     aria-valuemax="100">100%
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 col-sm-12">
                <div class="card total-users">
                    <div class="card-header card-no-border">
                        <h5>Public</h5>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="fa fa-spin fa-cog"></i></li>
                                <li><i class="icofont icofont-maximize full-card"></i></li>
                                <li><i class="icofont icofont-minus minimize-card"></i></li>
                                <li onclick="public_chart()"><i class="icofont icofont-refresh reload-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="apex-chart-container goal-status text-center row">
                            <div class="rate-card col-xl-12">
                                <div class="goal-chart">
                                    <div id="public_cluster"></div>
                                </div>
                                <div class="goal-end-point">
                                    <ul>
                                        <li class="mt-0 pt-0">
                                            <h6 class="font-primary">Public</h6>
                                            <h6 class="f-w-400">
                                                @if (isset($data['totalcluster_Public']) && $data['totalcluster_Public'] != '')
                                                    @php
                                                        $perc_cal = ($data['totalcluster_Public'] / $data['totalcluster']) * 100;
                                                        $perc=(isset($perc_cal) && $perc_cal!=''?$perc_cal:'0');
                                                    @endphp
                                                    {{$data['totalcluster_Public']}}
                                                @endif
                                                <input type="hidden" id="public_hidden"
                                                       value="{{number_format($perc,1)}}">
                                            </h6>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @php
                                $s=0;
                            @endphp
                            @if (isset($data['total']) && $data['total'] != '')
                                @foreach ($data['total'] as $keys=>$public)
                                    @php  $s++;
                                        $t=(isset($public['count']) && $public['count']!=''?$public['count']:'0');
                                        $d=(isset($public['public']) && $public['public']!=''?$public['public']:'0');
                                        $perc_cal = ($d / $t) * 100;
                                        $perc=(isset($perc_cal) && $perc_cal!='' && $perc_cal!='0'?number_format($perc_cal,1):'0');
                                    @endphp
                                    <div class="project-status mt-4">
                                        <a href="javascript:void(0)">
                                            <div class="media mb-0">
                                                <div class="media-body"><h6 class=" text-start"><span
                                                            class="font-primary">{{$public['fieldName']}}</span></h6>
                                                </div>
                                                <p class="text-end">{{$public['public']}}</p>
                                            </div>
                                            <div class="progress" style="height: 10px">
                                                <div class="progress-bar-animated mysmall_text bg-{{$colors[$s]}}"
                                                     role="progressbar"
                                                     style="width: {{number_format($perc,0)}}%"
                                                     aria-valuenow="{{$public['count']}}" aria-valuemin="0"
                                                     aria-valuemax="100">{{$perc}}%
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif


                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 col-sm-12">
                <div class="card total-users">
                    <div class="card-header card-no-border">
                        <h5>Private</h5>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="fa fa-spin fa-cog"></i></li>
                                <li><i class="icofont icofont-maximize full-card"></i></li>
                                <li><i class="icofont icofont-minus minimize-card"></i></li>
                                <li onclick="ip_chart()"><i class="icofont icofont-refresh reload-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="apex-chart-container goal-status text-center row">
                            <div class="rate-card col-xl-12">
                                <div class="goal-chart">
                                    <div id="ip_cluster"></div>
                                </div>
                                <div class="goal-end-point">
                                    <ul>
                                        <li class="mt-0 pt-0">
                                            <h6 class="font-primary">Private</h6>
                                            <h6 class="f-w-400">
                                                @if (isset($data['totalcluster_private']) && $data['totalcluster_private'] != '')
                                                    @php
                                                        $perc_cal = ($data['totalcluster_private'] / $data['totalcluster']) * 100;
                                                        $perc=(isset($perc_cal) && $perc_cal!=''?$perc_cal:'0');
                                                    @endphp
                                                    {{$data['totalcluster_private']}}
                                                @endif
                                                <input type="hidden" id="ip_hidden"
                                                       value="{{number_format($perc,1)}}">
                                            </h6>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @php
                                $s=0;
                            @endphp
                            @if (isset($data['total']) && $data['total'] != '')
                                @foreach ($data['total'] as $keys=>$ip)
                                    @php
                                        $s++;
                                            $t=(isset($ip['count']) && $ip['count']!=''?$ip['count']:'0');
                                            $d=(isset($ip['private']) && $ip['private']!=''?$ip['private']:'0');
                                            $perc_cal = ($d / $t) * 100;
                                            $perc=(isset($perc_cal) && $perc_cal!='' && $perc_cal!='0'?number_format($perc_cal,1):'0');
                                    @endphp
                                    <div class="project-status mt-4">
                                        <a href="javascript:void(0)">
                                            <div class="media mb-0">
                                                <div class="media-body"><h6 class=" text-start"><span
                                                            class="font-primary">{{$ip['fieldName']}}</span></h6>
                                                </div>
                                                <p class="text-end">{{$ip['private']}}</p>
                                            </div>
                                            <div class="progress" style="height: 10px">
                                                <div class="progress-bar-animated mysmall_text bg-{{$colors[$s]}}"
                                                     role="progressbar"
                                                     style="width: {{number_format($perc,0)}}%"
                                                     aria-valuenow="{{$ip['private']}}" aria-valuemin="0"
                                                     aria-valuemax="100">{{$perc}}%
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 col-sm-12">
                <div class="card total-users">
                    <div class="card-header card-no-border">
                        <h5>Data Collected</h5>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="fa fa-spin fa-cog"></i></li>
                                <li><i class="icofont icofont-maximize full-card"></i></li>
                                <li><i class="icofont icofont-minus minimize-card"></i></li>
                                <li onclick="r_chart()"><i class="icofont icofont-refresh reload-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="apex-chart-container goal-status text-center row">
                            <div class="rate-card col-xl-12">
                                <div class="goal-chart">
                                    <div id="r_cluster"></div>
                                </div>
                                <div class="goal-end-point">
                                    <ul>
                                        <li class="mt-0 pt-0">
                                            <h6 class="font-primary">Data Collected</h6>
                                            <h6 class="f-w-400">
                                                @if (isset($data['totalcluster_dc']) && $data['totalcluster_dc'] != '')
                                                    @php
                                                        $perc_cal = ($data['totalcluster_dc'] / $data['totalcluster']) * 100;
                                                        $perc=(isset($perc_cal) && $perc_cal!=''?$perc_cal:'0');
                                                    @endphp
                                                    {{$data['totalcluster_dc']}}
                                                @endif
                                                <input type="hidden" id="r_hidden"
                                                       value="{{number_format($perc,1)}}">
                                            </h6>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @php
                                $s=0;
                            @endphp
                            @if (isset($data['total']) && $data['total'] != '')
                                @foreach ($data['total'] as $keys=>$r)
                                    @php
                                        $s++;
                                         $t=(isset($r['count']) && $r['count']!=''?$r['count']:'0');
                                        $d=(isset($r['dc']) && $r['dc']!=''?$r['dc']:'0');
                                        $perc_cal = ($d / $t) * 100;
                                        $perc=(isset($perc_cal) && $perc_cal!='' && $perc_cal!='0'?number_format($perc_cal,1):'0');
                                    @endphp
                                    <div class="project-status mt-4">
                                        <a href="javascript:void(0)">
                                            <div class="media mb-0">
                                                <div class="media-body"><h6 class=" text-start"><span
                                                            class="font-primary">{{$r['fieldName']}}</span></h6>
                                                </div>
                                                <p class="text-end">{{$r['dc']}}</p>
                                            </div>
                                            <div class="progress" style="height: 10px">
                                                <div class="progress-bar-animated mysmall_text bg-{{$colors[$s]}}"
                                                     role="progressbar"
                                                     style="width: {{number_format($perc,0)}}%"
                                                     aria-valuenow="{{$r['dc']}}" aria-valuemin="0"
                                                     aria-valuemax="100">{{$perc}}%
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{asset(config('global.asset_path').'/js/chart/apex-chart/apex-chart.js')}}"></script>
    <script src="{{asset(config('global.asset_path').'/js/custom-card/custom-card.js')}}"></script>
    <script src="{{asset(config('global.asset_path').'/js/clipboard/clipboard.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            total_chart();
            public_chart();
            ip_chart();
            r_chart();
        });

        function total_chart() {
            var options4 = {
                series: [100],
                chart: {
                    height: 350,
                    type: 'radialBar',
                    offsetY: -10,
                },

                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        inverseOrder: true,
                        hollow: {
                            margin: 5,
                            size: '60%',
                            image: '{{asset(config('global.asset_path').'/images/dashboard-2/radial-image.png')}}',
                            imageWidth: 140,
                            imageHeight: 140,
                            imageClipped: false,
                        },
                        track: {
                            opacity: 0.4,
                            colors: CubaAdminConfig.primary
                        },
                        dataLabels: {
                            enabled: false,
                            enabledOnSeries: undefined,
                            formatter: function (val, opts) {
                                return val + "%"
                            },
                            textAnchor: 'middle',
                            distributed: false,
                            offsetX: 0,
                            offsetY: 0,

                            style: {
                                fontSize: '14px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fill: ['#2b2b2b'],

                            },
                        },
                    }
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100],
                        gradientToColors: ['#a927f9'],
                        type: 'horizontal'
                    },
                },
                stroke: {
                    dashArray: 15,
                    strokecolor: ['#ffffff']
                },

                labels: ['Total'],
                colors: [CubaAdminConfig.primary],
            };
            var chart4 = new ApexCharts(document.querySelector("#total_cluster"),
                options4
            );
            chart4.render();
        }

        function public_chart() {
            var public_hidden = $('#public_hidden').val();
            if (public_hidden == '' || public_hidden == undefined) {
                public_hidden = 0;
            }
            var options = {
                series: [public_hidden],
                chart: {
                    height: 350,
                    type: 'radialBar',
                    offsetY: -10,
                },

                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        inverseOrder: true,
                        hollow: {
                            margin: 5,
                            size: '60%',
                            image: '{{asset(config('global.asset_path').'/images/dashboard-2/radial-image.png')}}',
                            imageWidth: 140,
                            imageHeight: 140,
                            imageClipped: false,
                        },
                        track: {
                            opacity: 0.4,
                            colors: CubaAdminConfig.secondary
                        },
                        dataLabels: {
                            enabled: false,
                            enabledOnSeries: undefined,
                            formatter: function (val, opts) {
                                return val + "%"
                            },
                            textAnchor: 'middle',
                            distributed: false,
                            offsetX: 0,
                            offsetY: 0,

                            style: {
                                fontSize: '14px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fill: ['#7dafb7'],

                            },
                        },
                    }
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100],
                        gradientToColors: ['#7dafb7'],
                        type: 'horizontal'
                    },
                },
                stroke: {
                    dashArray: 15,
                    strokecolor: ['#ffffff']
                },

                labels: ['Public'],
                colors: [CubaAdminConfig.mycolor2],
            };
            var chart = new ApexCharts(document.querySelector("#public_cluster"),
                options
            );
            chart.render();
        }

        function ip_chart() {
            var ip_hidden = $('#ip_hidden').val();
            if (ip_hidden == '' || ip_hidden == undefined) {
                ip_hidden = 0;
            }
            var options = {
                series: [ip_hidden],
                chart: {
                    height: 350,
                    type: 'radialBar',
                    offsetY: -10,
                },

                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        inverseOrder: true,
                        hollow: {
                            margin: 5,
                            size: '60%',
                            image: '{{asset(config('global.asset_path').'/images/dashboard-2/radial-image.png')}}',
                            imageWidth: 140,
                            imageHeight: 140,
                            imageClipped: false,
                        },
                        track: {
                            opacity: 0.4,
                            colors: CubaAdminConfig.secondary
                        },
                        dataLabels: {
                            enabled: false,
                            enabledOnSeries: undefined,
                            formatter: function (val, opts) {
                                return val + "%"
                            },
                            textAnchor: 'middle',
                            distributed: false,
                            offsetX: 0,
                            offsetY: 0,

                            style: {
                                fontSize: '14px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fill: ['#efe3a5'],
                            },
                        },
                    }
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100],
                        gradientToColors: ['#efe3a5'],
                        type: 'horizontal'
                    },
                },
                stroke: {
                    dashArray: 15,
                    strokecolor: ['#ffffff']
                },

                labels: ['Private'],
                colors: [CubaAdminConfig.warning],
            };
            var chart = new ApexCharts(document.querySelector("#ip_cluster"),
                options
            );
            chart.render();
        }

        function r_chart() {
            var r_hidden = $('#r_hidden').val();
            if (r_hidden == '' || r_hidden == undefined) {
                r_hidden = 0;
            }
            var options = {
                series: [r_hidden],
                chart: {
                    height: 350,
                    type: 'radialBar',
                    offsetY: -10,
                },

                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        inverseOrder: true,
                        hollow: {
                            margin: 5,
                            size: '60%',
                            image: '{{asset(config('global.asset_path').'/images/dashboard-2/radial-image.png')}}',
                            imageWidth: 140,
                            imageHeight: 140,
                            imageClipped: false,
                        },
                        track: {
                            opacity: 0.4,
                            colors: CubaAdminConfig.secondary
                        },
                        dataLabels: {
                            enabled: false,
                            enabledOnSeries: undefined,
                            formatter: function (val, opts) {
                                return val + "%"
                            },
                            textAnchor: 'middle',
                            distributed: false,
                            offsetX: 0,
                            offsetY: 0,

                            style: {
                                fontSize: '14px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fill: ['#7367F0'],

                            },
                        },
                    }
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100],
                        gradientToColors: ['#f3959e'],
                        type: 'horizontal'
                    },
                },
                stroke: {
                    dashArray: 15,
                    strokecolor: ['#ffffff']
                },

                labels: ['Data Collected'],
                colors: [CubaAdminConfig.danger],
            };
            var chart = new ApexCharts(document.querySelector("#r_cluster"),
                options
            );
            chart.render();
        }
    </script>
@endsection
