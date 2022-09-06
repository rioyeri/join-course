@extends('layout.main')
@php
    use App\SubModul;
    use App\JenisMapping;
@endphp
@section('css')
    {{-- Select2 --}}
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    {{-- Current Menu Mapping --}}
    <form action="{{route('updateRoleMapping',['id'=>$id])}}" method="post">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <h4 class="m-t-0 header-title">Role Mapping: {{$id}}</h4>

                    <div class="p-20">
                        <div class="form-group row">
                            <label class="col-2 col-form-label">Role</label>
                            <div class="col-10">
                                <select class="form-control select2" parsley-trigger="change" name="role_id">
                                    <option value="#" disabled selected>Pilih Role</option>
                                    @foreach ($roles as $role)
                                        @isset($user->role_id)
                                            @if ($user->role_id == $role->id)
                                                <option value="{{$role->id}}" selected>{{$role->role_name}}</option>
                                            @else
                                                <option value="{{$role->id}}" >{{$role->role_name}}</option>
                                            @endif
                                        @else
                                            <option value="{{$role->id}}" >{{$role->role_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

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
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
@endsection

@section('script-js')
<script>
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
@endsection
