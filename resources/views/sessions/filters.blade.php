<section class="basic-select2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header ">
                    <h5>Filters</h5>
                </div>
                <div class="card-body">
                    <div class="row  mb-2">
                        <div class="col-sm-6 col-12">
                            <div class="form-group">
                                <label for="district_filter" class="">District</label>
                                <select class="select2 form-control district_filter" id="district_filter"
                                        name="district_filter" onchange="changeDistrict()">
                                    <option value="0" readonly disabled selected>District</option>
                                    @if(isset($data['districts']) && $data['districts']!='')
                                        @foreach($data['districts'] as $k=>$d)
                                            <option
                                                value="{{$d->dist_id}}" {{  $data['district_slug'] === $d->dist_id ? 'selected' :''}}>
                                                {{$d->district}} ({{$d->dist_id}})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="form-group">
                                <label for="tehsil_filter" class="">Tehsil</label>
                                <select class="select2 form-control tehsil_filter" id="tehsil_filter"
                                        name="tehsil_filter" onchange="changeTehsil()">
                                    <option value="0" readonly disabled selected>Tehsil</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-sm-6 col-12">
                            <div class="form-group">
                                <label for="hfc_filter" class="">Health Facility</label>
                                <select class="select2 form-control hfc_filter" id="hfc_filter"
                                        name="hfc_filter">
                                    <option value="0" readonly disabled selected>Health Facility</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="form-group">
                                <label for="lhw_filter" class="">LHW Code</label>
                                <input type="text" class="form-control lhw_filter" id="lhw_filter"
                                       value="{{ isset($data['lhw_slug']) && $data['lhw_slug'] !='' && $data['lhw_slug'] !='0' ? $data['lhw_slug'] :''}}"
                                       name="lhw_filter">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-12 mt-2">
                            <button type="button" class="btn btn-primary" onclick="searchData()">Get
                                Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<input type="hidden"
       value="{{ isset($data['district_slug']) && $data['district_slug'] !='' ? $data['district_slug'] :'0'}}"
       id="hidden_slug_district" name="hidden_slug_district">
<input type="hidden" value="{{ isset($data['tehsil_slug']) && $data['tehsil_slug'] !='' ? $data['tehsil_slug'] :'0'}}"
       id="hidden_slug_teshil" name="hidden_slug_teshil">
<input type="hidden" value="{{ isset($data['hfc_slug']) && $data['hfc_slug'] !='' ? $data['hfc_slug'] :'0'}}"
       id="hidden_slug_hf" name="hidden_slug_hf">
<input type="hidden"
       value="{{ isset($data['lhw_slug']) && $data['lhw_slug'] !='' && $data['lhw_slug'] !='0' ? $data['lhw_slug'] :''}}"
       id="hidden_slug_lhw" name="hidden_slug_lhw">


@section('script')
    <script>
        $(document).ready(function () {
            $('#datatable_custom').DataTable({
                "displayLength": 25,
                "oSearch": {"sSearch": " "},
                autoFill: false,
                attr: {
                    autocomplete: 'off'
                },
                initComplete: function () {
                    $(this.api().table().container()).find('input[type="search"]').parent().wrap('<form>').parent().attr('autocomplete', 'off').css('overflow', 'hidden').css('margin', 'auto');
                },
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
            changeDistrict();
        });

        function changeDistrict() {
            var data = {};
            data['district'] = $('#district_filter').val();
            if (data['district'] != '' && data['district'] != undefined && data['district'] != '0' && data['district'] != '$1') {
                CallAjax('{{ url('/Summary/changeDistrict/') }}', data, 'POST', function (res) {
                    var teshil = $('#hidden_slug_teshil').val();
                    var items = '<option value="0">Select All</option>';
                    if (res != '' && JSON.parse(res).length > 0) {
                        var response = JSON.parse(res);
                        try {
                            $.each(response, function (i, v) {
                                items += '<option value="' + v.tehsil_id + '"  ' + (teshil == v.tehsil_id ? 'selected' : '') + '>' + v.tehsil + ' (' + v.tehsil_id + ')</option>';
                            })
                        } catch (e) {
                        }
                    }
                    $('#tehsil_filter').html('').html(items);
                    setTimeout(function () {
                        changeTehsil();
                    }, 200);
                });
            } else {
                $('#tehsil_filter').html('');
            }
        }

        function changeTehsil() {
            var data = {};
            data['tehsil'] = $('#tehsil_filter').val();
            if (data['tehsil'] != '' && data['tehsil'] != undefined && data['tehsil'] != '0' && data['tehsil'] != '$1') {
                CallAjax('{{ url('/Summary/changeTehsil/') }}', data, 'POST', function (res) {
                    var hfc = $('#hidden_slug_hf').val();
                    var items = '<option value="0">Select All</option>';
                    if (res != '' && JSON.parse(res).length > 0) {
                        var response = JSON.parse(res);
                        try {
                            $.each(response, function (i, v) {
                                items += '<option value="' + v.hfcode + '"  ' + (hfc == v.hfcode ? 'selected' : '') + '>' + v.hf_name + ' (' + v.hfcode + ')</option>';
                            })
                        } catch (e) {
                        }
                    }
                    $('#hfc_filter').html('').html(items);
                });
            } else {
                $('#hfc_filter').html('');
            }
        }

        function searchData() {
            var data = {};
            data['district'] = $('#district_filter').val();
            data['tehsil'] = $('#tehsil_filter').val();
            data['hfc'] = $('#hfc_filter').val();
            data['lhw'] = $('#lhw_filter').val();
            var pathname = window.location.pathname;
            pathname += '?f=1';
            if (data['district'] != '' && data['district'] != undefined && data['district'] != '0' && data['district'] != '$1') {
                pathname += '&d=' + data['district'];
            }
            if (data['tehsil'] != '' && data['tehsil'] != undefined && data['tehsil'] != '0' && data['tehsil'] != '$1') {
                pathname += '&t=' + data['tehsil'];
            }
            if (data['hfc'] != '' && data['hfc'] != undefined && data['hfc'] != '0' && data['hfc'] != '$1') {
                pathname += '&h=' + data['hfc'];
            }
            if (data['lhw'] != '' && data['lhw'] != undefined && data['lhw'] != '0' && data['lhw'] != '$1') {
                pathname += '&l=' + data['lhw'];
            }
            window.location.href = pathname;
        }
    </script>
@endsection

