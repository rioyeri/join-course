@extends('layout.main')

@section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('judul')
Tambah Data SubModul
@endsection

@section('content')

    @if($jenis == "create")
        <form class="form-horizontal" role="form" action="{{ route('submodul.store') }}" enctype="multipart/form-data" method="POST">
    @elseif($jenis == "edit")
        <form class="form-horizontal" role="form" action="{{ route('submodul.update',['id' => $sub->submodul_id]) }}" enctype="multipart/form-data" method="POST">
            {{ method_field('PUT') }}
    @endif

    @csrf
    {{-- Informasi Pribadi --}}
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">Tambah SubModul</h4>
                <p class="text-muted m-b-30 font-14">
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="p-20">
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Modul</label>
                                <div class="col-10">
                                    <select class="form-control select2" parsley-trigger="change" name="modul_id">
                                        <option value="#" disabled selected>Pilih Modul</option>
                                        @foreach ($moduls as $modul)
                                            @isset($sub->modul_id)
                                                @if ($sub->modul_id == $modul->modul_id)
                                                    <option value="{{$modul->modul_id}}" selected>{{$modul->modul_desc}}</option>
                                                @else
                                                    <option value="{{$modul->modul_id}}">{{$modul->modul_desc}}</option>
                                                @endif
                                            @else
                                                <option value="{{$modul->modul_id}}">{{$modul->modul_desc}}</option>
                                            @endisset
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">SubModul ID</label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="submodul_id" id="submodul_id" value="@isset($sub->submodul_id){{$sub->submodul_id}}@endisset">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">SubModul Description</label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="submodul_desc" id="submodul_desc" value="@isset($sub->submodul_desc){{$sub->submodul_desc}}@endisset">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">SubModul Page</label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="submodul_page" id="submodul_page" value="@isset($sub->submodul_page){{$sub->submodul_page}}@endisset">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group text-right m-b-0">
        <button class="btn btn-primary waves-effect waves-light" type="submit">
            Submit
        </button>
    </div>

</form>
@endsection

@section('js')
<!-- Validation js (Parsleyjs) -->
<script type="text/javascript" src="{{ asset('assets/plugins/parsleyjs/dist/parsley.min.js') }}"></script>
@endsection

@section('script-js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('form').parsley();
        });

    </script>
@endsection