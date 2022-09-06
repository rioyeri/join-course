@extends('layout.main')
@php
    use App\Employee;
    use App\DemoFinger;
@endphp

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

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">
                <h4 class="m-t-0 header-title">Index User</h4>
                <p class="text-muted font-14 m-b-30">
                    @if (array_search("USUSC",$page))
                        <a href="{{ route('user.create') }}" class="btn btn-success btn-rounded w-md waves-effect waves-light m-b-5">Tambah User</a>
                    @endif
                </p>

                <table id="responsive-datatable" class="table table-bordered table-bordered dt-responsive wrap" cellspacing="0" width="100%">
                    <thead>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Alamat</th>
                        <th>No Hp</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>Action</th>
                        <th>Tanggal Registrasi</th>
                    </thead>

                    <tbody>
                        @php($i = 1)
                        @foreach($users as $user)
                        <tr>
                            <td>{{$i}}</td>
                            <td>
                                <a href="{{ asset('assets/images/user/foto/'.$user->foto_profil) }}" class="image-popup" title="{{$user->name}}">
                                    <img src="{{ asset('assets/images/user/foto/'.$user->foto_profil) }}"  alt="user-img" title="{{ $user->name }}" class="rounded-circle img-thumbnail img-responsive photo">
                                </a>
                            </td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->username}}</td>
                            <td>{{$user->address}}</td>
                            <td>{{$user->phone}}</td>
                            <td>{{$user->tmpt_lhr}}</td>
                            <td>{{$user->tgl_lhr}}</td>
                            <td>
                                @if (array_search("USUSU",$page))
                                    <a href="{{route('user.edit',['id'=>$user->id])}}" class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5">Edit</a>
                                @endif
                                @if (array_search("USUSD",$page))
                                    <a href="javascript:;" class="btn btn-danger btn-rounded waves-effect waves-light w-md m-b-5" onclick="deleteUser({{$user->id}})">Hapus</a>
                                @endif
                            </td>
                            <td>{{date('Y-m-d', strtotime($user->created_at))}}</td>
                        </tr>
                        @php($i++)
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
                { "width": "15%", "targets": 4 }
            ]
        });

        $('.image-popup').magnificPopup({
            type: 'image',
        });

    });

    function deleteUser(id){
        var token = $("meta[name='csrf-token']").attr("content");
        var result = checkBeforeDelete(id);

        if(result.text == ""){
            swal({
                title: "Apakah anda yakin ingin menghapus User ini?",
                text: "Tidak ada transaksi yang terdaftar menggunakan User ini",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Tidak, Batalkan!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger m-l-10',
                buttonsStyling: false
            }).then(function () {
                $.ajax({
                    url: "user/"+id,
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
        }else{
            title = result.title;
            text = result.text;
            swal(
                title,
                text,
                'error'
            )
        }
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
