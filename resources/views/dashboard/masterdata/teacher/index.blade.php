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

@section('title')
    Teachers
@endsection

@section('submodul')
    active
@endsection

@section('content')
    <!-- page start-->
    <div class="content-panel">
        @if(array_search("MDTCC", $submoduls))
            <button class="btn btn-theme btn-round m-20" data-toggle="modal" data-target="#myModal" onclick="create_data()"><i class="glyphicon glyphicon-plus"></i> Add</button>
        @endif
        <!-- Modal -->
        <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
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
            <table width="100%" class="table table-bordered datatable dt-responsive wrap" id="table-teacher">
                <thead>
                    <th style="width:5%">No</th>
                    <th>Teacher's Name</th>
                    <th>Title</th>
                    <th>Teacher's Profile</th>
                    <th>Courses</th>
                    {{-- <th>Prices</th> --}}
                    <th>Schedules</th>
                    <th>Reviews</th>
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
        $('#table-teacher').DataTable({
            "processing" : true,
            "serverSide" : true,
            "order": [[ 1, "desc" ]],
            "ajax" : {
                "url" : "{{ route('teacher.index') }}",
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                }
            },"columns" : [{data : "no", name : "no", searchable : false},
                    {data : "teacher_name", name : "teacher_name"},
                    {data : "title", name : "title"},
                    {data : "teacher_profile", name : "teacher_profile", orderable : false, searchable: false},
                    {data : "courses", name : "courses", orderable : false, searchable : false},
                    // {data : "prices", name : "prices", orderable : false},
                    {data : "schedules", name : "schedules", orderable : false, searchable : false},
                    {data : "reviews", name : "reviews", orderable : false, searchable : false},
                    {data : "options", name : "options", orderable : false, searchable : false,}
            ],
            "columnDefs" : [
                {
                    targets: [0],
                    width: "3%",
                },
                {
                    targets: [1,2],
                    width: "15%",
                },
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-center'>" + data + "</div>";
                    },
                    targets: [3,4,5,6],
                    width: "15%",
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

    function create_data(){
        $.ajax({
            url : "{{route('teacher.create')}}",
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    // function edit_data(id){
    //     console.log(id)
    //     $.ajax({
    //         url : "/teacher/"+id+"/edit",
    //         type : "get",
    //         dataType: 'json',
    //     }).done(function (data) {
    //         $('#view-form').html(data);
    //     }).fail(function (msg) {
    //         alert('Gagal menampilkan data, silahkan refresh halaman.');
    //     });
    // }

    function change_status(id){
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url : "/teacher/"+id+"/changestatus",
            type : "post",
            dataType: 'json',
            data: {
                "_token":token,
            }
        }).done(function (data) {
            location.reload();
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function delete_data(id){
        var token = $("meta[name='csrf-token']").attr("content");

        console.log(token);

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
                url: "teacher/"+id,
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

    function view_profile(id){
        $.ajax({
            url : "/teacher/"+id+"/edit",
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function view_subject(id){
        $.ajax({
            url : "/teacher/"+id+"/course",
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function view_price(id){
        $.ajax({
            url : "/teacher/"+id+"/price",
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function view_schedules(id){
        $.ajax({
            url : "teacherschedule/"+id+"/edit",
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function view_reviews(id){
        $.ajax({
            url : "orderreview/"+id+"/edit",
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function addToTable(){
        var style_display = document.getElementById('table_packages').style.display;    
        if(style_display == 'none'){
            document.getElementById('table_packages').style.display = 'block';
        }
        var package_id = $('#package').val();
        var price = $('#price').val();
        select = document.getElementById("package");
        var package_name = select.options[select.selectedIndex].text;
        count = $('#table-package tbody tr.trow').length+1;

        duplicate = 0;
        $("#table-body-package tr").each(function(){
            var value_count = $(this).find('td:eq(0)').text();
            var value_package = $('#package_id'+value_count).val();
            if(value_package == package_id){
                duplicate++;
            }
        });

        if(duplicate == 0){
            var append = '<tr style="width:100%" id="trow'+count+'" class="trow"><td>'+count+'</td><td>'+package_name+'</td><input type="hidden" name="package_id[]" id="package_id'+count+'" value="'+package_id+'"><td class="text-right">Rp '+number_format(price,2,",",".")+'</td><input type="hidden" name="package_price[]" id="package_price'+count+'" value="'+price+'"><td class="text-center"><a href="javascript:;" type="button" class="btn btn-danger btn-trans waves-effect w-xs waves-danger m-b-5" onclick="deleteItemPackage('+count+')" >Delete</a></td></tr>';
            $('#table-body-package').append(append);
        }else{
            $("#table-body-package tr").each(function(){
                var value_count = $(this).find('td:eq(0)').text();
                var value_package = $('#package_id'+value_count).val();
                if(value_package == package_id){
                    $(this).find('td:eq(2)').text('Rp '+number_format(price,2,",","."));
                    $('#package_price'+value_count).val(price);
                    console.log($('#package_price'+value_count).val());
                }
            });
        }
        resetFormPackage();
    }

    function resetFormPackage(){
        $('#package').val('#').change();
        $('#price').val("");
    }

    function deleteItemPackage(id){
        $('#trow'+id).remove();
    }
</script>
@endsection