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
Your Invitation
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">
                <h4 class="m-t-0 header-title">Your Invitations List</h4>
                <p class="text-muted font-14 m-b-30">
                    @if (array_search("IVPRC",$page))
                        <a href="{{ route('invitation.create') }}" class="btn btn-success btn-rounded w-md waves-effect waves-light m-b-5">Create New</a>
                    @endif
                </p>

                <table id="responsive-datatable" class="table table-bordered table-bordered dt-responsive wrap" cellspacing="0" width="100%">
                    <thead>
                        <th>No</th>
                        <th>Invitation ID</th>
                        <th>Groom's Photo</th>
                        <th>Groom's Name</th>
                        <th>Groom's Parent</th>
                        <th>Bride's Photo</th>
                        <th>Bride's Name</th>
                        <th>Bride's Parent</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach($invitations as $inv)
                        <tr>
                            <td>{{$i++}}</td>
                            <td><a href="{{ route('getInvitation', ['invitation_id'=> $inv->invitation_id, 'receiver' => "Contoh+Penerima_Lokasi"]) }}" target="_blank" class="btn btn-purple btn-rounded waves-effect waves-light w-xs m-b-5"><span class="mdi mdi-airplay"> {{$inv->invitation_id}}</span></a></td>
                            <td>
                                <a href="{{ asset('multimedia/'.$inv->invitation_id.'/'.$inv->groom_photo) }}" class="image-popup" title="{{$inv->groom_photo}}">
                                    <img src="{{ asset('multimedia/'.$inv->invitation_id.'/'.$inv->groom_photo) }}"  alt="user-img" title="{{ $inv->groom_photo }}" class="img-thumbnail img-responsive photo">
                                </a>
                            </td>
                            <td>{{$inv->groom_name}} <strong>({{$inv->groom_nickname}})</strong></td>
                            <td>
                                <li>{{$inv->groom_father}}</li>
                                <li>{{$inv->groom_mother}}</li>
                            </td>
                            <td>
                                <a href="{{ asset('multimedia/'.$inv->invitation_id.'/'.$inv->bride_photo) }}" class="image-popup" title="{{$inv->bride_photo}}">
                                    <img src="{{ asset('multimedia/'.$inv->invitation_id.'/'.$inv->bride_photo) }}"  alt="user-img" title="{{ $inv->bride_photo }}" class="img-thumbnail img-responsive photo">
                                </a>
                            </td>
                            <td>{{$inv->bride_name}} <strong>({{$inv->bride_nickname}})</strong></td>
                            <td>
                                <li>{{$inv->bride_father}}</li>
                                <li>{{$inv->bride_mother}}</li>
                            </td>
                            <td>
                                @if (array_search("IVPRU",$page))
                                    <a href="{{route('invitation.edit',['id'=>$inv->id])}}" class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5">Edit</a>
                                @endif
                                @if (array_search("IVPRD",$page))
                                    <a href="javascript:;" class="btn btn-danger btn-rounded waves-effect waves-light w-md m-b-5" onclick="deleteInvitation({{$inv->id}})">Delete</a>
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
                { "width": "5%", "targets": [0,1] },
                { "width": "15%", "targets": [3,4,6,7] },
                { "width": "10%", "targets": [2,5]} ,
            ]
        });

        $('.image-popup').magnificPopup({
            type: 'image',
        });

    });

    function deleteInvitation(id){
        var token = $("meta[name='csrf-token']").attr("content");

        swal({
            title: "Apakah anda yakin ingin menghapus data ini?",
            text: "Yang telah dihapus tidak dapat dikembalikan",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak, Batalkan!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            buttonsStyling: false
        }).then(function () {
            $.ajax({
                url: "invitation/"+id,
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
