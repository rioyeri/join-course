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
    $(document).ready(function() {
        // $(".select2").select2({
        //     width:'100%',
        // });

        // $("#teacher_id").select2({
        //     width:'100%',
        // });

        // Select2
        $(".select2").select2({
            templateResult: formatState,
            templateSelection: formatState,
            width:'100%',
        });

        function formatState (opt) {
            if (!opt.id) {
                return opt.text;
            }

            var optimage = $(opt.element).attr('data-image');
            if(!optimage){
            return opt.text;
            } else {
                var $opt = $(
                '<span><img src="' + optimage + '" width="60px" /> ' + opt.text + '</span>'
                );
                return $opt;
            }
        };

        $("#teacher_id").select2({
            templateResult: formatText,
            templateSelection: formatText,
            width:'100%',
        });

        function formatText (obj) {
            if($(obj.element).data('text') == 1){
                return $('<span>'+obj.text+' <span style="background: #008374; color:white; border-radius: 3px; margin-left: 20px;padding: 0 10px 0 10px;">Instant Order</span></span>');
            }else{
                return $('<span>'+obj.text+'</span>');
            }
        }
    });
</script>
<style>
    .timepicker-24 {
        height: 30px;
        top: 5px;
    }

    .row_schedule {
        margin-top:40px;
    }
</style>
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
            <select class="form-control" parsley-trigger="change" name="teacher_id" id="teacher_id" onchange="get_schedule(this.value)">
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
                            <option value="{{ $package->id }}" data-meet="{{ $package->number_meet }}" selected>{{ $package->name }}</option>
                        @else
                            <option value="{{ $package->id }}" data-meet="{{ $package->number_meet }}">{{ $package->name }}</option>
                        @endif
                    @endforeach
                @else
                    @foreach ($packages as $package)
                        <option value="{{$package->id}}" data-meet="{{ $package->number_meet }}">{{$package->name}}</option>
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
    <div id="line_schedule" @if(!isset($data->schedule_id)) style="display:none;" @endif>
        <div class="form-group">
            <label class="col-sm-3 col-sm-3 control-label">Schedule</label>
            <div class="col-sm-9">
                <select class="form-control select2 select2-multiple" multiple="multiple" multiple parsley-trigger="change" name="teacher_schedules[]" id="teacher_schedules" data-placeholder="-- Select --" onchange="generateSchedule()">
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
            <input type="text" data-date-format='yyyy-mm-dd' class="form-control datepicker" name="course_start" id="course_start" value="@isset($data->course_start){{ $data->course_start }}@endisset" onchange="generateSchedule(this.value)">
            <span id="span_date" class="input-group-btn add-on" onclick="moveToBox()" style="padding-right: 39px;">
                <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Class Type</label>
        <div class="col-sm-2">
            <div class="radio">
                <label><input type="radio" name="optionsRadios" id="options-online" value="online" @isset($data->order_type)@if($data->order_type=="online") checked @endif @endisset> Online</label>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="radio">
                <label><input type="radio" name="optionsRadios" id="options-offline" value="offline" @isset($data->order_type)@if($data->order_type=="offline") checked @endif @endisset> Offline</label>
            </div>
        </div>
    </div>
    <label class="mb" id="row_title">Add Schedule</label>
    <input type="hidden" id="detail_id" value="0">
    <input type="hidden" id="row_number" value="0">
    <div class="form-group-sm">
        <div class="col-sm-5">
            <input type="text" data-date-format='yyyy-mm-dd' class="form-control datepicker" id="schedule_date" value="" placeholder="Date">
            <span id="span_date" class="input-group-btn add-on" onclick="moveToBox()" style="padding-right: 39px; padding-top: 5px;">
                <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
            </span>
        </div>
        <div class="col-sm-5" style="margin-left:-25px;">
            <input type="text" class="form-control timepicker-24" id="schedule_time" value="" placeholder="Time">
            <span id="span_time" class="input-group-btn add-on" onclick="moveToTimeBox()" style="padding-right: 38px; padding-top: 5px;">
                <button class="btn btn-theme" type="button"><i class="fa fa-clock-o"></i></button>
            </span>
        </div>
        <div class="col-sm-1" id="button_adddetail" style="margin-left:-25px; margin-right: 20px; display:block;">
            <a class="btn btn-sm btn-theme02" id="save_button" onclick="addToTable()"><i class="fa fa-plus"></i> Add</a>
        </div>
        <div class="col-sm-1">
            <a class="btn btn-sm btn-danger" onclick="clearForm()">Clear</a>
        </div>
    </div>
    <br>
    <div id="table_schedule" style="margin-top:30px;">
        <div class="adv-table">
            <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-detail">
                <thead>
                    <th>No</th>
                    <th>Date</th>
                    <th>Option</th>
                </thead>
                <tbody id="table-body-detail">
                    @isset($details)
                        @php($i=1)
                        @foreach($details as $key)
                            <input type="hidden" name="detail_id[]" id="detail_id{{ $i }}" value="{{ $key->id }}">
                            <tr style="width:100%" id="trow{{ $i }}" class="trow">
                                <td style="width:10%">{{ $i }}</td>
                                <td style="width:70%">{{ date_format(date_create($key->schedule_time), "D, d-m-Y H:i:s") }}</td>
                                <input type="hidden" name="schedule_datetime[]" id="schedule_datetime{{ $i }}" value="{{ $key->schedule_time }}">
                                <td class="text-center" style="width:20%">
                                    <a href="javascript:;" type="button" class="btn btn-primary btn-sm" onclick="edit_row({{ $i }})">Edit</a>
                                    <a href="javascript:;" type="button" class="btn btn-danger btn-sm" onclick="delete_row({{ $i }})">Delete</a>
                                </td>
                            </tr>
                            @php($i++)
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
    <br>

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>

@if (session('user_data') && session('order'))
<script>
</script>
@endif
<script>
    // $(document).ready(function() {
    //     var teacher_id = $('#teacher_id').val();
    //     if(teacher_id != ""){
    //         get_schedule(teacher_id);
    //     }
    // });
    // Date Picker
    $('.datepicker').datepicker({
        todayHighlight: true,
        autoclose: true,
        startDate: '+1d',
    });

    function moveToBox(){
        $('#course_start').data("datepicker").show();
    }

    $('.timepicker-24').timepicker({
        autoclose: true,
        minuteStep: 1,
        showSeconds: true,
        showMeridian: false
    });

    function moveToTimeBox(){
        $('#schedule_time').data("timepicker-24").show();
    }

    function addToTable(){
        select = document.getElementById("package_id");
        var package_meet_number = select.options[select.selectedIndex].getAttribute('data-meet');
        count = $('#table-detail tbody tr.trow').length+1;

        var schedule_no = $('#row_number').val();
        var date = $('#schedule_date').val();
        var time = $('#schedule_time').val();
        var schedule = date+" "+time;
        var format_schedule = moment(schedule, "YYYY-MM-DD HH:mm:ss").format('ddd, DD-MM-YYYY, HH:mm:ss');

        duplicate = 0;
        $("#table-body-detail tr").each(function(){
            var value_count = $(this).find('td:eq(0)').text();
            if(schedule_no == value_count){
                duplicate++;
            }
        });

        if(duplicate == 0){
            if(count <= package_meet_number){
                var append = '<tr style="width:100%" id="trow'+count+'" class="trow"><td style="width:10%">'+count+'</td><td style="width:70%">'+format_schedule+'</td><input type="hidden" name="schedule_datetime[]" id="schedule_datetime'+count+'" value="'+schedule+'"><td class="text-center" style="width:20%"><a href="javascript:;" type="button" class="btn btn-primary btn-sm" onclick="edit_row('+count+')">Edit</a> <a href="javascript:;" type="button" class="btn btn-danger btn-sm" onclick="delete_row('+count+')" >Delete</a></td></tr>';
                $('#table-body-detail').append(append);
            }else{
                swal(
                    'Failed',
                    'Your Package just have limited meeting schedule',
                    'error'
                )
            }
        }else{
            $("#table-body-detail tr").each(function(){
                var value_count = $(this).find('td:eq(0)').text();
                if(value_count == schedule_no){
                    $(this).find('td:eq(1)').text(format_schedule);
                    $('#schedule_datetime'+value_count).val(schedule);
                }
            });
        }
        resetForm();
    }

    function edit_row(row){
        $("#table-body-detail tr").each(function(){
            var number = $(this).find('td:eq(0)').text();
            if(number == row){
                var datetime = $(this).find('td:eq(1)').text();
                var detail_id = $('#detail_id'+row).val();

                var date = moment(datetime, "ddd, DD-MM-YYYY, HH:mm:ss").format('YYYY-MM-DD');
                var time = moment(datetime, "ddd, DD-MM-YYYY, HH:mm:ss").format('HH:mm:ss');

                $('#schedule_date').val(date);
                $('#schedule_time').val(time);

                $('#detail_id').val(detail_id);
                $('#row_number').val(row);
                document.getElementById('row_title').textContent = "Update Schedule Row : "+row;
                document.getElementById('save_button').innerHTML = '<i class="fa fa-pencil"></i> Update';
            }
        });
    }

    function resetForm(){
        $('#schedule_date').val("");
        $('#schedule_time').val("");
        $('#row_number').val("");
        document.getElementById('row_title').textContent = "Add Schedule";
        document.getElementById('save_button').innerHTML = '<i class="fa fa-plus"></i> Add';
    }

    function delete_row(id){
        $('#trow'+id).remove();
        correctionNumber();
    }

    function correctionNumber(){
        var i = 1;
        $("#table-body-detail tr").each(function(){
            $(this).find('td:eq(0)').text(i++);
        })
    }
</script>