@extends('layout.main')

@section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- form Uploads -->
    <link href="{{ asset('assets/plugins/fileuploads/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Box Icons -->
    <link href="{{ asset('assets2/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
@endsection

@section('judul')
Form Social Media
@endsection

@section('content')
    @isset($sosmed)
        <form class="form-horizontal" role="form" action="{{ route('sosmed.update',['id' => $sosmed->id]) }}" enctype="multipart/form-data" method="POST">
            {{ method_field('PUT') }}
    @else
        <form class="form-horizontal" role="form" action="{{ route('sosmed.store') }}" enctype="multipart/form-data" method="POST">
    @endisset

    @csrf
    {{-- Informasi Pribadi --}}
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">Informasi Social Media</h4>
                <p class="text-muted m-b-30 font-14">
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="p-20">
                            <label class="col-4 col-form-label"><span class="text-danger">* required field</span></label>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Nama <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="name" id="name" value="@isset($sosmed->name){{$sosmed->name}}@endisset" placeholder="Masukan Nama Sosmed">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Icon <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <select class="select2 form-control" parsley-trigger="change" name="icon">
                                        @isset($sosmed->icon)
                                            <option value="#" disabled>Pilih Icon</option>
                                            @foreach ($icons as $icon)
                                                @if($sosmed->icon == $icon->icon)
                                                    <option value="{{ $icon->icon }}" data-icon="{{ $icon->icon }}" selected>{{ $icon->icon }}</option>
                                                @else
                                                    <option value="{{ $icon->icon }}" data-icon="{{ $icon->icon }}">{{ $icon->icon }}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option value="#" disabled selected>Pilih Icon</option>
                                            @foreach ($icons as $icon)
                                                <option value="{{ $icon->icon }}" data-icon="{{ $icon->icon }}">{{ $icon->icon }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    {{-- <input type="text" class="form-control" parsley-trigger="change" required name="icon" id="icon" value="@isset($sosmed->icon){{$sosmed->icon}}@endisset" placeholder="Contoh : bx bxl-facebook">
                                    <span class="help-block"><small>Kode bisa didapatkan melalui <a href="https://boxicons.com/" target="_blank">https://boxicons.com/</a></small></span> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group text-right m-b-0">
        <a href="{{ route("sosmed.index") }}" class="btn btn-warning waves-effect waves-light">Kembali</a>
        <button class="btn btn-success waves-effect waves-light" type="submit">
            Simpan
        </button>
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
        });
    </script>

    <script>
        // Select2
        $(".select2").select2({
            templateSelection: formatText,
            templateResult: formatText,
        });

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

        function formatText (icon) {
            return $('<span><i class="'+$(icon.element).data('icon') +'" width="160px"></i>  ' + icon.text + '</span>');
        };

    </script>

    <script type="text/javascript">
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
    </script>
@endsection
