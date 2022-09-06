@extends('layout.main')

@section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- form Uploads -->
    <link href="{{ asset('assets/plugins/fileuploads/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('judul')
Form Tambah Link
@endsection

@section('content')
    @isset($link)
        <form class="form-horizontal" role="form" action="{{ route('link.update',['id' => $link->id]) }}" enctype="multipart/form-data" method="POST">
            {{ method_field('PUT') }}
    @else
        <form class="form-horizontal" role="form" action="{{ route('link.store') }}" enctype="multipart/form-data" method="POST">
    @endisset

    @csrf
    {{-- Informasi Pribadi --}}
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">Informasi Link</h4>
                <p class="text-muted m-b-30 font-14">
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="p-20">
                            <label class="col-4 col-form-label"><span class="text-danger">* required field</span></label>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Link URL <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="link" id="link" value="@isset($link->link){{$link->link}}@endisset" placeholder="Masukan Link tujuan">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Judul Link <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="title" id="title" value="@isset($link->title){{$link->title}}@endisset" placeholder="Masukan Judul Link">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Kategori URL <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <select class="form-control select2" parsley-trigger="change" name="category">
                                        @isset($link->category)
                                            <option value="#" disabled>Pilih Kategori Link</option>
                                            @foreach($sosmeds as $sosmed)
                                                @if($sosmed->name == $link->category)
                                                    <option value="{{ $sosmed->name }}" selected>{{ $sosmed->name }}</option>
                                                @else
                                                    <option value="{{ $sosmed->name }}">{{ $sosmed->name }}</option>
                                                @endif
                                            @endforeach
                                            @if($link->category == "others")
                                                <option value="others" selected>Lainnya</option>
                                            @else
                                                <option value="others">Lainnya</option>
                                            @endif
                                        @else
                                            <option value="#" disabled selected>Pilih Kategori Link</option>
                                            @foreach ($sosmeds as $sosmed)
                                                <option value="{{ $sosmed->name }}">{{ $sosmed->name }}</option>
                                            @endforeach
                                            <option value="others">Lainnya</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Deskripsi Link</label>
                                <div class="col-10">
                                    <textarea id="textarea" name="description" class="form-control" maxlength="225" rows="2" placeholder="Masukan deskripsi tambahan untuk menjelaskan link.">@isset($link->description){{ $link->description }}@endisset</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group text-right m-b-0">
        <a href="{{ route("link.index") }}" class="btn btn-warning waves-effect waves-light">Kembali</a>
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
        $(".select2").select2();

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
