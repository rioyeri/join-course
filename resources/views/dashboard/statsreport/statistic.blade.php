@extends('dashboard.layout.main')

@section('title')
Statistics
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
        setSwitch();

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
    });

    function getGraphBestTeacher(value){
        // Refresh Chart
        $('#bestTeacher').html("");

        $.ajax({
            url : "api/bestteacher/",
            type : "get",
            dataType: 'json',
            data: {
                sort:value,
            },
        }).done(function (data) {
            $("#bestTeacher").css("height","180");
            $("#graph_bestteacher").css("height", "210px");

            if(value == "all"){
                $("#title-bestteacher").html("Teacher Stats");
            }else{
                $("#title-bestteacher").html("Teacher of the "+data.data[0].month_name);
            }

            var count = data.data.length;
            var datas = [];
            var colors = [];

            data.data.forEach(function(key){
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
            url : "api/mostsubject",
            type : "get",
            dataType: 'json',
            data: {
                sort:value,
            },
        }).done(function (data) {
            $("#mostsubject").css("height","180");
            $("#graph_mostsubject").css("height", "210px");

            if(value == "all"){
                $("#title-mostsubject").html("Most Subject Stats");
            }else{
                $("#title-mostsubject").html("Most Subject in "+data.data[0].month_name);
            }

            var count = data.data.length;
            var datas = [];
            var colors = [];

            data.data.forEach(function(key){
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
            url : "api/gradestats",
            type : "get",
            dataType : "json",
            data: {
                sort:value,
            },
        }).done(function (data) {
            $("#grade").css("height","180");
            $("#graph_grade").css("height", "210px");
            if(value == "all"){
                $("#title-grade").html("Grade Statistic");
            }else{
                $("#title-grade").html("Grade Statistic in "+data.data[0].month_name);
            }

            var colors = [];

            data.data.forEach(function(key){
                color = key.color;
                colors.push(color);
            });

            Morris.Bar({
                element: 'grade',
                data: data.data,
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
            url : "api/ordertypestats",
            type : "get",
            dataType: 'json',
            data: {
                sort:value,
            },
        }).done(function (data) {
            $("#ordertype").css("height","180");
            $("#graph_ordertype").css("height", "210px");

            if(value == "all"){
                $("#title-ordertype").html("Type of Order Stats");
            }else{
                $("#title-ordertype").html("Type of Order Stats in "+data.data[0].month_name);
            }

            var count = data.data.length;
            var datas = [];
            var colors = [];

            data.data.forEach(function(key){
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
            url : "api/packagestats",
            type : "get",
            dataType: 'json',
            data: {
                sort:value,
            },
        }).done(function (data) {
            $("#package").css("height","180");
            $("#graph_package").css("height", "210px");

            if(value == "all"){
                $("#title-package").html("Package Stats");
            }else{
                $("#title-package").html("Package Stats in "+data.data[0].month_name);
            }

            var count = data.data.length;
            var datas = [];
            var colors = [];

            data.data.forEach(function(key){
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
        new Switchery(switch1, {size: 'small',color: 'var(--color-primary)'});
    }
</script>
@endsection