@extends('dashboard.layout.main')

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('dashboard/additionalplugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/additionalplugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('dashboard/additionalplugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Multi Item Selection examples -->
    <link href="{{ asset('dashboard/additionalplugins/datatables/select.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Select2 -->
    <link href="{{ asset('dashboard/additionalplugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="{{ asset('dashboard/additionalplugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- Sweet Alert css -->
    <link href="{{ asset('dashboard/additionalplugins/sweet-alert/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Time Picker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/lib/bootstrap-timepicker/compiled/timepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/lib/bootstrap-datetimepicker/css/datetimepicker.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        #loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            /* border-top: 16px solid #3498db; */
            border-top: 16px solid #f85a40;
            width: 50px;
            height: 50px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            margin-left:10px;
            margin-right:200px;
            margin-top:10px;
            margin-bottom: 10px;
        }
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .dataTables_wrapper .dataTables_processing {
            position: absolute;
            top: 40% !important;
            /* background: #FFFFCC; */
            background: transparent;
            /* border: 1px solid black; */
            border-radius: 3px;
            font-weight: bold;
        }
    </style>
@endsection

@section('title')
    Order List
@endsection

@section('content')
    <!-- page start-->
    <div class="content-panel">
        @if(array_search("ORORC", $submoduls))
            <button class="btn btn-theme btn-round m-20" data-toggle="modal" data-target="#myModal" onclick="create_data()"><i class="glyphicon glyphicon-plus"></i> Add</button>
        @endif
        <!-- Modal -->
        <div class="modal fade bs-example-modal-lg" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Flash Academia</h4>
                    </div>
                    <div class="modal-body" id="view-form">
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-heading">
            <ul class="nav nav-tabs nav-justified">
                <li class="active">
                    <a data-toggle="tab" href="#notconfirm"><span style="background: #f0ad4e; border-radius: 3px; margin-left: 20px;padding: 0 10px 0 10px; color:white"">Not Yet Confirmed</span></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#confirm"><span style="background: #5cb85c; border-radius: 3px; margin-left: 20px;padding: 0 10px 0 10px; color:white"">Ongoing</span></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#finish"><span style="background: #5bc0de; border-radius: 3px; margin-left: 20px;padding: 0 10px 0 10px; color:white"">Finished</span></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#decline"><span style="background: #d9534f; border-radius: 3px; margin-left: 20px;padding: 0 10px 0 10px; color:white"">Declined</span></a>
                </li>
            </ul>
        </div>

        <!-- /panel-heading -->
        <div class="panel-body">
            <div class="tab-content">
                <div id="notconfirm" class="tab-pane active">
                    <div class="row">
                        <div class="adv-table">
                            <form class="form-horizontal" role="form" action="{{ route('exportOrder') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="0">
                                <div class="col-sm-12" style="margin-bottom: 10px;">
                                    <div class="col-sm-2">
                                        <input type="text" data-date-format='yyyy-mm-dd' class="form-control datepicker" name="notconfirm_start_date" id="notconfirm_start_date" value="" placeholder="Start Date">
                                        <span id="span_date" class="input-group-btn add-on" onclick="moveToBox('notconfirm_start_date')" style="padding-right: 39px; padding-top: 3px;">
                                            <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" data-date-format='yyyy-mm-dd' class="form-control datepicker" name="notconfirm_end_date" id="notconfirm_end_date" value="" placeholder="End Date">
                                        <span id="span_date" class="input-group-btn add-on" onclick="moveToBox('notconfirm_end_date')" style="padding-right: 39px; padding-top: 3px;">
                                            <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                    <div class="col-sm-2">
                                        <a class="btn btn-theme btn-round" onclick="getDatas('not-confirm')"><i class="fa fa-filter"></i> Filter</a>
                                        <a class="btn" onclick="resetParamProcess('not-confirm')"><i class="fa fa-rotate-left"></i> Clear</a>
                                    </div>
                                    @if(array_search("ORORX", $submoduls))
                                    <div class="col-6 text-right">
                                        <button class="btn btn-success btn-round m-b-5 btn-sm">
                                            <i class="fa fa-file-text-o"></i> Export to Excel
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </form>            
                            <table cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-order-notconfirm" width="100%">
                                <thead>
                                    <th>No</th>
                                    <th>Order ID</th>
                                    <th>Student</th>
                                    <th>Grade</th>
                                    <th>Course</th>
                                    <th>Teacher</th>
                                    <th>Package</th>
                                    <th>Type</th>
                                    <th>Schedules</th>
                                    <th>Total Bill</th>
                                    <th>Bill Paid</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </thead>
                                <tbody id="table-body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="confirm" class="tab-pane">
                    <div class="row">
                        <div class="adv-table">
                            <form class="form-horizontal" role="form" action="{{ route('exportOrder') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="1">
                                <div class="col-sm-12" style="margin-bottom: 10px;">
                                    <div class="col-sm-2">
                                        <input type="text" data-date-format='yyyy-mm-dd' class="form-control datepicker" name="confirm_start_date" id="confirm_start_date" value="" placeholder="Start Date">
                                        <span id="span_date" class="input-group-btn add-on" onclick="moveToBox('confirm_start_date')" style="padding-right: 39px; padding-top: 3px;">
                                            <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" data-date-format='yyyy-mm-dd' class="form-control datepicker" name="confirm_end_date" id="confirm_end_date" value="" placeholder="End Date">
                                        <span id="span_date" class="input-group-btn add-on" onclick="moveToBox('confirm_end_date')" style="padding-right: 39px; padding-top: 3px;">
                                            <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                    <div class="col-sm-2">
                                        <a class="btn btn-theme btn-round" onclick="getDatas('confirm')"><i class="fa fa-filter"></i> Filter</a>
                                        <a class="btn" onclick="resetParamProcess('confirm')"><i class="fa fa-rotate-left"></i> Clear</a>
                                    </div>
                                    @if(array_search("ORORX", $submoduls))
                                    <div class="col-6 text-right">
                                        <button class="btn btn-success btn-round m-b-5 btn-sm">
                                            <i class="fa fa-file-text-o"></i> Export to Excel
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </form>
                            <table cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-order-confirm" width="100%">
                                <thead>
                                    <th>No</th>
                                    <th>Order ID</th>
                                    <th>Student</th>
                                    <th>Grade</th>
                                    <th>Course</th>
                                    <th>Teacher</th>
                                    <th>Package</th>
                                    <th>Type</th>
                                    <th>Schedules</th>
                                    <th>Total Bill</th>
                                    <th>Bill Paid</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </thead>
                                <tbody id="table-body2">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="finish" class="tab-pane">
                    <div class="row">
                        <div class="adv-table">
                            <form class="form-horizontal" role="form" action="{{ route('exportOrder') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="2">
                                <div class="col-sm-12" style="margin-bottom: 10px;">
                                    <div class="col-sm-2">
                                        <input type="text" data-date-format='yyyy-mm-dd' class="form-control datepicker" name="finish_start_date" id="finish_start_date" value="" placeholder="Start Date">
                                        <span id="span_date" class="input-group-btn add-on" onclick="moveToBox('finish_start_date')" style="padding-right: 39px; padding-top: 3px;">
                                            <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" data-date-format='yyyy-mm-dd' class="form-control datepicker" name="finish_end_date" id="finish_end_date" value="" placeholder="End Date">
                                        <span id="span_date" class="input-group-btn add-on" onclick="moveToBox('finish_end_date')" style="padding-right: 39px; padding-top: 3px;">
                                            <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                    <div class="col-sm-2">
                                        <a class="btn btn-theme btn-round" onclick="getDatas('finish')"><i class="fa fa-filter"></i> Filter</a>
                                        <a class="btn" onclick="resetParamProcess('finish')"><i class="fa fa-rotate-left"></i> Clear</a>
                                    </div>
                                    @if(array_search("ORORX", $submoduls))
                                    <div class="col-6 text-right">
                                        <button class="btn btn-success btn-round m-b-5 btn-sm">
                                            <i class="fa fa-file-text-o"></i> Export to Excel
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </form>
                            <table cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-order-finish" width="100%">
                                <thead>
                                    <th>No</th>
                                    <th>Order ID</th>
                                    <th>Student</th>
                                    <th>Grade</th>
                                    <th>Course</th>
                                    <th>Teacher</th>
                                    <th>Package</th>
                                    <th>Type</th>
                                    <th>Schedules</th>
                                    <th>Total Bill</th>
                                    <th>Bill Paid</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </thead>
                                <tbody id="table-body3">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="decline" class="tab-pane">
                    <div class="row">
                        <div class="adv-table">
                            <form class="form-horizontal" role="form" action="{{ route('exportOrder') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="-1">
                                <div class="col-sm-12" style="margin-bottom: 10px;">
                                    <div class="col-sm-2">
                                        <input type="text" data-date-format='yyyy-mm-dd' class="form-control datepicker" name="decline_start_date" id="decline_start_date" value="" placeholder="Start Date">
                                        <span id="span_date" class="input-group-btn add-on" onclick="moveToBox('decline_start_date')" style="padding-right: 39px; padding-top: 3px;">
                                            <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" data-date-format='yyyy-mm-dd' class="form-control datepicker" name="decline_end_date" id="decline_end_date" value="" placeholder="End Date">
                                        <span id="span_date" class="input-group-btn add-on" onclick="moveToBox('decline_end_date')" style="padding-right: 39px; padding-top: 3px;">
                                            <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                    <div class="col-sm-2">
                                        <a class="btn btn-theme btn-round" onclick="getDatas('decline')"><i class="fa fa-filter"></i> Filter</a>
                                        <a class="btn" onclick="resetParamProcess('decline')"><i class="fa fa-rotate-left"></i> Clear</a>
                                    </div>
                                    @if(array_search("ORORX", $submoduls))
                                    <div class="col-6 text-right">
                                        <button class="btn btn-success btn-round m-b-5 btn-sm">
                                            <i class="fa fa-file-text-o"></i> Export to Excel
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </form>
                            <table cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-order-decline" width="100%">
                                <thead>
                                    <th>No</th>
                                    <th>Order ID</th>
                                    <th>Student</th>
                                    <th>Grade</th>
                                    <th>Course</th>
                                    <th>Teacher</th>
                                    <th>Package</th>
                                    <th>Type</th>
                                    <th>Schedules</th>
                                    <th>Total Bill</th>
                                    <th>Bill Paid</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </thead>
                                <tbody id="table-body4">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Required datatable js -->
    <script src="{{ asset('dashboard/additionalplugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/additionalplugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('dashboard/additionalplugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('dashboard/additionalplugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    
    <!-- Select2 -->
    <script src="{{ asset('dashboard/additionalplugins/select2/js/select2.min.js') }}" type="text/javascript"></script>

    <!-- Date Picker -->
    <script src="{{ asset('dashboard/additionalplugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <!-- Sweet Alert Js  -->
    <script src="{{ asset('dashboard/additionalplugins/sweet-alert/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('dashboard/additionalpages/jquery.sweet-alert.init.js') }}"></script>

    <!-- Time Picker -->
    <script type="text/javascript" src="{{ asset('dashboard/lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/lib/bootstrap-daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/lib/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
@endsection

@section('script-js')
<script type="text/javascript">
    $(document).ready(function() {
        getDatas();
        // Date Picker
        $('.datepicker').datepicker({
            todayHighlight: true,
            autoclose: true,
        });
    });

    function getOrderNotConfirm(start=null, end=null){
        $('#table-order-notconfirm').DataTable().clear().destroy();
        $('#table-order-notconfirm').DataTable({
            "responsive": true,
            "processing" : true,
            "serverSide" : true,
            "order": [[ 0, "desc" ]],
            "ajax" : {
                "url" : "{{ route('order.index') }}",
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                    "start_date" : start,
                    "end_date" : end,
                }
            },"columns" : [{data : "no", name : "no", searchable : false},
                {data : "order_id", name : "order_id"},
                {data : "student_name", name : "student_name"},
                {data : "grade_id", name : "grade_id"},
                {data : "course_name", name : "course_name"},
                {data : "teacher_name", name : "teacher_name"},
                {data : "package_name", name : "package_name"},
                {data : "order_type", name : "order_type"},
                {data : "schedules", name : "schedules", orderable : false},
                {data : "order_bill", name : "order_bill", render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' )},
                {data : "bill_paid", name : "bill_paid", render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' )},
                {data : "payment_status", name : "payment_status", orderable : false},
                {data : "status", name : "status", orderable : false},
                {data : "options", name : "options", orderable : false, searchable : false,}
            ],
            "columnDefs" : [
                {
                    render: function (data, type, full, meta) {
                        return '<strong>'+data+'</strong>';
                    },
                    targets: [1],
                },
                {
                    "className": "text-center",
                    targets: [10,11,12],
                },
                {
                    render: function (data, type, full, meta) {
                        if(data == 'offline'){
                            return '<span style="background: #f96f59; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Offline</span>';
                        }else{
                            return '<span style="background: #008374; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Online</span>';
                        }
                    },
                    targets: [7],
                },
                {
                    render: function (data, type, full, meta) {
                        if(data == 1){
                            return '<span style="background: #5cb85c; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Paid Off</span>';
                        }else if(data == 2){
                            return '<span style="background: #f0ad4e; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Overpaid</span>';
                        }else{
                            return '<span style="background: #d9534f; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Not Yet Paid Off</span>';
                        }
                    },
                    targets: [11],
                },
                {
                    render: function (data, type, full, meta) {
                        if(isNaN(data)){
                            return data;                            
                        }else{
                            if(data == -1){
                                return '<span style="background: #d9534f; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Declined</span>';
                            }else if(data == 1){
                                return '<span style="background: #5cb85c; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Ongoing</span>';
                            }else if(data == 2){
                                return '<span style="background: #000000; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Finish</span>';
                            }else{
                                return '<span style="background: #f0ad4e; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Not Confirmed</span>';
                            }
                        }
                    },
                    targets: [12],
                }
            ],
            oLanguage : {sProcessing: "<div id='loader'></div>"},
        });
    }

    function getOrderOngoing(start=null, end=null){
        $('#table-order-confirm').DataTable().clear().destroy();
        $('#table-order-confirm').DataTable({
            "responsive": true,
            "processing" : true,
            "serverSide" : true,
            "order": [[ 0, "desc" ]],
            "ajax" : {
                "url" : "{{ route('order.index') }}",
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                    "type" : "confirm",
                    "start_date" : start,
                    "end_date" : end,
                }
            },"columns" : [{data : "no", name : "no", searchable : false},
                {data : "order_id", name : "order_id"},
                {data : "student_name", name : "student_name"},
                {data : "grade_id", name : "grade_id"},
                {data : "course_name", name : "course_name"},
                {data : "teacher_name", name : "teacher_name"},
                {data : "package_name", name : "package_name"},
                {data : "order_type", name : "order_type"},
                {data : "schedules", name : "schedules"},
                {data : "order_bill", name : "order_bill", render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' )},
                {data : "bill_paid", name : "bill_paid", render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' )},
                {data : "payment_status", name : "payment_status", orderable : false},
                {data : "status", name : "status", orderable : false},
                {data : "options", name : "options", orderable : false, searchable : false,}
            ],
            "columnDefs" : [
                {
                    render: function (data, type, full, meta) {
                        return '<strong>'+data+'</strong>';
                    },
                    targets: [1],
                },
                {
                    "className": "text-center",
                    targets: [10,11,12],
                },
                {
                    render: function (data, type, full, meta) {
                        if(data == 'offline'){
                            return '<span style="background: #f96f59; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Offline</span>';
                        }else{
                            return '<span style="background: #008374; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Online</span>';
                        }
                    },
                    targets: [7],
                },
                {
                    render: function (data, type, full, meta) {
                        if(data == 1){
                            return '<span style="background: #5cb85c; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Paid Off</span>';
                        }else if(data == 2){
                            return '<span style="background: #f0ad4e; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Overpaid</span>';
                        }else{
                            return '<span style="background: #d9534f; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Not Yet Paid Off</span>';
                        }
                    },
                    targets: [11],
                },
                {
                    render: function (data, type, full, meta) {
                        if(isNaN(data)){
                            return data;                            
                        }else{
                            if(data == -1){
                                return '<span style="background: #d9534f; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Declined</span>';
                            }else if(data == 1){
                                return '<span style="background: #5cb85c; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Ongoing</span>';
                            }else if(data == 2){
                                return '<span style="background: #000000; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Finish</span>';
                            }else{
                                return '<span style="background: #f0ad4e; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Not Confirmed</span>';
                            }
                        }
                    },
                    targets: [12],
                }
            ],
            oLanguage : {sProcessing: "<div id='loader'></div>"},
        });
    }

    function getOrderDecline(start=null, end=null){
        $('#table-order-decline').DataTable().clear().destroy();
        $('#table-order-decline').DataTable({
            "responsive": true,
            "processing" : true,
            "serverSide" : true,
            "order": [[ 0, "desc" ]],
            "ajax" : {
                "url" : "{{ route('order.index') }}",
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                    "type" : "decline",
                    "start_date" : start,
                    "end_date" : end,
                }
            },"columns" : [{data : "no", name : "no", searchable : false},
                {data : "order_id", name : "order_id"},
                {data : "student_name", name : "student_name"},
                {data : "grade_id", name : "grade_id"},
                {data : "course_name", name : "course_name"},
                {data : "teacher_name", name : "teacher_name"},
                {data : "package_name", name : "package_name"},
                {data : "order_type", name : "order_type"},
                {data : "schedules", name : "schedules"},
                {data : "order_bill", name : "order_bill", render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' )},
                {data : "bill_paid", name : "bill_paid", render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' )},
                {data : "payment_status", name : "payment_status", orderable : false},
                {data : "status", name : "status", orderable : false},
                {data : "options", name : "options", orderable : false, searchable : false,}
            ],
            "columnDefs" : [
                {
                    render: function (data, type, full, meta) {
                        return '<strong>'+data+'</strong>';
                    },
                    targets: [1],
                },
                {
                    "className": "text-center",
                    targets: [10,11,12],
                },
                {
                    render: function (data, type, full, meta) {
                        if(data == 'offline'){
                            return '<span style="background: #f96f59; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Offline</span>';
                        }else{
                            return '<span style="background: #008374; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Online</span>';
                        }
                    },
                    targets: [7],
                },
                {
                    render: function (data, type, full, meta) {
                        if(data == 1){
                            return '<span style="background: #5cb85c; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Paid Off</span>';
                        }else if(data == 2){
                            return '<span style="background: #f0ad4e; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Overpaid</span>';
                        }else{
                            return '<span style="background: #d9534f; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Not Yet Paid Off</span>';
                        }
                    },
                    targets: [11],
                },
                {
                    render: function (data, type, full, meta) {
                        if(isNaN(data)){
                            return data;                            
                        }else{
                            if(data == -1){
                                return '<span style="background: #d9534f; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Declined</span>';
                            }else if(data == 1){
                                return '<span style="background: #5cb85c; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Ongoing</span>';
                            }else if(data == 2){
                                return '<span style="background: #000000; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Finish</span>';
                            }else{
                                return '<span style="background: #f0ad4e; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Not Confirmed</span>';
                            }
                        }
                    },
                    targets: [12],
                }
            ],
            oLanguage : {sProcessing: "<div id='loader'></div>"},
        });
    }

    function getOrderFinish(start=null, end=null){
        $('#table-order-finish').DataTable().clear().destroy();
        $('#table-order-finish').DataTable({
            "responsive": true,
            "processing" : true,
            "serverSide" : true,
            "order": [[ 0, "desc" ]],
            "ajax" : {
                "url" : "{{ route('order.index') }}",
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                    "type" : "finish",
                    "start_date" : start,
                    "end_date" : end,
                }
            },"columns" : [{data : "no", name : "no", searchable : false},
                {data : "order_id", name : "order_id"},
                {data : "student_name", name : "student_name"},
                {data : "grade_id", name : "grade_id"},
                {data : "course_name", name : "course_name"},
                {data : "teacher_name", name : "teacher_name"},
                {data : "package_name", name : "package_name"},
                {data : "order_type", name : "order_type"},
                {data : "schedules", name : "schedules"},
                {data : "order_bill", name : "order_bill", render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' )},
                {data : "bill_paid", name : "bill_paid", render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' )},
                {data : "payment_status", name : "payment_status", orderable : false},
                {data : "status", name : "status", orderable : false},
                {data : "options", name : "options", orderable : false, searchable : false,}
            ],
            "columnDefs" : [
                {
                    render: function (data, type, full, meta) {
                        return '<strong>'+data+'</strong>';
                    },
                    targets: [1],
                },
                {
                    "className": "text-center",
                    targets: [10,11,12],
                },
                {
                    render: function (data, type, full, meta) {
                        if(data == 'offline'){
                            return '<span style="background: #f96f59; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Offline</span>';
                        }else{
                            return '<span style="background: #008374; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Online</span>';
                        }
                    },
                    targets: [7],
                },
                {
                    render: function (data, type, full, meta) {
                        if(data == 1){
                            return '<span style="background: #5cb85c; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Paid Off</span>';
                        }else if(data == 2){
                            return '<span style="background: #f0ad4e; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Overpaid</span>';
                        }else{
                            return '<span style="background: #d9534f; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Not Yet Paid Off</span>';
                        }
                    },
                    targets: [11],
                },
                {
                    render: function (data, type, full, meta) {
                        if(isNaN(data)){
                            return data;                            
                        }else{
                            if(data == -1){
                                return '<span style="background: #d9534f; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Declined</span>';
                            }else if(data == 1){
                                return '<span style="background: #5cb85c; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Ongoing</span>';
                            }else if(data == 2){
                                return '<span style="background: #000000; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Finish</span>';
                            }else{
                                return '<span style="background: #f0ad4e; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Not Confirmed</span>';
                            }
                        }
                    },
                    targets: [12],
                }
            ],
            oLanguage : {sProcessing: "<div id='loader'></div>"},
        });
    }

    function getDatas(type=null){
        if(type == "not-confirm"){
            start_date = $('#notconfirm_start_date').val();
            end_date = $('#notconfirm_end_date').val();
            getOrderNotConfirm(start_date, end_date);
        }else if(type == "confirm"){
            start_date = $('#confirm_start_date').val();
            end_date = $('#confirm_end_date').val();
            getOrderOngoing(start_date, end_date);
        }else if(type == "decline"){
            start_date = $('#decline_start_date').val();
            end_date = $('#decline_end_date').val();
            getOrderDecline(start_date, end_date);
        }else if(type == "finish"){
            start_date = $('#finish_start_date').val();
            end_date = $('#finish_end_date').val();
            getOrderFinish(start_date, end_date);
        }else{
            getOrderNotConfirm();
            getOrderOngoing();
            getOrderDecline();
            getOrderFinish();
        }
    }

    function resetParamProcess(type=null){
        if(type == "not-confirm"){
            $('#notconfirm_start_date').val("");
            $('#notconfirm_end_date').val("");
        }else if(type == "confirm"){
            $('#confirm_start_date').val("");
            $('#confirm_end_date').val("");
        }else if(type == "decline"){
            $('#decline_start_date').val("");
            $('#decline_end_date').val("");
        }else if(type == "finish"){
            $('#finish_start_date').val("");
            $('#finish_end_date').val("");
        }

        getDatas(type);
    }

    function moveToBox(id){
        $('#'+id).data("datepicker").show();
    }

    function create_data(){
        console.log("create")
        $.ajax({
            url : "{{route('order.create')}}",
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function edit_data(id){
        console.log(id)
        $.ajax({
            url : "/order/"+id+"/edit",
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function confirm_order(id){
        var token = $("meta[name='csrf-token']").attr("content");
        console.log(id)
        swal({
            title: 'Confirm this Order?',
            text: "Confirmed Order will be forward to Student!",
            type: 'warning',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Confirm Order',
            cancelButtonText: 'Decline Order',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            buttonsStyling: false
        }).then(function () {
            $.ajax({
                url : "/order/"+id+"/changestatus",
                type : "post",
                dataType: 'json',
                data: {
                    "_token":token,
                    "status":1,
                }
            }).done(function (data) {
                location.reload();
            }).fail(function (msg) {
                swal(
                    'Failed',
                    'Failed to confirm',
                    'error'
                )
            });
        }, function (dismiss) {
            if (dismiss === 'cancel') {
                $.ajax({
                    url : "/order/"+id+"/changestatus",
                    type : "post",
                    dataType: 'json',
                    data: {
                        "_token":token,
                        "status":-1,
                    }
                }).done(function (data) {
                    location.reload();
                }).fail(function (msg) {
                    swal(
                        'Failed',
                        'Failed to confirm',
                        'error'
                    )
                });
            }
        })
    }

    function finishing_order(id){
        var token = $("meta[name='csrf-token']").attr("content");

        swal({
            title: 'Finish this Order?',
            text: "Order will be show as Finished Course!",
            type: 'warning',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Finishing Order',
            cancelButtonText: 'Cancel Order',
            confirmButtonClass: 'btn btn-info',
            cancelButtonClass: 'btn btn-danger m-l-10',
            buttonsStyling: false
        }).then(function () {
            $.ajax({
                url : "/order/"+id+"/changestatus",
                type : "post",
                dataType: 'json',
                data: {
                    "_token":token,
                    "status":2,
                }
            }).done(function (data) {
                location.reload();
            }).fail(function (msg) {
                swal(
                    'Failed',
                    'Failed to confirm',
                    'error'
                )
            });
        }, function (dismiss) {
            if (dismiss === 'cancel') {
                $.ajax({
                    url : "/order/"+id+"/changestatus",
                    type : "post",
                    dataType: 'json',
                    data: {
                        "_token":token,
                        "status":0,
                    }
                }).done(function (data) {
                    location.reload();
                }).fail(function (msg) {
                    swal(
                        'Failed',
                        'Failed to confirm',
                        'error'
                    )
                });
            }
        })
    }

    function canceling_finish_order(id){
        var token = $("meta[name='csrf-token']").attr("content");

        swal({
            title: 'Canceling Finish Order?',
            text: "This Order will be back to Ongoing course",
            type: 'warning',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Back to Ongoing',
            cancelButtonText: 'Close',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            buttonsStyling: false
        }).then(function () {
            $.ajax({
                url : "/order/"+id+"/changestatus",
                type : "post",
                dataType: 'json',
                data: {
                    "_token":token,
                    "status":1,
                }
            }).done(function (data) {
                location.reload();
            }).fail(function (msg) {
                swal(
                    'Failed',
                    'Failed to confirm',
                    'error'
                )
            });
        })
    }

    function delete_data(id){
        var token = $("meta[name='csrf-token']").attr("content");

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            buttonsStyling: false
        }).then(function () {
            $.ajax({
                url: "order/"+id,
                type: 'DELETE',
                data: {
                    "_token": token,
                },
            }).done(function (data) {
                swal(
                    'Deleted!',
                    'Your data has been deleted.',
                    'success'
                )
                location.reload();
            }).fail(function (msg) {
                swal(
                    'Failed',
                    'Failed to delete',
                    'error'
                )
            });

        }, function (dismiss) {
            // dismiss can be 'cancel', 'overlay',
            // 'close', and 'timer'
            if (dismiss === 'cancel') {
                swal(
                    'Cancelled',
                    'Your data is safe :)',
                    'error'
                )
            }
        })
    }

    function get_teacher(params) {
        var jenisdata = "get_teacher";
        get_schedule(params);
        $.ajax({
            url : "{{route('getData')}}",
            type : "get",
            dataType: 'json',
            data:{
                params: params,
                jenisdata: jenisdata,
            },
        }).done(function (data) {
            $('#teacher_id').html(data.append);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    // function get_package(params){
    //     var jenisdata = "get_package";
    //     get_schedule(params);
    //     $.ajax({
    //         url : "{{route('getData')}}",
    //         type : "get",
    //         dataType: 'json',
    //         data:{
    //             params: params,
    //             jenisdata: jenisdata,
    //         },
    //     }).done(function (data) {
    //         $('#package_id').html(data.append);
    //     }).fail(function (msg) {
    //         alert('Gagal menampilkan data, silahkan refresh halaman.');
    //     });
    // }

    function get_schedule(params) {
        var jenisdata = "get_schedule";
        var element_teacher = document.getElementById('teacher_id');
        var select_value = element_teacher.options[element_teacher.selectedIndex].getAttribute('data-text');

        var line_schedule = document.getElementById('line_schedule');

        if(select_value == 1){
            line_schedule.style.display = "block";
            $.ajax({
                url : "{{route('getData')}}",
                type : "get",
                dataType: 'json',
                data:{
                    params: params,
                    jenisdata: jenisdata,
                },
            }).done(function (data) {
                $('#teacher_schedules').html(data.append);
            }).fail(function (msg) {
                alert('Gagal menampilkan data, silahkan refresh halaman.');
            });
        }else{
            line_schedule.style.display = "none";
        }
    }

    function get_bill(){
        // var teacher_id = $('#teacher_id').val();
        var package_id = $('#package_id').val();

        if(teacher_id != null && course_id != null && package_id != null){
            generateSchedule();
            $.ajax({
                url: "{{ route('getTeacherFee') }}",
                type: 'GET',
                data: {
                    // "teacher_id": teacher_id,
                    "package_id": package_id,
                },
            }).done(function (data) {
                $('#order_bill_display').val(number_format(data));
                $('#order_bill').val(data);
            }).fail(function (msg) {
                swal(
                    'Failed',
                    'Failed to delete',
                    'error'
                )
            });
        }
    }

    function printPdf(id){
        windowUrl = $('#route'+id).val();
        console.log(windowUrl)
        windowName = "Invoice";
        var printWindow = window.open(windowUrl, windowName, 'left=50000,top=50000,width=0,height=0');
        printWindow.focus();
        setTimeout(function(){ printWindow.close(); }, 3000);
        printWindow.print();
    }

    function copyText(order_id,text) {
        /* Copy the text inside the text field */
        navigator.clipboard.writeText(text);

        $('#copytextbtn'+order_id).popover({content: "Whatsapp Text copied to clipboard",animation: true});

        // Set the date we're counting down to (dalam kasus ini ditampilkan selama 5 detik)
        var hide = new Date(new Date().getTime() + 5000).getTime();

        var x = setInterval(function() {
            // code goes here

            // Get today's date and time
            var now = new Date().getTime();
            
            var distance = hide - now;

            if(distance < 0){
                clearInterval(x);
                $('[data-toggle="popover"]').popover("hide");
            }
        }, 1000)
        /* Alert the copied text */
    }

    function generateSchedule(){
        var jenisdata = "generateSchedule";
        var course_start = $('#course_start').val();
        var teacher_schedules = $("select[name='teacher_schedules[]']").map(function(){return $(this).val()}).get();
        var teacher_id = $('#teacher_id').val();
        var package_id = $('#package_id').val();

        if(course_start != null && teacher_schedules.length != 0 && teacher_id != null && package_id != null){
            $.ajax({
                url : "{{route('getData')}}",
                type : "get",
                dataType: 'json',
                data:{
                    params: course_start,
                    jenisdata: jenisdata,
                    teacher_id: teacher_id,
                    package_id: package_id,
                    teacher_schedules: teacher_schedules,
                },
            }).done(function (data) {
                $('#table-body-detail').empty();
                console.log(data);
                for(i=0; i<data.length; i++){
                    count = i+1;
                    console.log(data[i].schedule_time);
                    var format_schedule = moment(data[i].schedule_time, "YYYY-MM-DD HH:mm:ss").format('ddd, DD-MM-YYYY, HH:mm:ss');
                    var append = '<tr style="width:100%" id="trow'+count+'" class="trow"><td style="width:10%">'+count+'</td><td style="width:70%">'+format_schedule+'</td><input type="hidden" name="schedule_datetime[]" id="schedule_datetime'+count+'" value="'+data[i].schedule_time+'"><td class="text-center" style="width:20%"><a href="javascript:;" type="button" class="btn btn-primary btn-sm" onclick="edit_row('+count+')">Edit</a> <a href="javascript:;" type="button" class="btn btn-danger btn-sm" onclick="delete_row('+count+')" >Delete</a></td></tr>';
                    $('#table-body-detail').append(append);
                }
            }).fail(function (msg) {
                alert('Gagal menampilkan data, silahkan refresh halaman.');
            });
        }
    }

    function view_report(id){
        $.ajax({
            url : "home/"+id+"/edit",
            type : "get",
            dataType: 'json',
            data: {
                type:"report",
            },
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function view_review(id){
        $.ajax({
            // url : "home/"+id+"/edit",
            url: "{{ route('orderreview.create') }}",
            type: "get",
            dataType: 'json',
            data: {
                // type:"review",
                id: id,
            },
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }
</script>
@if(session('order'))
<script>
    //  $.holdReady(true);
    // setTimeout(function () {
    //     // Setting a variable until
    //     // document.ready is called
    //     // const myVar = "GFG";
    //     // $.holdReady(false);


    //     console.log("timeout")
    // }, 5000);

    $(document).ready(function() {
        create_data();
        $(".modal").modal("show");
    });
</script>
@endif
@endsection