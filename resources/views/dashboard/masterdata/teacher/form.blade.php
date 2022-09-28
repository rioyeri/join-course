<script>
    $(".select2").select2({
        width:'100%',
    });
</script>
@isset($data)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Teacher</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('teacher.update', ['id' => $data->id]) }}">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Teacher</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('teacher.store') }}">
@endif
    @csrf
    @isset($data)
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="user_id" id="user_id" value="@isset($data){{ $data->name }}@endisset" disabled>
        </div>
    </div>
    @else
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Choose User</label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="user_id" id="user_id">
                <option value="#" selected disabled>-- Choose --</option>
                @foreach ($users as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endisset
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Subjects</label>
        <div class="col-sm-9">
            <select class="form-control select2 select2-multiple" multiple="multiple" multiple parsley-trigger="change" name="teacher_subjects[]" id="teacher_subjects" data-placeholder="-- What subject do you teach--">
                {{-- <option value="#" disabled selected>-- What subject do you teach--</option> --}}
                @foreach ($courses as $course)
                    @isset($data)
                        {{-- <optgroup label="{{ $course->name }}"> --}}
                        @if(in_array($course->id, $exist_course))
                            <option value="{{$course->id}}" selected>{{$course->name}}</option>
                        @else
                            <option value="{{$course->id}}" >{{$course->name}}</option>
                        @endif
                        {{-- </optgroup> --}}
                    @else
                        <optgroup label="{{ $course->name }}">
                            <option value="{{$course->id}}" >{{$course->name}}</option>
                        </optgroup>
                    @endisset
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>