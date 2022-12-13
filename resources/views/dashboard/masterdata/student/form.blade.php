<script>
    $(".select2").select2({
        width:'100%',
    });
</script>
@isset($data)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Student</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('student.update', ['id' => $data->id]) }}">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Student</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('student.store') }}">
@endisset
    @csrf
    <p class="text-muted font-14">
        <label class="col-4 col-form-label">( <span class="text-danger">*</span> ) is a required field</label>
    </p>
    @isset($data)
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="user_id" id="user_id" value="@isset($data){{ $data->student->name }}@endisset" disabled>
        </div>
    </div>
    @else
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Choose User <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="user_id" id="user_id" required>
                <option value="#" selected disabled>-- Choose --</option>
                @foreach ($users as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endisset
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">School's Name <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="school_name" id="school_name" value="@isset($data){{ $data->school_name }}@endisset" required>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Student's Grade <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="student_grade" id="student_grade" required>
                <option value="#" disabled selected>-- Choose --</option>
                @foreach ($grades as $grade)
                    @isset($data->student_grade)
                        @if ($grade->id == $data->student_grade)
                            <option value="{{$grade->id}}" selected>{{$grade->name}}</option>
                        @else
                            <option value="{{$grade->id}}" >{{$grade->name}}</option>
                        @endif
                    @else
                        <option value="{{$grade->id}}" >{{$grade->name}}</option>
                    @endisset
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>