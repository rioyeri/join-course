@extends('layout.main')

@section('css')
@endsection

@section('judul')
Tambah Data Modul
@endsection

@section('content')

    @if($jenis == "create")
        <form class="form-horizontal" role="form" action="{{ route('modul.store') }}" enctype="multipart/form-data" method="POST">
    @elseif($jenis == "edit")
        <form class="form-horizontal" role="form" action="{{ route('modul.update',['id' => $modul->modul_id]) }}" enctype="multipart/form-data" method="POST">
            {{ method_field('PUT') }}
    @endif

    @csrf
    {{-- Informasi Pribadi --}}
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">Tambah Modul</h4>
                <p class="text-muted m-b-30 font-14">
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="p-20">
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Modul ID</label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="modul_id" id="modul_id" value="@isset($modul->modul_id){{$modul->modul_id}}@endisset">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Modul Description</label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="modul_desc" id="modul_desc" value="@isset($modul->modul_desc){{$modul->modul_desc}}@endisset">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Modul Icon</label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="modul_icon" id="modul_icon" value="@isset($modul->modul_icon){{$modul->modul_icon}}@endisset">
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