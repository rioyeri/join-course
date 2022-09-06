@extends('layout.main')

@section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- form Uploads -->
    <link href="{{ asset('assets/plugins/fileuploads/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('judul')
Create New Event
@endsection

@section('content')
    @isset($event)
        <form class="form-horizontal" role="form" action="{{ route('event.update',['id' => $event->id]) }}" enctype="multipart/form-data" method="POST">
            {{ method_field('PUT') }}
            <input type="hidden" name="inv_id" id="inv_id" value="{{ $event->invitation_id }}">
    @else
        <form class="form-horizontal" role="form" action="{{ route('event.store') }}" enctype="multipart/form-data" method="POST">
    @endisset

    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">Events Information</h4>
                <p class="text-muted m-b-30 font-14">
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="p-20">
                            <label class="col-4 col-form-label"><span class="text-danger">* required field</span></label>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Invitation ID <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <select class="form-control select2" parsley-trigger="change" name="invitation_id" id="invitation_id" onchange="getDetailRow(this.value)" @if(isset($event)) disabled @endif>
                                        <option value="#" disabled selected>Pilih Invitation ID</option>
                                        @foreach ($invitation as $inv)
                                            @isset($event->invitation_id)
                                                @if ($event->invitation_id == $inv->invitation_id)
                                                    <option value="{{ $inv->invitation_id }}" selected>{{ $inv->invitation_id }}</option>
                                                @else
                                                    <option value="{{ $inv->invitation_id }}">{{ $inv->invitation_id }}</option>
                                                @endif                                            
                                            @else
                                                <option value="{{ $inv->invitation_id }}">{{ $inv->invitation_id }}</option>
                                            @endisset
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Event's Title<span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="title" id="title" value="@isset($event->title){{$event->title}}@endisset" placeholder="Event Title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Event's Description<span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="description" id="description" value="@isset($event->description){{$event->description}}@endisset" placeholder="Event Description">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">Data Events</h4>
                <div class="card-box table-responsive">
                    <h4 class="m-t-0 header-title">Event Detail</h4>
                    @if (array_search("IVEVC",$page))
                    <p class="text-muted font-14 m-b-30">
                        <a href="javascript:;" class="btn btn-success btn-rounded w-md waves-effect waves-light m-b-5" onclick="detailEvent('create')">Tambah</a>
                    </p>
                    @endif

                    <table id="responsive-datatable" class="table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <th>No</th>
                            <th>Event's Name</th>
                            <th>Event's Date</th>
                            <th>Event's Start Time</th>
                            <th>Event's End Time</th>
                            <th>Event's Time Zone</th>
                            <th>Event's Location</th>
                            <th>Event's Location Address</th>
                            <th>Event's Location Link</th>
                            <th>Event's Streaming</th>
                            <th>Event's Streaming Link</th>
                            <th>Action</th>
                        </thead>

                        <tbody id="tbody-events">
                        </tbody>
                    </table>
                </div>

                {{-- Form Create/Update --}}
                <div id="form-eventdetail"></div>
            </div>
        </div>
    </div>

    <div class="form-group text-right m-b-0">
        <a href="{{ route("event.index") }}" class="btn btn-warning waves-effect waves-light">Kembali</a>
        <button class="btn btn-success waves-effect waves-light" type="submit">Simpan</button>
    </div>
</form>
@endsection

@section('js')
<!-- Plugin -->
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- file uploads js -->
<script src="{{ asset('assets/plugins/fileuploads/js/dropify.min.js') }}"></script>
<!-- Validation js (Parsleyjs) -->
<script type="text/javascript" src="{{ asset('assets/plugins/parsleyjs/dist/parsley.min.js') }}"></script>
<!-- Max Length -->
<script src="{{ asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
@endsection

@section('script-js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('form').parsley();
            var inv_id = $('#inv_id').val();
            if(inv_id != undefined){
                getDetailRow(inv_id);
            }
        });
        // Select2
        $(".select2").select2();

        $('textarea#textarea').maxlength({
            alwaysShow: true,
            warningClass: "badge badge-success",
            limitReachedClass: "badge badge-danger"
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

        $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop a file here or click',
                'replace': 'Drag and drop or click to replace',
                'remove': 'Remove',
                'error': 'Ooops, something wrong appended.'
            },
            error: {
                'fileSize': 'The file size is too big (1M max).'
            }
        });

        function getDetailRow(inv_id){
            $("#tbody-events tr").remove();
            $.ajax({
                url : "/event/"+inv_id,
                type : "get",
                dataType: 'json',
            }).done(function (data) {
                var row = data.length;
                if(row != 0){
                    for(i=0; i<row; i++){
                        var duplicate = 0
                        $("#tbody-events tr").each(function(){
                            var value = $(this).find('td:eq(1)').text();
                            if(value == data[i].value){
                                duplicate++;
                            }
                        });
                        if(duplicate == 0){
                            $('#tbody-events').append(data[i].append);
                        }
                    }
                }
            }).fail(function (msg) {
                alert('Gagal menampilkan data, silahkan refresh halaman.');
            });
        }

        function detailEvent(jenis,id=null){
            if(jenis == "create"){
                $.ajax({
                    url : "{{route('event.create')}}",
                    type : "get",
                    dataType: 'json',
                }).done(function (data) {
                    $('#form-eventdetail').html(data);
                    element = document.getElementById("form-eventdetail");
                    element.scrollIntoView();
                }).fail(function (msg) {
                    alert('Gagal menampilkan data, silahkan refresh halaman.');
                });
            }else if(jenis == "edit"){
                name = $('#event_name'+id).val();
                date = $('#event_date'+id).val();
                time_start = $('#event_time_start'+id).val();
                time_end = $('#event_time_end'+id).val();
                time_zone = $('#event_time_zone'+id).val();
                venue = $('#event_location'+id).val();
                address = $('#event_location_address'+id).val();
                location_url = $('#event_location_url'+id).val();
                streaming_channel = $('#event_streaming_channel'+id).val();
                streaming_link = $('#event_streaming_link'+id).val();
                $.ajax({
                    url : "{{route('event.edit',['id'=>1])}}",
                    type : "get",
                    dataType: 'json',
                }).done(function (data) {
                    $('#form-eventdetail').html(data);
                    $('#name').val(name);
                    $('#date').val(date);
                    $('#time_start').val(time_start);
                    $('#time_end').val(time_end);
                    $('#time_zone').val(time_zone);
                    $('#location').val(venue);
                    $('#address').val(address);
                    $('#location_url').val(location_url);
                    $('#streaming_channel').val(streaming_channel);
                    $('#streaming_link').val(streaming_link);
                    $('#event_id').val(id);
                    element = document.getElementById("form-eventdetail");
                    element.scrollIntoView();
                }).fail(function (msg) {
                    alert('Gagal menampilkan data, silahkan refresh halaman.');
                });
            }else if(jenis == "delete"){
                swal({
                    title: "Apakah anda yakin ingin menghapus ini?",
                    text: "Tidak ada transaksi yang terdaftar menggunakan ini",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Tidak, Batalkan!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger m-l-10',
                    buttonsStyling: false,
                }).then(function () {
                    $.ajax({
                        url : "{{route('event.destroy',['id'=>1])}}",
                        type : "DELETE",
                        dataType: 'json',
                        data:{
                            'id':id,
                        },
                    }).done(function (data) {
                        swal(
                            'Terhapus!',
                            'Data berhasil dihapus.',
                            'success'
                        )
                        location.reload();
                    }).fail(function (msg) {
                        swal(
                            'Gagal',
                            'Data gagal terhapus!',
                            'error'
                        )
                    });

                }, function (dismiss) {
                    // dismiss can be 'cancel', 'overlay',
                    // 'close', and 'timer'
                    if (dismiss === 'cancel') {
                        console.log("eh ga kehapus");
                        swal(
                            'Dibatalkan',
                            'Penghapusan dibatalkan. Data masih tersimpan dengan aman :)',
                            'error'
                        )
                    }
                })
            }
        }
    </script>
@endsection
