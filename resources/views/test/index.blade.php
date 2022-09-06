@extends('layout.main')

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Multi Item Selection examples -->
    <link href="{{ asset('assets/plugins/datatables/select.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="row">
                        <div class="col-12">
                            @csrf
                            <input type="text" class="form-control" name="search" id="search" value="{{ $keyword }}" placeholder="Search..">
                        </div>
                    </div>
                    <section class="datas">
                        @include('test.list')
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
@endsection

@section('script-js')
<script>
    $(document).ready(function() {
        $(document).on('click', '.pagination a', function (e) {
            $('.datas').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="../images/loading.gif" />');
            var url = $(this).attr('href'); 
            getDatas($(this).attr('href').split('page=')[1]);
            e.preventDefault();
        });
    });

    //setup before functions
    var typingTimer;                //timer identifier
    var doneTypingInterval = 1000;  //time in ms, 5 second for example
    var $input = $('#search');

    //on keyup, start the countdown
    $input.on('keyup', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    //on keydown, clear the countdown 
    $input.on('keydown', function () {
        clearTimeout(typingTimer);
        // typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    //user is "finished typing," do something
    function doneTyping () {
        var search = $('#search').val();
        console.log(search)
        $.ajax({
            url : 'testuser',
            type : "get",
            dataType: 'json',
            data:{
                search: search
            },
        }).done(function (data) {
            $('.datas').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function getDatas(page) {
        $.ajax({
            url : '?page=' + page,
            type : "get",
            dataType: 'json',
            data:{
                search: $('#search').val()
            },
        }).done(function (data) {
            $('.datas').html(data);
            location.hash = page;
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }
</script>
@endsection