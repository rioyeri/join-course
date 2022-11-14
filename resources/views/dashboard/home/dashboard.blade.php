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
{{-- Switchery --}}
<link href="{{ asset('dashboard/additionalplugins/switchery/switchery.min.css') }}" rel="stylesheet" />
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

    img.output{
        object-fit:cover;
        display:block;
        width:100px;
        height:100px;
        display: flex;
    }
</style>
@endsection

@section('content')
<!-- page start-->
<div class="content-panel">
    <h4 style="text-align: center;"><strong>Statistics</strong></h4>
    <div class="text-right" style="margin:-35px 20px 20px 0;">
        <input type="checkbox" checked id="switch1" data-plugin="switchery"/>
    </div>
    <div id="stats-container">
        <div class="text-right" style="margin: 0 10px 10px 0;">
            <label style="margin-right: 10px;">Show : </label>
            <select id="sort" onchange="filter(this.value)">
                <option value="all">All</option>
                <option value="thismonth">This Month</option>
            </select>
        </div>
        <div class="row mb-5">
            <div class="col-lg-4">
                <div class="content-panel-gray" id="graph_grade" style="margin-left: 10px">
                    <h5 class="header-title" style="text-align:center; margin: 2px 0 2px 0;" id="title-grade">Statistic of Student Grade</h5>
                    <div class="widget-chart" style="text-align: center;">
                        <div id="grade" class="text-center"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="content-panel-gray" id="graph_mostsubject">
                    <h5 class="header-title" style="text-align:center; margin: 2px 0 2px 0;" id="title-mostsubject">Most Subject has Ordered</h5>
                    <div class="widget-chart" style="text-align: center;">
                        <div id="mostsubject" class="text-center"></div>
                        {{-- <ul class="list-inline chart-detail-list mb-0">
                            @foreach ($best_teacher as $sh)
                                <li class="list-inline-item">
                                    <h5 style="color: {{ $sh['color'] }};"><i class="fa fa-circle m-r-5"></i>{{ $sh['teacher_name'] }}</h5>
                                </li>
                            @endforeach
                        </ul> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="content-panel-gray" id="graph_bestteacher">
                    <h5 class="header-title" style="text-align:center; margin: 2px 0 2px 0;" id="title-bestteacher">10 Best Teacher on</h5>
                    <div class="widget-chart" style="text-align: center;">
                        <div id="bestTeacher" class="text-center"></div>
                        {{-- <ul class="list-inline chart-detail-list mb-0">
                            @foreach ($best_teacher as $sh)
                                <li class="list-inline-item">
                                    <h5 style="color: {{ $sh['color'] }};"><i class="fa fa-circle m-r-5"></i>{{ $sh['teacher_name'] }}</h5>
                                </li>
                            @endforeach
                        </ul> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="content-panel-gray" id="graph_ordertype">
                    <h5 class="header-title" style="text-align:center; margin: 2px 0 2px 0;" id="title-ordertype">Type of Order</h5>
                    <div class="widget-chart" style="text-align: center;">
                        <div id="ordertype" class="text-center"></div>
                        {{-- <ul class="list-inline chart-detail-list mb-0">
                            @foreach ($best_teacher as $sh)
                                <li class="list-inline-item">
                                    <h5 style="color: {{ $sh['color'] }};"><i class="fa fa-circle m-r-5"></i>{{ $sh['teacher_name'] }}</h5>
                                </li>
                            @endforeach
                        </ul> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="content-panel-gray" id="graph_package" style="margin-right: 10px">
                    <h5 class="header-title" style="text-align:center; margin: 2px 0 2px 0;" id="title-package">Package</h5>
                    <div class="widget-chart" style="text-align: center;">
                        <div id="package" class="text-center"></div>
                        {{-- <ul class="list-inline chart-detail-list mb-0">
                            @foreach ($best_teacher as $sh)
                                <li class="list-inline-item">
                                    <h5 style="color: {{ $sh['color'] }};"><i class="fa fa-circle m-r-5"></i>{{ $sh['teacher_name'] }}</h5>
                                </li>
                            @endforeach
                        </ul> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="content-panel">
    <h4 style="text-align: center;"><strong>Report</strong></h4>
    <div class="text-right" style="margin:-35px 20px 20px 0;">
        <input type="checkbox" checked id="switch2" data-plugin="switchery"/>
    </div>
    <div class="row mb-5" id="report-container">
        <div class="col-lg-6">
            <div class="content-panel-gray" id="graph_orderreport" style="margin-left: 10px">
                <h5 class="header-title" style="text-align:center; margin: 2px 0 2px 0;" id="title-grade">Order Report</h5>
                <div class="widget-chart" style="text-align: center;">
                    <div id="orderreport" class="text-center"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="content-panel-gray" id="graph_incomereport" style="margin-right: 10px">
                <h5 class="header-title" style="text-align:center; margin: 2px 0 2px 0;" id="title-grade">Income Report</h5>
                <div class="widget-chart" style="text-align: center;">
                    <div id="incomereport" class="text-center"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="content-panel">
    <div class="mb">
        <h4><strong>Ongoing Course</strong></h4>
    </div>
    <div class="adv-table">
        <table cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-ongoing-order" width="100%">
            <thead>
                <th>No</th>
                <th>Order ID</th>
                <th>Student</th>
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
                <th>Student</th>
                <th>Course</th>
                <th>Grade</th>
                <th>Teacher</th>
                <th>Package</th>
                <th>Schedule</th>
                <th>Type</th>
                <th>Payment Status</th>
                <th>Order Status</th>
            </thead>
            <tbody id="table-body-notyet-confirm-order">
            </tbody>
        </table>
    </div>
</div>
<br>
<div class="content-panel">
    <div class="mb">
        <h4><strong>Incoming Payment</strong></h4>
    </div>
    <div class="adv-table">
        <table cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-incoming-payment" width="100%">
            <thead>
                <th>No</th>
                <th>Invoice ID</th>
                <th>Order ID</th>
                <th>Student Name</th>
                <th>Order Bill</th>
                <th>Payment Amount</th>
                <th>Destination Account</th>
                <th>Payment Evidence</th>
                <th>Payment Time</th>
                <th>Payment Status</th>
            </thead>
            <tbody id="table-body-incoming-payment">
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

<!-- Charts -->
<script src="{{ asset('dashboard/lib/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('dashboard/lib/morris/morris.min.js') }}"></script>

<!-- Switchery -->
<script src="{{ asset('dashboard/additionalplugins/switchery/switchery.min.js') }}"></script>
@endsection

@section('script-js')
<script type="text/javascript">
    $(document).ready(function() {

        filter("all");
        getOrderReport();
        getIncomeReport();
        setSwitch();

        $('#table-ongoing-order').DataTable({
            "responsive": true,
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
                {data : "student_name", name : "student_name"},
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
                    targets: [7],
                }
            ],
            oLanguage : {sProcessing: "<div id='loader'></div>"},
        });

        $('#table-notyet-confirm-order').DataTable({
            "responsive": true,
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
                {data : "student_name", name : "student_name"},
                {data : "course_name", name : "course_name"},
                {data : "grade_id", name : "grade_id"},
                {data : "teacher_name", name : "teacher_name"},
                {data : "package_name", name : "package_name"},
                {data : "schedules", name : "schedules"},
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
                    targets: [8],
                }
            ],
            oLanguage : {sProcessing: "<div id='loader'></div>"},
        });

        $('#table-incoming-payment').DataTable({
            "responsive": true,
            "processing" : true,
            "serverSide" : true,
            "order": [[ 8, "asc" ]],
            "ajax" : {
                "url" : "{{ route('orderpayment.index') }}",
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                }
            },"columns" : [{data : "no", name : "no", searchable : false},
                {data : "invoice_id", name : "invoice_id"},
                {data : "order_id", name : "order_id"},
                {data : "student_name", name : "student_name"},
                {data : "order_bill", name : "order_bill", render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' )},
                {data : "payment_amount", name : "payment_amount", render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' )},
                {data : "payment_method", name : "payment_method"},
                {data : "payment_evidence", name : "payment_evidence", orderable : false, searchable: false},
                {data : "payment_time", name : "payment_time"},
                {data : "payment_confirmation", name : "payment_confirmation"},
            ],
            "columnDefs" : [
                {
                    render: function (data, type, full, meta) {
                        if(data == null){
                            var $image = '<img class="output text-center" src="dashboard/assets/noimage.jpg">';
                        }else{
                            var path = 'dashboard/assets/payment/'+full.order_id.substr(1)+'/'+data;
                            var $image = '<a href="'+path+'" class="image-popup"><img class="output text-center" src="'+path+'"></a>';
                        }
                        return $image;
                    },
                    targets: [7],
                },
                {
                    render: function (data, type, full, meta) {
                        return '<strong>'+data+'</strong>';
                    },
                    targets: [1],
                }
            ],
            oLanguage : {sProcessing: "<div id='loader'></div>"},
            drawCallback: function(){
                $('.image-popup').magnificPopup({
                    type: 'image',
                });
            }
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
            if(!optimage){
            return opt.text.toUpperCase();
            } else {
                var $opt = $(
                '<span><img src="' + optimage + '" width="60px" /> ' + opt.text.toUpperCase() + '</span>'
                );
                return $opt;
            }
        };

        // Switchery 1
        switch1.onchange = function() {
            if(switch1.checked == true){
                document.getElementById("stats-container").style.display = 'block';
            }else{
                document.getElementById("stats-container").style.display = 'none';
            }
        };

        switch2.onchange = function() {
            if(switch2.checked == true){
                document.getElementById("report-container").style.display = 'block';
            }else{
                document.getElementById("report-container").style.display = 'none';
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

    function confirm_order(id){
        var token = $("meta[name='csrf-token']").attr("content");
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

    function change_status(id, status){
        var token = $("meta[name='csrf-token']").attr("content");
        if(status == 1){
            var btn_text = "Cancel Confirm Payment";
            var btn_class = "btn btn-warning";
            var new_status = 0;
        }else if(status == -1){
            var btn_text = "Confirm Payment";
            var btn_class = "btn btn-success";
            var new_status = 1;
        }else{
            var btn_text = "Confirm Payment";
            var btn_class = "btn btn-success";
            var new_status = 1; 
        }
        swal({
            title: 'Confirm this Order?',
            text: "Confirmed Order will be forward to Student!",
            type: 'warning',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: btn_text,
            cancelButtonText: 'Decline Payment',
            confirmButtonClass: btn_class,
            cancelButtonClass: 'btn btn-danger m-l-10',
            buttonsStyling: false
        }).then(function () {
            $.ajax({
                url : "/orderpayment/"+id+"/changestatus",
                type : "post",
                dataType: 'json',
                data: {
                    "_token":token,
                    "status":new_status,
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
            console.log(dismiss);
            if(dismiss == 'cancel'){
                $.ajax({
                    url : "/orderpayment/"+id+"/changestatus",
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

    function getGraphBestTeacher(value){
        // Refresh Chart
        $('#bestTeacher').html("");

        $.ajax({
            url : "{{ route('home.index') }}",
            type : "get",
            dataType: 'json',
            data: {
                type:"chart_bestteacher",
                sort:value,
            },
        }).done(function (data) {
            $("#bestTeacher").css("height","180");
            $("#graph_bestteacher").css("height", "210px");

            if(value == "all"){
                $("#title-bestteacher").html("Teacher Stats");
            }else{
                $("#title-bestteacher").html("Teacher of the "+data[0].month_name);
            }

            var count = data.length;
            var datas = [];
            var colors = [];

            data.forEach(function(key){
                name = key.teacher_name;
                qty = key.order_qty;
                color = key.color;

                var x = {label:name, value:qty};
                datas.push(x);
                colors.push(color);
            });

            Morris.Donut({
                element: 'bestTeacher',
                data : datas,
                colors: colors,
                height: "180",
                formatter: function (y) { return y }
            });
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function getGraphMostSubject(value){
        // Refresh Chart
        $('#mostsubject').html("");

        $.ajax({
            url : "{{ route('home.index') }}",
            type : "get",
            dataType: 'json',
            data: {
                type:"chart_mostsubject",
                sort:value,
            },
        }).done(function (data) {
            $("#mostsubject").css("height","180");
            $("#graph_mostsubject").css("height", "210px");

            if(value == "all"){
                $("#title-mostsubject").html("Most Subject Stats");
            }else{
                $("#title-mostsubject").html("Most Subject in "+data[0].month_name);
            }

            var count = data.length;
            var datas = [];
            var colors = [];

            data.forEach(function(key){
                name = key.course_name;
                qty = key.course_count;
                color = key.color;

                var x = {label:name, value:qty};
                datas.push(x);
                colors.push(color);
            });

            Morris.Donut({
                element: 'mostsubject',
                data : datas,
                colors: colors,
                height: "180",
                formatter: function (y) { return y }
            });
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function getGradeStatistic(value){
        // Refresh Chart
        $('#grade').html("");

        $.ajax({
            url : "{{ route('home.index') }}",
            type : "get",
            dataType : "json",
            data: {
                type:"chart_grade",
                sort:value,
            },
        }).done(function (data) {
            $("#grade").css("height","180");
            $("#graph_grade").css("height", "210px");
            if(value == "all"){
                $("#title-grade").html("Grade Statistic");
            }else{
                $("#title-grade").html("Grade Statistic in "+data[0].month_name);
            }

            var colors = [];

            data.forEach(function(key){
                color = key.color;
                colors.push(color);
            });

            Morris.Bar({
                element: 'grade',
                data: data,
                xkey: 'grade_name',
                ykeys: ['grade_count','order_count'],
                labels: ['Jumlah murid','Jumlah Order'],
                xLabelAngle: 90,
                hideHover: true,
                hoverCallback: function(index, options, content, row) {
                    return '<div style="background:rgba(255,255,255,1); border-radius:4px; margin: 0 30% 0 30%">'+content+'</div>';
                },
                gridTextSize: 10,
                barColors: colors,
                resize: true,
            });
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        })
    }

    function getGraphOrderType(value){
        // Refresh Chart
        $('#ordertype').html("");
        $.ajax({
            url : "{{ route('home.index') }}",
            type : "get",
            dataType: 'json',
            data: {
                type:"chart_ordertype",
                sort:value,
            },
        }).done(function (data) {
            $("#ordertype").css("height","180");
            $("#graph_ordertype").css("height", "210px");

            if(value == "all"){
                $("#title-ordertype").html("Type of Order Stats");
            }else{
                $("#title-ordertype").html("Type of Order Stats in "+data[0].month_name);
            }

            var count = data.length;
            var datas = [];
            var colors = [];

            data.forEach(function(key){
                name = key.order_type;
                qty = key.order_count;
                color = key.color;

                var x = {label:name, value:qty};
                datas.push(x);
                colors.push(color);
            });

            Morris.Donut({
                element: 'ordertype',
                data : datas,
                colors: colors,
                height: "180",
                formatter: function (y) { return y }
            });
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function getGraphPackage(value){
        // Refresh Chart
        $('#package').html("");

        $.ajax({
            url : "{{ route('home.index') }}",
            type : "get",
            dataType: 'json',
            data: {
                type:"chart_package",
                sort:value,
            },
        }).done(function (data) {
            $("#package").css("height","180");
            $("#graph_package").css("height", "210px");

            if(value == "all"){
                $("#title-package").html("Package Stats");
            }else{
                $("#title-package").html("Package Stats in "+data[0].month_name);
            }

            var count = data.length;
            var datas = [];
            var colors = [];

            data.forEach(function(key){
                name = key.package_name;
                qty = key.order_qty;
                color = key.color;

                var x = {label:name, value:qty};
                datas.push(x);
                colors.push(color);
            });

            Morris.Donut({
                element: 'package',
                data : datas,
                colors: colors,
                height: "180",
                formatter: function (y) { return y }
            });
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function getOrderReport(){
        // Refresh Chart
        $('#orderreport').html("");

        $.ajax({
            url : "{{ route('home.index') }}",
            type : "get",
            dataType : "json",
            data: {
                type:"chart_orderreport",
            },
        }).done(function (data) {
            $("#orderreport").css("height","180");
            $("#graph_orderreport").css("height", "210px");
            // if(value == "all"){
            //     $("#title-orderreport").html("Order Report");
            // }else{
            //     $("#title-orderreport").html("Grade Statistic in "+data[0].month_name);
            // }

            var colors = [];

            data.forEach(function(key){
                color = key.color;
                colors.push(color);
            });

            Morris.Line({
                element: 'orderreport',
                data: data,
                xkey: 'report_period',
                ykeys: ['report_count'],
                labels: ['Total Order'],
                lineColors:['#008374'],
                xLabelAngle: 60,
                hideHover: true,
                hoverCallback: function(index, options, content, row) {
                    return '<div style="background:rgba(255,255,255,1); border-radius:4px; margin: 0 30% 0 30%">'+content+'</div>';
                },
                resize: true,

            });
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        })
    }

    function getIncomeReport(){
        // Refresh Chart
        $('#incomereport').html("");

        $.ajax({
            url : "{{ route('home.index') }}",
            type : "get",
            dataType : "json",
            data: {
                type:"chart_incomereport",
            },
        }).done(function (data) {
            $("#incomereport").css("height","180");
            $("#graph_incomereport").css("height", "210px");
            // if(value == "all"){
            //     $("#title-orderreport").html("Order Report");
            // }else{
            //     $("#title-orderreport").html("Grade Statistic in "+data[0].month_name);
            // }

            var colors = [];

            data.forEach(function(key){
                color = key.color;
                colors.push(color);
            });

            Morris.Line({
                element: 'incomereport',
                data: data,
                xkey: 'report_period',
                ykeys: ['report_count'],
                labels: ['Total Income'],
                lineColors:['#f96f59'],
                xLabelAngle: 60,
                hideHover: true,
                hoverCallback: function(index, options, content, row) {
                    return '<div style="background:rgba(255,255,255,1); border-radius:4px; margin: 0 30% 0 30%">'+content+'</div>';
                },
                resize: true,

            });
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        })
    }

    function filter(value=null){
        // Get Chart
        getGraphBestTeacher(value);
        getGraphMostSubject(value);
        getGradeStatistic(value);
        getGraphOrderType(value);
        getGraphPackage(value);
    }

    function setSwitch(){
        var switch1 = document.querySelector('#switch1');
        var switch2 = document.querySelector('#switch2');
        new Switchery(switch1, {size: 'small',color: '#008374'});
        new Switchery(switch2, {size: 'small',color: '#008374'});
    }

    function copyText(order_id,text) {
        /* Copy the text inside the text field */
        navigator.clipboard.writeText(text);

        $('#copytextbtn'+order_id).popover({content: "Text copied to clipboard",animation: true});

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
</script>
@endsection