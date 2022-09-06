@extends('layout.main')

@section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- form Uploads -->
    <link href="{{ asset('assets/plugins/fileuploads/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('judul')
Create New Invitation
@endsection

@section('content')
    @isset($invitation)
        <form class="form-horizontal" role="form" action="{{ route('invitation.update',['id' => $invitation->id]) }}" enctype="multipart/form-data" method="POST">
            {{ method_field('PUT') }}
    @else
        <form class="form-horizontal" role="form" action="{{ route('invitation.store') }}" enctype="multipart/form-data" method="POST">
    @endisset

    @csrf
    {{-- Informasi Pribadi --}}
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">Bride-Groom Information</h4>
                <p class="text-muted m-b-30 font-14">
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="p-20">
                            <label class="col-4 col-form-label"><span class="text-danger">* required field</span></label>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Invitation ID <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="invitation_id" id="invitation_id" value="@isset($invitation->invitation_id){{$invitation->invitation_id}}@endisset" placeholder="Invitation ID">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Groom's Name<span class="text-danger">*</span></label>
                                <div class="col-5">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="groom_name" id="groom_name" value="@isset($invitation->groom_name){{$invitation->groom_name}}@endisset" placeholder="Groom's Full Name">
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="groom_nickname" id="groom_nickname" value="@isset($invitation->groom_nickname){{$invitation->groom_nickname}}@endisset" placeholder="Groom's Nick Name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Groom's Parent<span class="text-danger">*</span></label>
                                <div class="col-5">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="groom_father" id="groom_father" value="@isset($invitation->groom_father){{$invitation->groom_father}}@endisset" placeholder="Groom's Father">
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="groom_mother" id="groom_mother" value="@isset($invitation->groom_mother){{$invitation->groom_mother}}@endisset" placeholder="Groom's Mother">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Groom's Photo <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="file" class="dropify" data-height="100" name="groom_photo" id="groom_photo" data-default-file="@isset($invitation->groom_photo){{ asset('multimedia/'.$invitation->invitation_id."/".$invitation->groom_photo) }}@endisset" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Bride's Name<span class="text-danger">*</span></label>
                                <div class="col-5">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="bride_name" id="bride_name" value="@isset($invitation->bride_name){{$invitation->bride_name}}@endisset" placeholder="Bride's Full Name">
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="bride_nickname" id="bride_nickname" value="@isset($invitation->bride_nickname){{$invitation->bride_nickname}}@endisset" placeholder="Bride's Nick Name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Bride's Parent<span class="text-danger">*</span></label>
                                <div class="col-5">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="bride_father" id="bride_father" value="@isset($invitation->bride_father){{$invitation->bride_father}}@endisset" placeholder="Bride's Father">
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="bride_mother" id="bride_mother" value="@isset($invitation->bride_mother){{$invitation->bride_mother}}@endisset" placeholder="Bride's Mother">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Bride's Photo <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <input type="file" class="dropify" data-height="100" name="bride_photo" id="bride_photo" data-default-file="@isset($invitation->bride_photo){{ asset('multimedia/'.$invitation->invitation_id."/".$invitation->bride_photo) }}@endisset" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group text-right m-b-0">
        <a href="{{ route("invitation.index") }}" class="btn btn-warning waves-effect waves-light">Kembali</a>
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
