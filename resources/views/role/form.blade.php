@extends('layout.main')

@section('css')
@endsection

@section('judul')
Tambah Data Role
@endsection

@section('content')

    @if($jenis == "create")
        <form class="form-horizontal" role="form" action="{{ route('role.store') }}" enctype="multipart/form-data" method="POST">
    @elseif($jenis == "edit")
        <form class="form-horizontal" role="form" action="{{ route('role.update',['id' => $role->id]) }}" enctype="multipart/form-data" method="POST">
            {{ method_field('PUT') }}
    @endif

    @csrf
    {{-- Informasi Pribadi --}}
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">Role</h4>
                <p class="text-muted m-b-30 font-14">
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="p-20">
                            <div class="form-group row">
                                <label class="col-2 col-form-label">Nama Role</label>
                                <div class="col-10">
                                    <input type="text" class="form-control" parsley-trigger="change" required name="role_name" id="role_name" value="@isset($role->role_name){{$role->role_name}}@endisset">
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
