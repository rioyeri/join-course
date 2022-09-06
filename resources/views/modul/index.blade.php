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
    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">
                <h4 class="m-t-0 header-title">Index Modul</h4>
                <p class="text-muted font-14 m-b-30">
                    <a href="{{ route('modul.create') }}" class="btn btn-success btn-rounded w-md waves-effect waves-light m-b-5">Tambah Modul</a>
                </p>

                <table id="responsive-datatable" class="table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <th>No</th>
                        <th>Modul ID</th>
                        <th>Modul Description</th>
                        <th>Modul Icon</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        @php($i = 1)
                        @foreach($moduls as $modul)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$modul->modul_id}}</td>
                            <td>{{$modul->modul_desc}}</td>
                            <td><i class="{{$modul->modul_icon}}"></i></td>
                            <td>
                                <a href="{{route('modul.edit',['id'=>$modul->modul_id])}}" class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5">Edit</a>
                                <a href="" class="btn btn-danger btn-rounded waves-effect waves-light w-md m-b-5">Hapus</a>
                            </td>
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
@endsection

@section('script-js')
<script type="text/javascript">

    $(document).ready(function () {

        // Responsive Datatable
        $('#responsive-datatable').DataTable();
    });
    
</script>
@endsection