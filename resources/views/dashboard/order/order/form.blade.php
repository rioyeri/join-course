@php
    use App\Models\Order;
    use App\Models\Teacher;
    if(session('order') && session('user_data')){
        $data = Order::getFormatData(session('user_data'), session('order'));
        $teachers = Teacher::getTeacherCourse($data->course_id);
        $schedules = Teacher::getTeacherSchedules($data->teacher_id);
    }
@endphp

<script>
    $(".select2").select2({
        width:'100%',
    });

    $("#teacher_id").select2({
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
    @isset($data->order_id)
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">ORDER ID</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" value="{{ $data->order_id }}" disabled>
        </div>
    </div>
    @endisset
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Student</label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="student_id" id="student_id">
                @if(count($students) == 1)
                    @foreach ($students as $student)
                        <option value="{{$student->id}}" selected>{{$student->student->name}}</option>
                    @endforeach
                @else
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
                @endif
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
                        @if($data->course_id == $course->id)
                            <option value="{{$course->id}}" selected>{{$course->name}}</option>
                        @else
                            <option value="{{$course->id}}" >{{$course->name}}</option>
                        @endif
                    @else
                        <option value="{{$course->id}}" >{{$course->name}}</option>
                    @endisset
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Teacher</label>
        <div class="col-sm-9">
            <select class="form-control" parsley-trigger="change" name="teacher_id" id="teacher_id" onchange="get_package(this.value)">
                <option value="#" selected disabled>-- Select --</option>
                @isset($data->teacher_id)
                    @foreach ($teachers as $teacher)
                        @if($data->teacher_id == $teacher->id)
                            <option value="{{ $teacher->id }}" data-text="{{ $teacher->isItInstantOrder() }}" selected>{{ $teacher->teacher->name }}</option>
                        @else
                            <option value="{{ $teacher->id }}"data-text="{{ $teacher->isItInstantOrder() }}">{{ $teacher->teacher->name }}</option>
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
    <div id="line_schedule" @isset($data->schedule_id)style="display:none;"@endisset>
        <div class="form-group">
            <label class="col-sm-3 col-sm-3 control-label">Schedule</label>
            <div class="col-sm-9">
                <select class="form-control select2 select2-multiple" multiple="multiple" multiple parsley-trigger="change" name="teacher_schedules[]" id="teacher_schedules" data-placeholder="-- Select --">
                    @isset($data->schedules_id)
                        @foreach ($schedules as $schedule)
                            @if(in_array($schedule->id, $data->schedules_id))
                                <option value="{{$schedule->id}}" selected>{{$schedule->get_day->day_name}}, {{ $schedule->time_start }} - {{ $schedule->time_end }}</option>
                            @else
                                <option value="{{$schedule->id}}" >{{$schedule->get_day->day_name}}, {{ $schedule->time_start }} - {{ $schedule->time_end }}</option>
                            @endif
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Order Bill</label>
        <div class="col-sm-9">
            @if(session('role_id') == 4)
                <input type="hidden" name="order_bill" id="order_bill" value="@isset($data->order_bill){{ $data->order_bill }}@endisset">
                <input type="text" class="form-control" name="order_bill_display" id="order_bill_display" value="Rp @isset($data->order_bill){{ number_format($data->order_bill,2,",",".") }}@else{{ number_format(0,2,",",".") }}@endisset" disabled>
            @else
                <input type="text" class="form-control" name="order_bill" id="order_bill" value="@isset($data->order_bill){{ $data->order_bill }}@endisset">
            @endif
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Select Date to Start</label>
        <div class="col-sm-9">
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

@if (session('user_data') && session('order'))
<script>
    $(document).ready(function() {
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

        $("#teacher_id").select2({
            templateResult: formatText,
            templateSelection: formatText,
        });

        function formatText (obj) {
            if($(obj.element).data('text') == 1){
                return $('<span>'+obj.text+' <span style="background: #008374; color:white">Instant Order</span></span>');
            }else{
                return $('<span>'+obj.text+'</span>');
            }
        }
        // var subject = $('#course_id').val();
        // if(subject != "#"){
        //     get_teacher(subject);
        // }
        // var teacher = $('#teacher_id').val();
        // if(teacher){
        //     get_schedule(teacher);
        // }
    })    
</script>
@endif
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