@extends('layout.main')

@section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- form Uploads -->
    <link href="{{ asset('assets/plugins/fileuploads/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('judul')
Registrasi Data User
@endsection

@section('content')
    @if($jenis == "create")
        <form class="form-horizontal" role="form" action="{{ route('user.store') }}" enctype="multipart/form-data" method="POST">
    @elseif($jenis == "edit")
        <form class="form-horizontal" role="form" action="{{ route('user.update',['id' => $user->id]) }}" enctype="multipart/form-data" method="POST">
            {{ method_field('PUT') }}
    @endif

    @csrf
    {{-- Informasi Pribadi --}}
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">Informasi Pribadi</h4>
                <p class="text-muted m-b-30 font-14">
                    <label class="col-4 col-form-label"><span class="text-danger">* required field</span></label>
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="p-20">
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="nama" id="nama" value="@isset($user->name){{$user->name}}@endisset" placeholder="Masukan Nama Lengkap">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Nomor KTP <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="text" class="form-control" name="ktp" id="ktp" value="@isset($user->ktp){{$user->ktp}}@endisset" parsley-trigger="change" required placeholder="Masukan No Identitas">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Alamat <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="alamat" id="alamat" value="@isset($user->address){{$user->address}}@endisset" placeholder="Masukan Alamat">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Telepon <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="text" class="form-control"  name="telepon" id="telepon" value="@isset($user->phone){{$user->phone}}@endisset" placeholder="Masukan Nomor Telepon">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">E-mail <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="email" class="form-control" parsley-trigger="change" required name="email" id="email" value="@isset($user->email){{$user->email}}@endisset" placeholder="Masukan Alamat Email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="text" class="form-control" name="tempat_lahir" parsley-trigger="change" required id="tempat_lahir" value="@isset($user->tmpt_lhr){{$user->tmpt_lhr}}@endisset" placeholder="Masukan Nama Kota Kelahiran">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control" parsley-trigger="change" required placeholder="yyyy-mm-dd" name="tanggal_lahir" id="tanggal_lahir"  value="@isset($user->tgl_lhr){{$user->tgl_lhr}}@endisset"  data-date-format='yyyy-mm-dd' autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="ti-calendar"></i></span>
                                        </div>
                                    </div><!-- input-group -->
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Upload Foto Profil</label>
                                <div class="col-10">
                                    <input type="file" class="dropify" data-height="100" name="foto_profil" id="foto_profil" data-default-file="@isset($user->foto_profil){{ asset('assets/images/user/foto/'.$user->foto_profil) }}@endisset" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center m-b-0">
                            <span class="help-block text-center"><b><i>--- Seluruh data pribadi yang anda masukkan, tidak akan kami salah gunakan ---</i></b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Account --}}
    @if($jenis=="create")
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">Informasi Akun</h4>
                <p class="text-muted m-b-30 font-14">
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="p-20">
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Username</label>
                                <div class="col-10">
                                    <input type="text" class="form-control" name="username" id="username" parsley-trigger="change" required autocomplete="off">
                                    <span class="help-block"><small>Username ini nantinya akan digunakan untuk login ke dashboard. Oleh sebab itu, username harus unik (tidak dapat diganti)</small></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Password</label>
                                <div class="col-10">
                                    <input type="password" class="form-control" name="password" id="password" parsley-trigger="change" required autocomplete="off" minlength="8">
                                    <span class="help-block"><small>Password harus terdiri dari minimal 8 karakter</small></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="form-group text-right m-b-0">
        <button class="btn btn-primary waves-effect waves-light" type="submit">
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
        // Date Picker
        $("#tanggal_lahir").datepicker();

        // Select2
        $(".select2").select2({
            templateResult: formatState,
            templateSelection: formatState
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
