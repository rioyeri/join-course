@extends('layout.main')

@section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- form Uploads -->
    <link href="{{ asset('assets/plugins/fileuploads/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Tags Input -->
    <link href="{{ asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <!-- Multi Select -->
    <link href="{{ asset('assets/plugins/multiselect/css/multi-select.css') }}"  rel="stylesheet" type="text/css" />
@endsection

@section('judul')
Form Complement Things
@endsection

@section('content')
    @isset($complement)
        <form class="form-horizontal" role="form" action="{{ route('complement.update',['id' => $complement->id]) }}" enctype="multipart/form-data" method="POST">
            {{ method_field('PUT') }}
            <input type="hidden" name="inv_id" id="inv_id" value="{{ $complement->invitation_id }}">
    @else
        <form class="form-horizontal" role="form" action="{{ route('complement.store') }}" enctype="multipart/form-data" method="POST">
    @endisset

    @csrf
    {{-- Informasi Pribadi --}}
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">Complement Things</h4>
                <p class="text-muted m-b-30 font-14">
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="p-20">
                            <label class="col-4 col-form-label"><span class="text-danger">* required field</span></label>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Invitation ID <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <select class="form-control select2" parsley-trigger="change" name="invitation_id" id="invitation_id" @if(isset($complement)) disabled @endif>
                                        <option value="#" disabled selected>Pilih Invitation ID</option>
                                        @foreach ($invitation as $inv)
                                            @isset($complement->invitation_id)
                                                @if ($complement->invitation_id == $inv->invitation_id)
                                                    <option value="{{ $inv->invitation_id }}" selected>{{ $inv->invitation_id }}</option>
                                                @else
                                                    <option value="{{ $inv->invitation_id }}">{{ $inv->invitation_id }}</option>
                                                @endif                                            
                                            @else
                                                <option value="{{ $inv->invitation_id }}">{{ $inv->invitation_id }}</option>
                                            @endisset
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Upload Tab Icon <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="file" class="dropify" data-height="100" name="icon" id="icon" data-default-file="@isset($complement->icon){{ asset('multimedia/'.$complement->invitation_id.'/'.$complement->icon) }}@endisset" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Upload Background Song <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="file" class="dropify" data-height="100" name="song" id="song" data-default-file="@isset($complement->song){{ asset('multimedia/'.$complement->invitation_id.'/'.$complement->song) }}@endisset" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Upload Background for Banner <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="file" class="dropify" data-height="100" name="banner" id="banner" data-default-file="@isset($complement->banner){{ asset('multimedia/'.$complement->invitation_id.'/'.$complement->banner) }}@endisset" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group text-right m-b-0">
        <a href="{{ route("complement.index") }}" class="btn btn-warning waves-effect waves-light">Kembali</a>
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
<!-- Tags Input -->
<script src="{{ asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
<!-- Max Length -->
<script src="{{ asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
<!-- Multi Select -->
<script src="{{ asset('assets/plugins/multiselect/js/jquery.multi-select.js') }}" type="text/javascript"></script>
<!-- Select -->
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
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
