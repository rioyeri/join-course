@extends('layout.main')
@php
    use App\SubModul;
    use App\MenuMapping;
@endphp
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
    {{-- Current Menu Mapping --}}
    <form action="{{route('deleteMapping')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="user_id" value="{{$id}}">
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <h4 class="m-t-0 header-title">Current Mapping: {{$name}}</h4>

                    <table id="tbl-cur" class="table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <th>No</th>
                            {{-- <th style="width:1%">Check All</th> --}}
                            <th>Nama Modul</th>
                            <th>Nama Submodul</th>
                            <th>Action</th>
                        </thead>

                        <tbody>
                            @php($i = 1)
                            @foreach($currents as $current)
                            <tr>
                                <td>{{$i}}</td>
                                {{-- <td>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="currenta[]" name="current[]" value="#">
                                    </div>
                                </td> --}}
                                <td>{{$current->modul_desc}}</td>
                                <td>{{$current->submodul_desc}}</td>
                                <td>
                                    @foreach ($current->submapping as $submap)
                                        <div>
                                            <input id="checkboxcurrent{{$submap->submapping_id}}" name="current[]" type="checkbox" value="{{$submap->submapping_id}}">
                                            <label for="checkboxcurrent{{$submap->submapping_id}}">
                                                {{$submap->jenis_id}}
                                            </label>
                                        </div>
                                    @endforeach
                                </td>
                            </tr>
                            @php($i++)
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end row -->

        <div class="form-group text-right m-b-0">
            <button class="btn btn-primary waves-effect waves-light" type="submit">
                Submit
            </button>
        </div>
    </form>

    {{-- Rest of Menu Mapping --}}
    <form action="{{route('storeMapping')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="user_id" value="{{$id}}">
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <h4 class="m-t-0 header-title">List Modul</h4>

                    <table id="tbl-rest" class="table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <th>No</th>
                            <th>Nama Modul</th>
                            <th>Nama Submodul</th>
                            <th>Action</th>
                            
                        </thead>

                        <tbody>
                            @php($i = 1)
                            @foreach($rests as $rest)
                            <tr>
                                <td>
                                    {{$i}}
                                </td>
                                <td>{{$rest->modul}}</td>
                                <td>{{$rest->submodul}}</td>
                                <td>
                                    @foreach ($rest->submapping as $submap)
                                        <div>
                                            <input id="checkboxrest{{$submap->id}}" name="rest[]" type="checkbox" value="{{$submap->id}}">
                                            <label for="checkboxrest{{$submap->id}}">
                                                {{$submap->jenis_id}}
                                            </label>
                                        </div>
                                    @endforeach
                                </td>
                            </tr>
                            @php($i++)
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end row -->

        <div class="form-group text-right m-b-0">
            <button class="btn btn-primary waves-effect waves-light" type="submit">
                Submit
            </button>
        </div>
    </form>
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
// Responsive Datatable
$('#tbl-rest').DataTable();
$('#tbl-cur').DataTable();
</script>
@endsection