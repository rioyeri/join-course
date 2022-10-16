<script>
    $(".select2").select2({
        width:'100%',
    });
</script>
@isset($data->id)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Order</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('order.update', ['id' => $data->id]) }}">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Order</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('order.store') }}">
@endif
    @csrf
    @isset($data->invoice_id)
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">ORDER ID</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" value="{{ $data->invoice_id }}" disabled>
        </div>
    </div>
    @endisset
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Student</label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="student_id" id="student_id">
                <option value="#" selected disabled>-- Select --</option>
                @foreach ($students as $student)
                    @isset($data->student_id)
                        @if($data->student_id == $student->id)
                            <option value="{{$student->id}}" selected>{{$student->student->name}}</option>
                        @else
                            <option value="{{$student->id}}" >{{$student->student->name}}</option>
                        @endif
                    @else
                        <option value="{{$student->id}}">{{$student->student->name}}</option>
                    @endisset
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Grade</label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="grade_id" id="grade_id">
                <option value="#" selected disabled>-- Select --</option>
                @foreach ($grades as $grade)
                    @isset($data->grade_id)
                        @if($data->grade_id == $grade->id)
                            <option value="{{$grade->id}}" selected>{{$grade->name}}</option>
                        @else
                            <option value="{{$grade->id}}" >{{$grade->name}}</option>
                        @endif
                    @else
                        <option value="{{$grade->id}}">{{$grade->name}}</option>
                    @endisset
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Select Subject</label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="course_id" id="course_id" onchange="get_teacher(this.value)">
                <option value="#" disabled selected>-- Select --</option>
                @foreach ($courses as $course)
                    @isset($data->course_id)
                        {{-- <optgroup label="{{ $course->name }}"> --}}
                        @if($data->course_id == $course->id)
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
        <label class="col-sm-3 col-sm-3 control-label">Teacher</label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="teacher_id" id="teacher_id" onchange="get_package(this.value)">
                <option value="#" selected disabled>-- Select --</option>
                @isset($data->teacher_id)
                    @foreach ($teachers as $teacher)
                        @if($data->teacher_id == $teacher->id)
                            <option value="{{ $teacher->id }}" selected>{{ $teacher->teacher->name }}</option>
                        @else
                            <option value="{{ $teacher->id }}">{{ $teacher->teacher->name }}</option>
                        @endif
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Select Package</label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="package_id" id="package_id" onchange="get_bill()">
                <option value="#" disabled selected>-- Select--</option>
                @isset($data->package_id)
                    @foreach ($packages as $package)
                        @if($data->package_id == $package->id)
                            <option value="{{ $package->id }}" selected>{{ $package->name }}</option>
                        @else
                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                        @endif
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Order Bill</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="order_bill" id="order_bill" value="@isset($data->order_bill){{ $data->order_bill }}@endisset">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 col-md-3 control-label">Select Date to Start</label>
        <div class="col-md-9 col-xs-11">
            {{-- <input type="text" class="form-control datepicker" name="course_start" id="course_start" value="@isset($data){{ $data->course_start }}@endisset"> --}}
            <input type="text" data-date-format='yyyy-mm-dd' class="form-control datepicker" name="course_start" id="course_start" value="@isset($data->course_start){{ $data->course_start }}@endisset">
            <span id="span_date" class="input-group-btn add-on" onclick="moveToBox()" style="padding-right: 39px;">
                <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
            </span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>

<script>
// Date Picker
    jQuery('.datepicker').datepicker({
        todayHighlight: true,
        autoclose: true
    });

    function moveToBox(){
        $('#course_start').data("datepicker").show();
    }
</script>