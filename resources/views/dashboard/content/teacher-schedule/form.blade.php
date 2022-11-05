@isset($data->id)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Teacher Schedule : <strong>{{ $data->teacher->name }}</strong></h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('teacherschedule.update', ['id' => $data->id]) }}">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Teacher Schedule</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('teacherschedule.store') }}">
@endif
    @csrf
    @if(!isset($data->id))
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Teacher</label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="teacher_id" id="teacher_id">
                <option value="#" selected disabled>-- Select --</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->teacher->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

    <h5 class="mb" id="title-edit"><strong>Add Detail Schedule</strong></h5>
    <input type="hidden" id="detail_id" value="0">
    <input type="hidden" id="row_number" value="0">
    <div class="form-group-sm">
        <label class="col-sm-2 control-label" style="padding-left:-100px;">Schedule</label>
        <div class="col-sm-3">
            <select class="form-control select2" id="day">
                <option value="#" disabled selected>-- Day --</option>
                @foreach ($days as $day)
                    <option value="{{ $day->id }}">{{ $day->day_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3" style="margin-left:-25px;">
            <input type="text" class="form-control timepicker-24" id="start" value="" placeholder="Time Start">
            <span id="span_time" class="input-group-btn add-on" onclick="moveToTimeBox('start')" style="padding-right: 32px; padding-top: 6px;">
                <button class="btn btn-theme btn-sm" type="button" style="height: 31px;"><i class="fa fa-clock-o"></i></button>
            </span>
        </div>
        <div class="col-sm-3" style="margin-left:-25px;">
            <input type="text" class="form-control timepicker-24" id="end" value="" placeholder="Time End">
            <span id="span_time" class="input-group-btn add-on" onclick="moveToTimeBox('end')" style="padding-right: 32px; padding-top: 6px;">
                <button class="btn btn-theme btn-sm" type="button" style="height: 31px;"><i class="fa fa-clock-o"></i></button>
            </span>
        </div>
        <div class="col-sm-1" id="button_adddetail" style="margin-left:-25px; margin-right: 20px; display:block;">
            <a class="btn btn-sm btn-theme02" onclick="addToTable()">Submit</a>
        </div>
        <div class="col-sm-1" id="button_updatedetail" style="margin-left:-25px; margin-right: 20px; display:none;">
            <a class="btn btn-sm btn-theme02" onclick="update_row()">Update</a>
        </div>
        <div class="col-sm-1">
            <a class="btn btn-sm btn-danger" onclick="clearForm()">Clear</a>
        </div>
    </div>
    <br>
    <div id="table_schedule_detail" style="margin-top:30px;">
        <div class="adv-table">
            <table cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-schedule-detail">
                <thead>
                    <th>No</th>
                    <th>Day</th>
                    <th>Time Start</th>
                    <th>Time End</th>
                    <th>Option</th>
                </thead>
                <tbody id="table-body-detail">
                    @isset($details)
                        @php($i=1)
                        @foreach($details as $key)
                            <input type="hidden" id="detail_id{{ $i }}" value="{{ $key->id }}">
                            <tr style="width:100%" id="trow{{ $i }}" class="trow">
                                <td>{{ $i }}</td>
                                <td>{{ $key->get_day->day_name }}</td>
                                <input type="hidden" name="day_id[]" id="day_id{{ $i }}" value="{{ $key->day_id }}">
                                <td>{{ $key->time_start }}</td>
                                <input type="hidden" name="time_start[]" id="time_start{{ $i }}" value="{{ $key->time_start }}">
                                <td>{{ $key->time_end }}</td>
                                <input type="hidden" name="time_end[]" id="time_end{{ $i }}" value="{{ $key->time_end }}">
                                <td class="text-center">
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

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Save @endisset</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function(){
        $(".select2").select2({
            width:'100%',
        });

        $('.timepicker-24').timepicker({
            autoclose: true,
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false
        });
    })

    function moveToTimeBox(id){
        $('#'+id).timepicker({
            autoclose: true,
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false
        });;
    }

    function edit_row(row){
        $("#table-body-detail tr").each(function(){
            var number = $(this).find('td:eq(0)').text();
            if(number == row){
                var detail_id = $('#detail_id'+row).val();
                var day_id = $('#day_id'+row).val();
                var time_start = $(this).find('td:eq(2)').text();
                var time_end = $(this).find('td:eq(3)').text();

                $('#day').val(day_id).change();
                $('#start').val(time_start);
                $('#end').val(time_end)
                $('#detail_id').val(detail_id);
                $('#row_number').val(number);

                document.getElementById('button_adddetail').style.display = 'none';
                document.getElementById('button_updatedetail').style.display = 'block';
                document.getElementById('title-edit').innerHTML ="<strong>Update Detail Schedule : "+row+"</strong>";
            }
        });
    }

    function addToTable(){
        var day = $('#day').val();
        var start = $('#start').val();
        var end = $('#end').val();
        var schedule_id = $('#schedule_id').val();
        var schedule_no = $('#row_number').val();

        daySelect = document.getElementById("day");
        var day_name = daySelect.options[daySelect.selectedIndex].text;

        duplicate = 0;
        $("#table-body-detail tr").each(function(){
            var value_count = $(this).find('td:eq(0)').text();
            if(schedule_no == value_count){
                duplicate++;
            }
        });

        if(day != null && start != "" && end != ""){
            if(schedule_id == undefined){
                var i = $('#table-schedule-detail tbody tr.trow').length + 1;
                var append = '<tr style="width:100%" id="trow'+i+'" class="trow">'
                append += '<td>'+i+'</td>';
                append += '<td>'+day_name+'</td>';
                append += '<input type="hidden" name="day_id[]" id="day_id'+i+'" value="'+day+'">';
                append += '<td>'+start+'</td>';
                append += '<input type="hidden" name="time_start[]" id="time_start'+i+'" value="'+start+'">';
                append += '<td>'+end+'</td>';
                append += '<input type="hidden" name="time_end[]" id="time_end'+i+'" value="'+end+'">';
                append += '<td class="text-center">';
                append += '<a href="javascript:;" type="button" class="btn btn-primary btn-sm" onclick="edit_row('+i+')"> Edit</a> ';
                append += '<a href="javascript:;" type="button" class="btn btn-danger btn-sm" onclick="delete_row('+i+')"> Delete</a></td></tr>';
                $('#table-body-detail').append(append);
            }else{
                $("#table-body-detail tr").each(function(){
                    var value_count = $(this).find('td:eq(0)').text();
                    if(value_count == schedule_no){
                        $(this).find('td:eq(1)').text(day_name);
                        $('#day_id'+value_count).val(day_id);

                        $(this).find('td:eq(2)').text(start);
                        $('#time_start'+value_count).val(start);

                        $(this).find('td:eq(3)').text(end);
                        $('#time_end'+value_count).val(end);
                    }
                });
            }
            clearForm();
        }
        
    }

    function delete_row(id){
        $('#trow'+id).remove();
        correctionNumber();
    }

    function clearForm(){
        $('#detail_id').val("");
        $('#row_number').val("");
        $('#day').val('#').change();
        $('#start').val("");
        $('#end').val("");
        document.getElementById('button_adddetail').style.display = 'block';
        document.getElementById('button_updatedetail').style.display = 'none';
        document.getElementById('title-edit').innerHTML ="<strong>Add Detail Schedule</strong>";
    }

    function correctionNumber(){
        var i = 1;
        $("#table-schedule-detail tr").each(function(){
            $(this).find('td:eq(0)').text(i++);
        })
    }
</script>