@extends('layout.main')

@section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- form Uploads -->
    <link href="{{ asset('assets/plugins/fileuploads/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('judul')
Form Profile Website
@endsection

@section('content')
    @isset($profile)
        <form class="form-horizontal" role="form" action="{{ route('profile.update',['id' => $profile->id]) }}" enctype="multipart/form-data" method="POST">
            {{ method_field('PUT') }}
    @else
        <form class="form-horizontal" role="form" action="{{ route('profile.store') }}" enctype="multipart/form-data" method="POST">
    @endisset

    @csrf
    {{-- Informasi Pribadi --}}
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">Informasi Profile Website</h4>
                <p class="text-muted m-b-30 font-14">
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="p-20">
                            <label class="col-4 col-form-label"><span class="text-danger">* required field</span></label>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Nama Depan <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="first_name" id="first_name" value="@isset($profile->first_name){{$profile->first_name}}@endisset" placeholder="Masukan Nama Depan">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Nama Tengah</label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" name="mid_name" id="mid_name" value="@isset($profile->mid_name){{$profile->mid_name}}@endisset" placeholder="Masukan Nama Tengah">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Nama Belakang<span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="last_name" id="last_name" value="@isset($profile->last_name){{$profile->last_name}}@endisset" placeholder="Masukan Nama Belakang">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Alamat</label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" name="address" id="address" value="@isset($profile->address){{$profile->address}}@endisset" placeholder="Masukan Alamat">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Nomor Telepon</label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" name="phone" id="phone" value="@isset($profile->phone){{$profile->phone}}@endisset" placeholder="Masukan Nomor Telepon">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Email</label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" name="email" id="email" value="@isset($profile->email){{$profile->email}}@endisset" placeholder="Masukan Email">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group text-right m-b-0">
        <a href="{{ route("profile.index") }}" class="btn btn-warning waves-effect waves-light">Kembali</a>
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
