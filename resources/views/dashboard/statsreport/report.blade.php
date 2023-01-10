@extends('dashboard.layout.main')

@section('title')
Reports
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

        getOrderReport();
        getIncomeReport();
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

        switch2.onchange = function() {
            if(switch2.checked == true){
                document.getElementById("report-container").style.display = 'block';
            }else{
                document.getElementById("report-container").style.display = 'none';
            }
        };
    });

    function getOrderReport(){
        // Refresh Chart
        $('#orderreport').html("");

        $.ajax({
            url : "api/orderreport",
            type : "get",
            dataType : "json",
        }).done(function (data) {
            $("#orderreport").css("height","180");
            $("#graph_orderreport").css("height", "210px");

            var colors = [];

            data.data.forEach(function(key){
                color = key.color;
                colors.push(color);
            });

            Morris.Line({
                element: 'orderreport',
                data: data.data,
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
            url : "api/incomereport",
            type : "get",
            dataType : "json",
        }).done(function (data) {
            $("#incomereport").css("height","180");
            $("#graph_incomereport").css("height", "210px");

            var colors = [];

            data.data.forEach(function(key){
                color = key.color;
                colors.push(color);
            });

            Morris.Line({
                element: 'incomereport',
                data: data.data,
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

    function setSwitch(){
        var switch2 = document.querySelector('#switch2');
        new Switchery(switch2, {size: 'small',color: 'var(--color-primary)'});
    }
</script>
@endsection