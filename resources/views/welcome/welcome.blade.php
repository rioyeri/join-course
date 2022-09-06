@extends('layout.main')
@php
    use App\Coa;
    use App\TaskEmployee;
    use App\User;
@endphp

@section('css')
<style>
    span.desc{
        font-size: 14px;
    }
</style>
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
@endsection

@section('judul')

@endsection

@section('content')
@php
    use Carbon\Carbon;
@endphp

<div class="card-box">
    <div class="row">
        <div class="form-group col-md-8">
            <h1 class="text-dark">Hai {{ session('name') }},</h1>
            @php
                Carbon::setLocale('id');
                $getTime = Carbon::now()->toTimeString();
                if($getTime < '12:00:00'){
                    $time = "PAGI";
                }elseif(($getTime >='12:00:00') && ($getTime < '15:00:00')){
                    $time = "SIANG";
                }elseif(($getTime >='15:00:00') && ($getTime < '18:00:00')){
                    $time = "SORE";
                }elseif($getTime >='18:00:00'){
                    $time = "MALAM";
                }
            @endphp
            <p class="text-dark"><strong>SELAMAT {{ $time }}, DAN SELAMAT MELAYANI TUHAN!</strong></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <img src="{{ asset(User::getPhoto(session('user_id'))) }}"  alt="user-img" title="{{ session('name') }}" class="rounded-circle img-thumbnail img-responsive photo">
        </div>
        <div class="form-group col-md-8" style="font-size:initial;">
            <div class="form-group row">
                <label class="col-2 col-form-label">Nama:</label>
                <label class="col-10 col-form-label">{{$user->name}}</label>
            </div>
            <div class="form-group row">
                <label class="col-2 col-form-label">Role:</label>
                <label class="col-10 col-form-label">{{session('role')}}</label>
            </div>
        </div>
    </div>
    @if(session('role') == 'Superadmin' || session('role') == 'Owner' || session('role') == 'Admin')
        @if($birth <> null)
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">
                        <h4 class="m-t-0 header-title">Daftar Ulang Tahun</h4>
                        <table id="responsive-datatable" class="table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <th>No</th>
                                <th>Name</th>
                                <th>Tanggal</th>
                            </thead>

                            <tbody>
                                @php($i = 1)
                                @foreach($birth as $key)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$key['name']}}</td>
                                        <td>{{$key['tanggal']}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end row -->
        @endif
    @endif
    <!--  Modal content for the above example -->
    <div class="modal fade bs-example-modal-lg" id="modalLarge" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg" id="do-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Detail Task</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="closemodal">×</button>
                </div>
                <div class="modal-body" id="modalView">
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
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
<script>

</script>
@endsection
