@extends('layouts.simple.master')
@section('title',  trans('lang.app_users_main_heading')  )

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>{{ trans('lang.app_users_main_heading') }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ trans('lang.app_users_main_heading') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax data source array start-->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header project-list">
                        <h5>{{ trans('lang.app_users_dt_heading') }}</h5>
                        <span>{{ trans('lang.app_users_dt_paragraph') }}</span>
                        <span><a class="btn btn-primary addbtn" href="javascript:void(0)"
                                 data-uk-modal="{target:'#addModal'}" id="add"> <i data-feather="plus-square"> </i>Create New User</a></span>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive2">
                            <table class="display datatables" id="datatable_custom">

                                <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>District</th>
                                    <th>Designation</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $sno = 1
                                @endphp
                                @foreach ($data['data'] as $keys=>$value)
                                    <tr data-id="{{ $value->id }}">
                                        <td>{{$sno++}}</td>
                                        <td>{{(isset($value->full_name) && $value->full_name!=''?$value->full_name:'')}}</td>
                                        <td>{{(isset($value->username) && $value->username!=''?$value->username:'')}}</td>
                                        <td>{{(isset($value->dist_id) && $value->dist_id!=''?$value->dist_id:'')}}</td>
                                        <td>{{(isset($value->designation) && $value->designation!=''?$value->designation:'')}}</td>
                                        <td>
                                            <a href="javascript:void(0)" data-original-title="Edit"
                                               title="Edit"
                                               onclick="getEdit(this)">
                                                <i data-feather="edit" class="txt-secondary"></i>
                                            </a>
                                            <a href="javascript:void(0)" data-original-title="Delete"
                                               title="Delete"
                                               onclick="getDelete(this)">
                                                <i data-feather="trash-2" class="txt-danger"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>SNo</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>District</th>
                                    <th>Designation</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Add Modal-->
                        <div class="modal fade text-left" id="addModal" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel_add"
                             aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content ">
                                    <div class="loader-box hide loader">
                                        <div class="loader-1 myloader"></div>
                                        <div class="myloader"> Loading..</div>
                                    </div>
                                    <div class="modal-header bg-primary white">
                                        <h4 class="modal-title white" id="myModalLabel_add">Add User</h4>
                                        <button class="btn-close white" type="button" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body  ">
                                        <div class="mb-1 form-group">
                                            <label class="col-form-label" for="fullName">Full Name: </label>
                                            <input type="text" class="form-control fullName"
                                                   id="fullName" required>
                                        </div>
                                        <div class="mb-1 form-group">
                                            <label class="col-form-label" for="userName">User Name: </label>
                                            <input type="email" class="form-control userName" minlength="8"
                                                   id="userName" required>
                                        </div>
                                        <div class="mb-1 form-group">
                                            <label class="col-form-label" for="userPassword">Password</label>
                                            <input class="form-control" id="userPassword" type="text"
                                                   name="userPassword"
                                                   required="" minlength="8"
                                                   placeholder="*********" autocomplete="current-userPassword">
                                        </div>
                                        <div class="mb-1 form-group">
                                            <label class="col-form-label" for="district">District: </label>
                                            <select class="form-control col-sm-12" id="district" required>
                                                <option value="0">Select District</option>
                                                @if (isset($data['districts']) && $data['districts'] != '')
                                                    @foreach ($data['districts'] as $keys=>$d)
                                                        <option
                                                            value="{{(isset($d->dist_id) && $d->dist_id!=''?$d->dist_id:$d->district)}}">
                                                            {{(isset($d->district) && $d->district!=''?$d->district:$d->dist_id)}}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="mb-1 form-group">
                                            <label class="col-form-label" for="designation">Designation: </label>
                                            <input type="text" class="form-control" id="designation" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-secondary" data-bs-dismiss="modal"
                                                aria-label="Close" data-dismiss="modal">Close
                                        </button>
                                        <button type="button" class="btn btn-primary mybtn" onclick="addData()">Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal-->
                        <div class="modal fade text-left" id="editModal" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel_edit"
                             aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content ">
                                    <div class="loader-box hide loader">
                                        <div class="loader-1 myloader"></div>
                                        <div class="myloader"> Loading..</div>
                                    </div>
                                    <div class="modal-header bg-primary white">
                                        <h4 class="modal-title white" id="myModalLabel_edit">Edit User</h4>
                                        <input type="hidden" id="hiddenEditId" name="hiddenEditId" value="">
                                        <button class="btn-close white" type="button" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body  ">
                                        <div class="mb-1 form-group">
                                            <label class="col-form-label" for="fullName_edit">Full Name: </label>
                                            <input type="text" class="form-control fullName_edit"
                                                   id="fullName_edit" required>
                                        </div>
                                        <div class="mb-1 form-group">
                                            <label class="col-form-label" for="userName_edit">User Name: </label>
                                            <input type="email" class="form-control userName_edit" minlength="8"
                                                   id="userName_edit" readonly disabled required>
                                        </div>
                                        <div class="mb-1 form-group">
                                            <label class="col-form-label" for="userPassword_edit">Password</label>
                                            <input class="form-control" id="userPassword_edit" type="text"
                                                   name="userPassword_edit"
                                                   required="" minlength="8"
                                                   placeholder="*********" autocomplete="current-userPassword_edit">
                                        </div>
                                        <div class="mb-1 form-group">
                                            <label class="col-form-label" for="district_edit">District: </label>
                                            <select class="form-control col-sm-12" id="district_edit" required>
                                                <option value="0">Select District</option>
                                                @if (isset($data['districts']) && $data['districts'] != '')
                                                    @foreach ($data['districts'] as $keys=>$d)
                                                        <option
                                                            value="{{(isset($d->dist_id) && $d->dist_id!=''?$d->dist_id:$d->district)}}">
                                                            {{(isset($d->district) && $d->district!=''?$d->district:$d->dist_id)}}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="mb-1 form-group">
                                            <label class="col-form-label" for="designation_edit">Designation: </label>
                                            <input type="text" class="form-control" id="designation_edit" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-secondary" data-bs-dismiss="modal"
                                                aria-label="Close" data-dismiss="modal">Close
                                        </button>
                                        <button type="button" class="btn btn-primary mybtn" onclick="editData()">Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal-->
                        <div class="modal fade text-left" id="deleteModal" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel_delete"
                             aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content ">
                                    <div class="loader-box hide loader">
                                        <div class="loader-1 myloader"></div>
                                        <div class="myloader"> Loading..</div>
                                    </div>
                                    <div class="modal-header bg-primary white">
                                        <h4 class="modal-title white" id="myModalLabel_delete">Delete User</h4>
                                        <input type="hidden" id="hiddenDeleteId" name="hiddenDeleteId" value="">
                                        <button class="btn-close white" type="button" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body  ">
                                        <div class="px-3">
                                            <p>Are you sure, you want to delete it?</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn grey btn-secondary" data-bs-dismiss="modal"
                                                aria-label="Close" data-dismiss="modal">Close
                                        </button>
                                        <button type="button" class="btn btn-primary mybtn" onclick="deleteData()">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
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
            $('.myselect2').select2({
                dropdownParent: $('#addModal')
            });
            $('.addbtn').click(function () {
                $('#addModal').modal('show');
            });
            $('#datatable_custom').DataTable({
                "displayLength": 50,
                "oSearch": {"sSearch": " "},
                autoFill: false,
                attr: {
                    autocomplete: 'off'
                },
                initComplete: function () {
                    $(this.api().table().container()).find('input[type="search"]').parent().wrap('<form>').parent().attr('autocomplete', 'off').css('overflow', 'hidden').css('margin', 'auto');
                },
            });
        });

        function addData() {
            $('#fullName').css('border', '1px solid #babfc7');
            $('#userName').css('border', '1px solid #babfc7');
            $('#userPassword').css('border', '1px solid #babfc7');
            var flag = 0;
            var data = {};
            data['fullName'] = $('#fullName').val();
            data['userName'] = $('#userName').val();
            data['userPassword'] = $('#userPassword').val();
            data['district'] = $('#district').val();
            data['designation'] = $('#designation').val();
            if (data['fullName'] == '' || data['fullName'] == undefined) {
                $('#fullName').css('border', '1px solid red');
                flag = 1;
                toastMsg('Full Name', 'Invalid Full Name', 'danger');
                return false;
            }
            if (data['userName'].length < 8) {
                $('#userName').css('border', '1px solid red');
                flag = 1;
                toastMsg('User Name', 'Username must be 8 characters long', 'danger');
                return false;
            }
            if (data['userPassword'].length < 8) {
                $('#userPassword').css('border', '1px solid red');
                flag = 1;
                toastMsg('Password', 'Password must be 8 characters long', 'danger');
                return false;
            }

            if (data['userName'] == '' || data['userName'] == undefined || data['userName'].length < 8) {
                $('#userName').css('border', '1px solid red');
                flag = 1;
                toastMsg('User Name', 'Invalid User Name', 'danger');
                return false;
            }

            if (data['userPassword'] == '' || data['userPassword'] == undefined || data['userPassword'].length < 8) {
                $('#userPassword').css('border', '1px solid red');
                flag = 1;
                toastMsg('Password', 'Invalid Password', 'danger');
                return false;
            }
            if (data['district'] == '' || data['district'] == undefined || data['district'] == 0 || data['district'] == '0') {
                $('#district').css('border', '1px solid red');
                flag = 1;
                toastMsg('District', 'Invalid District', 'danger');
                return false;
            }
            if (flag == 0) {
                showloader();
                CallAjax('{{ url('/App_Users/addAppUsers') }}', data, 'POST', function (result) {
                    hideloader();
                    if (result !== '' && JSON.parse(result).length > 0) {
                        var response = JSON.parse(result);
                        try {
                            toastMsg(response[0], response[1], response[2]);
                            if (response[0] === 'Success') {
                                hideModal('addModal');
                                setTimeout(function () {
                                    window.location.reload();
                                }, 700);
                            }
                        } catch (e) {
                        }
                    } else {
                        toastMsg('Error', 'Something went wrong while uploading data', 'danger');
                    }
                });
            } else {
                toastMsg('User', 'Something went wrong', 'danger');
            }
        }

        function getEdit(obj) {
            var data = {};
            data['id'] = $(obj).parents('tr').attr('data-id');
            if (data['id'] != '' && data['id'] != undefined) {
                CallAjax('{{ url('/App_Users/detail/') }}', data, 'GET', function (result) {
                    if (result != '' && JSON.parse(result).length > 0) {
                        var a = JSON.parse(result);
                        try {
                            $('#hiddenEditId').val(data['id']);
                            $('#fullName_edit').val(a[0]['full_name']);
                            $('#userName_edit').val(a[0]['username']);
                            $('#userPassword_edit').val(a[0]['password']);
                            $('#district_edit').val(a[0]['dist_id']);
                            $('#designation_edit').val(a[0]['designation']);
                        } catch (e) {
                        }
                        showModal('editModal');
                    }
                });
            }
        }

        function editData() {
            $('#fullName_edit').css('border', '1px solid #babfc7');
            $('#userPassword_edit').css('border', '1px solid #babfc7');
            var flag = 0;
            var data = {};
            data['id'] = $('#hiddenEditId').val();
            data['fullName'] = $('#fullName_edit').val();
            data['userPassword'] = $('#userPassword_edit').val();
            data['district'] = $('#district_edit').val();
            data['designation'] = $('#designation_edit').val();
            if (data['id'] == '' || data['id'] == undefined) {
                $('#hiddenEditId').css('border', '1px solid red');
                flag = 1;
                toastMsg('Error', 'Invalid User Id', 'danger');
                return false;
            }
            if (data['fullName'] == '' || data['fullName'] == undefined) {
                $('#fullName_edit').css('border', '1px solid red');
                flag = 1;
                toastMsg('Full Name', 'Invalid Full Name', 'danger');
                return false;
            }
            if (data['userPassword'].length < 8) {
                $('#userPassword_edit').css('border', '1px solid red');
                flag = 1;
                toastMsg('Password', 'Password must be 8 characters long', 'danger');
                return false;
            }
            if (data['userPassword'] == '' || data['userPassword'] == undefined || data['userPassword'].length < 8) {
                $('#userPassword_edit').css('border', '1px solid red');
                flag = 1;
                toastMsg('Password', 'Invalid Password', 'danger');
                return false;
            }
            if (data['district'] == '' || data['district'] == undefined || data['district'] == 0 || data['district'] == '0') {
                $('#district_edit').css('border', '1px solid red');
                flag = 1;
                toastMsg('District', 'Invalid District', 'danger');
                return false;
            }
            if (flag === 0) {
                showloader();
                CallAjax('{{ url('/App_Users/editAppUsers') }}', data, 'POST', function (result) {
                    hideloader();
                    if (result !== '' && JSON.parse(result).length > 0) {
                        var response = JSON.parse(result);
                        try {
                            toastMsg(response[0], response[1], response[2]);
                            if (response[0] === 'Success') {
                                hideModal('editModal');
                                setTimeout(function () {
                                    window.location.reload();
                                }, 700);
                            }
                        } catch (e) {
                        }
                    } else {
                        toastMsg('Error', 'Something went wrong while uploading data', 'danger');
                    }
                });
            } else {
                toastMsg('User', 'Something went wrong', 'danger');
            }
        }

        function getDelete(obj) {
            $('#hiddenDeleteId').val($(obj).parents('tr').attr('data-id'));
            showModal('deleteModal');
        }

        function deleteData() {
            var data = {};
            data['id'] = $('#hiddenDeleteId').val();
            if (data['id'] == '' || data['id'] == undefined) {
                $('#hiddenEditId').css('border', '1px solid red');
                toastMsg('Error', 'Invalid User Id', 'danger');
                return false;
            } else {
                showloader();
                CallAjax('{{ url('/App_Users/deleteAppUsers') }}', data, 'POST', function (result) {
                    hideloader();
                    if (result !== '' && JSON.parse(result).length > 0) {
                        var response = JSON.parse(result);
                        try {
                            toastMsg(response[0], response[1], response[2]);
                            if (response[0] === 'Success') {
                                hideModal('deleteModal');
                                setTimeout(function () {
                                    window.location.reload();
                                }, 700);
                            }
                        } catch (e) {
                        }
                    } else {
                        toastMsg('Error', 'Something went wrong while uploading data', 'danger');
                    }
                });
            }
        }
    </script>
@endsection
