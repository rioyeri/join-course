@extends('layout.main')

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Multi Item Selection examples -->
    <link href="{{ asset('assets/plugins/datatables/select.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!--venobox lightbox-->
    <link rel="stylesheet" href="{{ asset('assets/plugins/magnific-popup/dist/magnific-popup.css') }}"/>

    {{-- Fingerprint --}}
    <link href="{{ asset('assets/fingerprint/ajaxmask.css') }}" rel="stylesheet">
    <!-- Sweet Alert css -->
    <link href="{{ asset('assets/plugins/sweet-alert/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
    img.photo{
        display:block; width:50%; height:auto;
    }
    </style>
@endsection

@section('judul')
Index Complement Things
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">
                <h4 class="m-t-0 header-title">Index Complement Things</h4>
                <p class="text-muted font-14 m-b-30">
                    @if (array_search("IVCMC",$page))
                        <a href="{{ route('complement.create') }}" class="btn btn-success btn-rounded w-md waves-effect waves-light m-b-5">Create New</a>
                    @endif
                </p>

                <table id="responsive-datatable" class="table table-bordered table-bordered dt-responsive wrap" cellspacing="0" width="100%">
                    <thead>
                        <th>No</th>
                        <th>Invitation ID</th>
                        <th>Icon</th>
                        <th>Song</th>
                        <th>Banner Image</th>
                        <th>Creator</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach($complements as $comp)
                        <tr>
                            <td>{{$i++}}</td>
                            <td><a href="{{ route('getInvitation', ['invitation_id'=> $comp->invitation_id, 'receiver' => "Contoh+Penerima_Lokasi"]) }}" target="_blank">{{ $comp->invitation_id }}</a></td>
                            <td>
                                <a href="{{ asset('multimedia/'.$comp->invitation_id.'/'.$comp->icon) }}" class="image-popup" title="{{$comp->icon}}">
                                    <img src="{{ asset('multimedia/'.$comp->invitation_id.'/'.$comp->icon) }}"  alt="user-img" title="{{ $comp->icon }}" class="img-thumbnail img-responsive photo">
                                </a>
                            </td>
                            <td>{{$comp->song}}</td>
                            <td>
                                <a href="{{ asset('multimedia/'.$comp->invitation_id.'/'.$comp->banner) }}" class="image-popup" title="{{$comp->banner}}">
                                    <img src="{{ asset('multimedia/'.$comp->invitation_id.'/'.$comp->banner) }}"  alt="user-img" title="{{ $comp->banner }}" class="img-thumbnail img-responsive photo">
                                </a>
                            </td>
                            <td>{{$comp->creator_name()->first()->name}}</td>
                            <td>
                                @if (array_search("IVCMU",$page))
                                    <a href="{{route('complement.edit',['id'=>$comp->id])}}" class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5">Edit</a>
                                @endif
                                @if (array_search("IVCMD",$page))
                                    <a href="javascript:;" class="btn btn-danger btn-rounded waves-effect waves-light w-md m-b-5" onclick="deleteComplement({{$comp->id}})">Delete</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end row -->
@endsection

@section('js')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

    <!-- Modal-Effect -->
    <script src="{{ asset('assets/plugins/custombox/dist/custombox.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custombox/dist/legacy.min.js') }}"></script>

    <!-- Sweet Alert Js  -->
    <script src="{{ asset('assets/plugins/sweet-alert/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/pages/jquery.sweet-alert.init.js') }}"></script>

    <!-- Magnific popup -->
    <script type="text/javascript" src="{{ asset('assets/plugins/magnific-popup/dist/jquery.magnific-popup.min.js') }}"></script>
@endsection

@section('script-js')

<script type="text/javascript">

    $(document).ready(function () {
        // Responsive Datatable
        $('#responsive-datatable').DataTable({
            "columnDefs": [
                { "width": "5%", "targets": 0 },
                { "width": "30%", "targets": 2 },
                { "width": "20%", "targets": [1,4] },
                { "width": "10%", "targets": [3,5] },
            ]
        });

        $('.image-popup').magnificPopup({
            type: 'image',
        });

    });

    function deleteComplement(id){
        var token = $("meta[name='csrf-token']").attr("content");

        swal({
            title: "Apakah anda yakin ingin menghapus ini?",
            text: "Gambar yang telah dihapus tidak dapat dikembalikan",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak, Batalkan!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            buttonsStyling: false
        }).then(function () {
            $.ajax({
                url: "complement/"+id,
                type: 'DELETE',
                data: {
                    "id": id,
                    "_token": token,
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

    function checkBeforeDelete(id){
        var result="";
        $.ajax({
            url: "{{ route('checkBeforeDelete') }}",
            type: "get",
            dataType: 'json',
            async: false,
            data: {
                "id": id,
                "type": "deleteuser",
            },success:function(data){
                 result = data
            }
        })

        return result;
    }
</script>
@endsection
