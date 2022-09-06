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
Your Gift Box
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">
                <h4 class="m-t-0 header-title">Your Gift Box List</h4>
                <p class="text-muted font-14 m-b-30">
                    @if (array_search("IVGBC",$page))
                        <a href="{{ route('giftbox.create') }}" class="btn btn-success btn-rounded w-md waves-effect waves-light m-b-5">Create New</a>
                    @endif
                </p>

                <table id="responsive-datatable" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <th width="5%">No</th>
                        <th width="5%">Invitation ID</th>
                        <th width="40%">Accounts</th>
                        <th width="5%">Action</th>
                    </thead>

                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach($giftboxs as $giftbox)
                        <tr>
                            <td>{{$i++}}</td>
                            <td><a href="{{ route('getInvitation', ['invitation_id'=> $giftbox->invitation_id, 'receiver' => "Contoh+Penerima_Lokasi"]) }}" target="_blank">{{$giftbox->invitation_id}}</a></td>
                            <td>
                                @foreach ($giftbox->accounts as $detail)
                                    <li>Account : {{$detail->account_number}} ({{ $detail->account_type }})</li>
                                    <li>Account Name : {{$detail->account_name}}</li>
                                    <br>
                                @endforeach
                            </td>
                            <td>
                                @if (array_search("IVGBU",$page))
                                    <a href="{{route('giftbox.edit',['id'=>$giftbox->invitation_id])}}" class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5">Edit</a>
                                @endif
                                @if (array_search("IVGBD",$page))
                                    <a href="javascript:;" class="btn btn-danger btn-rounded waves-effect waves-light w-md m-b-5" onclick="deleteGiftBox('{{$giftbox->invitation_id}}')">Delete</a>
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
                { "width": "10%", "targets": 3 },
            ]
        });

        $('.image-popup').magnificPopup({
            type: 'image',
        });

    });

    function deleteGiftBox(id){
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
                url: "giftbox/"+id,
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
</script>
@endsection
