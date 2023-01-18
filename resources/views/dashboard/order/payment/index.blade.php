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
    {{-- Date Picker --}}
    <link href="{{ asset('dashboard/additionalplugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- Sweet Alert css -->
    <link href="{{ asset('dashboard/additionalplugins/sweet-alert/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Magnific Pop-up-->
    <link rel="stylesheet" href="{{ asset('dashboard/additionalplugins/magnific-popup/dist/magnific-popup.css') }}"/>
    <!-- File Upload-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/lib/bootstrap-fileupload/bootstrap-fileupload.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        #loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            /* border-top: 16px solid #3498db; */
            border-top: 16px solid var(--color-secondary);
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

@section('title')
    Order Payment
@endsection

@section('content')
    <!-- page start-->
    <div class="content-panel">
        @if(array_search("ORPYC", $submoduls))
            <button class="btn btn-theme btn-round m-15" data-toggle="modal" data-target="#myModal" onclick="create_data()"><i class="glyphicon glyphicon-plus"></i> Add</button>
        @endif
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
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
                    <a data-toggle="tab" href="#notconfirm"><span style="background: #f0ad4e; border-radius: 3px; margin-left: 20px;padding: 0 10px 0 10px; color:white"">Payment Not Yet Confirmed</span></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#confirm"><span style="background: #5cb85c; border-radius: 3px; margin-left: 20px;padding: 0 10px 0 10px; color:white"">Payment Completed</span></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#decline"><span style="background: #d9534f; border-radius: 3px; margin-left: 20px;padding: 0 10px 0 10px; color:white"">Payment Declined</span></a>
                </li>
            </ul>
        </div>
        <!-- /panel-heading -->
        <div class="panel-body">
            <div class="tab-content">
                <div id="notconfirm" class="tab-pane active">
                    <div class="row">
                        {{-- <div class="adv-table"> --}}
                            <form class="form-horizontal" role="form" action="{{ route('exportPayment') }}" enctype="multipart/form-data" method="POST">
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
                                        <a class="btn btn-theme" onclick="getDatas('not-confirm')"><i class="fa fa-filter"></i> Filter</a>
                                        <a class="btn" onclick="resetParamProcess('not-confirm')"><i class="fa fa-rotate-left"></i> Clear</a>
                                    </div>
                                    @if(array_search("ORPYX", $submoduls))
                                    <div class="col-6 text-right">
                                        <button class="btn btn-success btn-round m-b-5">
                                            <i class="fa fa-file-text-o"></i> Export to Excel
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </form>

                            <table width="100%" class="table table-bordered datatable dt-responsive wrap" id="table-payment-notconfirm">
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
                                    <th>Options</th>
                                </thead>
                                <tbody id="table-body">
                                </tbody>
                            </table>
                        {{-- </div> --}}
                    </div>
                </div>
                <div id="confirm" class="tab-pane">
                    <div class="row">
                        {{-- <div class="adv-table"> --}}
                            <form class="form-horizontal" role="form" action="{{ route('exportPayment') }}" enctype="multipart/form-data" method="POST">
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
                                        <a class="btn btn-theme" onclick="getDatas('confirm')"><i class="fa fa-filter"></i> Filter</a>
                                        <a class="btn" onclick="resetParamProcess('confirm')"><i class="fa fa-rotate-left"></i> Clear</a>
                                    </div>
                                    @if(array_search("ORPYX", $submoduls))
                                    <div class="col-6 text-right">
                                        <button class="btn btn-success btn-round m-b-5">
                                            <i class="fa fa-file-text-o"></i> Export to Excel
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </form>
                            <table width="100%" class="table table-bordered datatable wrap" id="table-payment-confirm">
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
                                    <th>Options</th>
                                </thead>
                                <tbody id="table-body2">
                                </tbody>
                            </table>
                        {{-- </div> --}}
                    </div>
                </div>
                <div id="decline" class="tab-pane">
                    <div class="row">
                        {{-- <div class="adv-table"> --}}
                            <form class="form-horizontal" role="form" action="{{ route('exportPayment') }}" enctype="multipart/form-data" method="POST">
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
                                        <a class="btn btn-theme" onclick="getDatas('decline')"><i class="fa fa-filter"></i> Filter</a>
                                        <a class="btn" onclick="resetParamProcess('decline')"><i class="fa fa-rotate-left"></i> Clear</a>
                                    </div>
                                    @if(array_search("ORPYX", $submoduls))
                                    <div class="col-6 text-right">
                                        <button class="btn btn-success btn-round m-b-5">
                                            <i class="fa fa-file-text-o"></i> Export to Excel
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </form>
                            <table width="100%" class="table table-bordered datatable wrap" id="table-payment-decline">
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
                                    <th>Options</th>
                                </thead>
                                <tbody id="table-body3">
                                </tbody>
                            </table>
                        {{-- </div> --}}
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
    <!-- File Upload -->
    <script type="text/javascript" src="{{ asset('dashboard/lib/bootstrap-fileupload/bootstrap-fileupload.js') }}"></script>
    <!-- Magnific popup -->
    <script type="text/javascript" src="{{ asset('dashboard/additionalplugins/magnific-popup/dist/jquery.magnific-popup.min.js') }}"></script>
@endsection

@section('script-js')
<script type="text/javascript">
    $(document).ready(function() {
        getDatas();
        
        $('.datepicker').datepicker({
            todayHighlight: true,
            autoclose: true,
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
    });
    
    function getPaymentNotConfirm(start=null, end=null){
        $('#table-payment-notconfirm').DataTable().clear().destroy();
        $('#table-payment-notconfirm').DataTable({
            "responsive": true,
            "processing" : true,
            "serverSide" : true,
            "order": [[ 8, "asc" ]],
            "ajax" : {
                "url" : "{{ route('orderpayment.index') }}",
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                    "start_date" : start,
                    "end_date" : end,
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
                {data : "options", name : "options", orderable : false, searchable : false,}
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
    }

    function getPaymentConfirm(start=null, end=null){
        $('#table-payment-confirm').DataTable().clear().destroy();
        $('#table-payment-confirm').DataTable({
            "responsive": true,
            "processing" : true,
            "serverSide" : true,
            "order": [[ 8, "asc" ]],
            "ajax" : {
                "url" : "{{ route('orderpayment.index') }}",
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                    "type" : "confirm",
                    "start_date" : start,
                    "end_date" : end,
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
                {data : "options", name : "options", orderable : false, searchable : false,}
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
    }

    function getPaymentDecline(start=null, end=null){
        $('#table-payment-decline').DataTable().clear().destroy();
        $('#table-payment-decline').DataTable({
            "responsive": true,
            "processing" : true,
            "serverSide" : true,
            "order": [[ 8, "asc" ]],
            "ajax" : {
                "url" : "{{ route('orderpayment.index') }}",
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                    "type" : "decline",
                    "start_date" : start,
                    "end_date" : end,
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
                {data : "options", name : "options", orderable : false, searchable : false,}
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
    }

    function getDatas(type=null){
        if(type == "not-confirm"){
            start_date = $('#notconfirm_start_date').val();
            end_date = $('#notconfirm_end_date').val();
            getPaymentNotConfirm(start_date, end_date);
        }else if(type == "confirm"){
            start_date = $('#confirm_start_date').val();
            end_date = $('#confirm_end_date').val();
            getPaymentConfirm(start_date, end_date);
        }else if(type == "decline"){
            start_date = $('#decline_start_date').val();
            end_date = $('#decline_end_date').val();
            getPaymentDecline(start_date, end_date);
        }else{
            getPaymentNotConfirm();
            getPaymentConfirm();
            getPaymentDecline();
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
        }

        getDatas(type);
    }

    function moveToBox(id){
        $('#'+id).data("datepicker").show();
    }

    function create_data(){
        $.ajax({
            url : "{{route('orderpayment.create')}}",
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function edit_data(id){
        $.ajax({
            url : "/orderpayment/"+id+"/edit",
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
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
                url: "orderpayment/"+id,
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
                console.log(data);
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
</script>

@if(session('order_id') && session('order_token'))
<script>
    $(document).ready(function() {
        create_data();
        $(".modal").modal("show");
    });
</script>
@endif
@endsection