@extends('dashboard.layout.main')

@section('title')
Dashboard
@endsection

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
<!-- Time Picker -->
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/lib/bootstrap-timepicker/compiled/timepicker.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/lib/bootstrap-datetimepicker/css/datetimepicker.css') }}" />
<!-- Sweet Alert css -->
<link href="{{ asset('dashboard/additionalplugins/sweet-alert/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
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

@section('content')
<!-- page start-->
<div class="content-panel">
    <div class="mb">
        <h4><strong>Ongoing Course</strong></h4>
    </div>
    <div class="adv-table">
        <table cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-ongoing-order" width="100%">
            <thead>
                <th>No</th>
                <th>Order ID</th>
                <th>Course</th>
                <th>Grade</th>
                <th>Teacher</th>
                <th>Package</th>
                <th>Type</th>
                <th>Schedule</th>
                <th>Report</th>
            </thead>
            <tbody id="table-body-ongoing-order">
            </tbody>
        </table>
    </div>
</div>
<br>
<div class="content-panel">
    <div class="mb">
        <h4><strong>Order Not Yet Confirmed</strong></h4>
    </div>
    <div class="adv-table">
        <table cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-notyet-confirm-order" width="100%">
            <thead>
                <th>No</th>
                <th>Order ID</th>
                <th>Course</th>
                <th>Grade</th>
                <th>Teacher</th>
                <th>Package</th>
                <th>Schedules</th>
                <th>Type</th>
                <th>Payment Status</th>
                <th>Order Status</th>
            </thead>
            <tbody id="table-body-notyet-confirm-order">
            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

<!-- Time Picker -->
<script type="text/javascript" src="{{ asset('dashboard/lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/lib/bootstrap-daterangepicker/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/lib/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>

<!-- Sweet Alert Js  -->
<script src="{{ asset('dashboard/additionalplugins/sweet-alert/sweetalert2.min.js') }}"></script>
<script src="{{ asset('dashboard/additionalpages/jquery.sweet-alert.init.js') }}"></script>
@endsection

@section('script-js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#table-ongoing-order').DataTable({
            "processing" : true,
            "serverSide" : true,
            "order": [[ 0, "desc" ]],
            "lengthMenu": [[5,10,25,50,100], [5,10,25, 50, 100]],
            "pageLength": 5,
            "ajax" : {
                "url" : "{{ route('home.index') }}",
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                    "type" : "ongoing-order",
                }
            },"columns" : [{data : "no", name : "no", searchable : false},
                {data : "order_id", name : "order_id"},
                {data : "course_name", name : "course_name"},
                {data : "grade_id", name : "grade_id"},
                {data : "teacher_name", name : "teacher_name"},
                {data : "package_name", name : "package_name"},
                {data : "order_type", name : "order_type"},
                {data : "schedule", name : "schedule", orderable : false, searchable : false,},
                {data : "report", name : "report", orderable : false, searchable : false,}
            ],
            "columnDefs" : [
                {
                    render: function (data, type, full, meta) {
                        return '<strong>'+data+'</strong>';
                    },
                    targets: [1],
                },
                {
                    render: function (data, type, full, meta) {
                        if(data == 'offline'){
                            return '<span style="background: #f96f59; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Offline</span>';
                        }else{
                            return '<span style="background: #008374; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Online</span>';
                        }
                    },
                    targets: [6],
                }
            ],
            oLanguage : {sProcessing: "<div id='loader'></div>"},
        });

        $('#table-notyet-confirm-order').DataTable({
            "processing" : true,
            "serverSide" : true,
            "order": [[ 0, "desc" ]],
            "lengthMenu": [[5,10,25,50,100], [5,10,25, 50, 100]],
            "pageLength": 5,
            "ajax" : {
                "url" : "{{ route('home.index') }}",
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                    "type" : "notyet-confirm-order",
                }
            },"columns" : [{data : "no", name : "no", searchable : false},
                {data : "order_id", name : "order_id"},
                {data : "course_name", name : "course_name"},
                {data : "grade_id", name : "grade_id"},
                {data : "teacher_name", name : "teacher_name"},
                {data : "package_name", name : "package_name"},
                {data : "schedules", name : "schedules", orderable : false},
                {data : "order_type", name : "order_type"},
                {data : "payment_status", name : "payment_status", orderable : false, searchable : false},
                {data : "order_status", name : "order_status", orderable : false, searchable : false,}
            ],
            "columnDefs" : [
                {
                    render: function (data, type, full, meta) {
                        return '<strong>'+data+'</strong>';
                    },
                    targets: [1],
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
                }
            ],
            oLanguage : {sProcessing: "<div id='loader'></div>"},
        });

        // Select2
        $(".select2").select2({
            templateResult: formatState,
            templateSelection: formatState
        });

        function formatState (opt) {
            if (!opt.id) {
                return opt.text.toUpperCase();
            }

            var optimage = $(opt.element).attr('data-image');
            console.log(optimage)
            if(!optimage){
            return opt.text.toUpperCase();
            } else {
                var $opt = $(
                '<span><img src="' + optimage + '" width="60px" /> ' + opt.text.toUpperCase() + '</span>'
                );
                return $opt;
            }
        };
    });

    function view_schedule(id){
        $.ajax({
            url : "home/"+id+"/edit",
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
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
</script>
@endsection