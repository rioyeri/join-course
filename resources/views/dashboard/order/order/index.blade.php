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
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
        <div class="adv-table">
            <table cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-order">
                <thead>
                    <th>No</th>
                    <th>Order ID</th>
                    <th>Student</th>
                    <th>Grade</th>
                    <th>Course</th>
                    <th>Teacher</th>
                    <th>Package</th>
                    <th>Status</th>
                    <th>Course Start</th>
                    <th>Total Bill</th>
                    <th>Bill Paid</th>
                    <th>Payment Status</th>
                    <th>Options</th>
                </thead>
                <tbody id="table-body">
                </tbody>
            </table>
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
@endsection

@section('script-js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#table-order').DataTable({
            "processing" : true,
            "serverSide" : true,
            "order": [[ 0, "desc" ]],
            "ajax" : {
                "url" : "{{ route('order.index') }}",
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                }
            },"columns" : [{data : "no", name : "no", searchable : false},
                {data : "invoice_id", name : "invoice_id"},
                {data : "student_name", name : "student_name"},
                {data : "grade_id", name : "grade_id"},
                {data : "course_name", name : "course_name"},
                {data : "teacher_name", name : "teacher_name"},
                {data : "package_name", name : "package_name"},
                {data : "status", name : "status", orderable : false},
                {data : "course_start", name : "course_start"},
                {data : "order_bill", name : "order_bill"},
                {data : "bill_paid", name : "bill_paid"},
                {data : "payment_status", name : "payment_status", orderable : false},
                {data : "options", name : "options", orderable : false, searchable : false,}
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

    function create_data(){
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
            cancelButtonText: 'Cancel Order',
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

    function finishing_order(id){
        var token = $("meta[name='csrf-token']").attr("content");

        swal({
            title: 'Confirm this Order?',
            text: "Confirmed Order will be forward to Student!",
            type: 'warning',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Finishing Order',
            cancelButtonText: 'Close',
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

    function get_package(params){
        var jenisdata = "get_package";
        $.ajax({
            url : "{{route('getData')}}",
            type : "get",
            dataType: 'json',
            data:{
                params: params,
                jenisdata: jenisdata,
            },
        }).done(function (data) {
            $('#package_id').html(data.append);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function get_bill(){
        var teacher_id = $('#teacher_id').val();
        var package_id = $('#package_id').val();

        if(teacher_id != null && course_id != null && package_id != null){
            $.ajax({
                url: "{{ route('getTeacherFee') }}",
                type: 'GET',
                data: {
                    "teacher_id": teacher_id,
                    "package_id": package_id,
                },
            }).done(function (data) {
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
</script>
@endsection