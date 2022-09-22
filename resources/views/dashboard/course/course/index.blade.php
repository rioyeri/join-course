@extends('dashboard.layout.main')

@section('css')
    {{-- <link href="{{ asset('dashboard/lib/advanced-datatable/css/demo_page.css') }}" rel="stylesheet" />
    <link href="{{ asset('dashboard/lib/advanced-datatable/css/demo_table.css') }}" rel="stylesheet" /> --}}
    {{-- <link rel="stylesheet" href="{{ asset('dashboard/lib/advanced-datatable/css/DT_bootstrap.css') }}" /> --}}
    <!-- DataTables -->
    <link href="{{ asset('dashboard/additionalplugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/additionalplugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('dashboard/additionalplugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Multi Item Selection examples -->
    <link href="{{ asset('dashboard/additionalplugins/datatables/select.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Select2 -->
    <link href="{{ asset('dashboard/additionalplugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        #loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 50px;
            height: 50px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            margin-left:10px;
            margin-right:10px;
            margin-top:10px;
        }
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endsection

@section('title')
    Course <i class="fa fa-angle-right"></i> Course
@endsection

@section('content')
    <!-- page start-->
    <div class="content-panel">
        <button class="btn btn-success btn-round m-20" data-toggle="modal" data-target="#myModal" onclick="create_data()"><i class="glyphicon glyphicon-plus"></i> Add</button>
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
                    {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Dismiss</button>
                        <button type="button" class="btn btn-primary">Submit</button>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="adv-table">
            <table cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-course">
                <thead>
                    <th>No</th>
                    <th>Subject</th>
                    <th>Grade</th>
                    <th>Topic</th>
                    <th>Description</th>
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
@endsection

@section('script-js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#table-course').DataTable({
            "processing" : true,
            "serverSide" : true,
            "order": [[ 3, "asc" ]],
            "ajax" : {
                "url" : "{{ route('course.index') }}",
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                }
            },"columns" : [{data : "no", name : "no", searchable : false},
                    {data : "name", name : "name"},
                    {data : "grade", name : "grade"},
                    {data : "topic", name : "topic"},
                    {data : "description", name : "description", orderable : false},
                    {data : "options", name : "options", orderable : false, searchable : false}
            ],
            // "columnDefs" : [
            //     {
            //         // targets: '_all',
            //         // type: 'natural'
            //         render: function (data, type, full, meta) {
            //             return "<div class='text-wrap width-200'>" + data + "</div>";
            //         },
            //         targets: [1,3],
            //     },
            //     {
            //         render: function (data, type, full, meta) {
            //             return "<div class='text-right'>" + data + "</div>";
            //         },
            //         targets: [4,5,6,7,8],
            //     }
            // ],
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
            url : "{{route('course.create')}}",
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
            url : "/course/"+id+"/edit",
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            console.log(data)
            $('#view-form').html(data);
            // $('#modalform').modal("show");
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }
</script>
@endsection