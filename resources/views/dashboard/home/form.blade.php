<script>
    $(".select2").select2({
        width:'100%',
    });
</script>
@php
    use App\Models\OrderDetail;
@endphp
<form class="form-horizontal style-form" method="post" action="{{ route('home.update', ['id' => $data->id]) }}">
    {{ method_field('PUT') }}
    @csrf
    <h4 class="mb"><i class="fa fa-angle-right"></i> Course Schedule for Order ID : <strong>#{{ $data->order_id }}</strong> (<span id="package_number_meet">{{ $package_number_meet }}</span> <span id="order_type">{{ $order_type }}</span> Meets)</h4>
    <input type="hidden" id="order_id" value="{{ $data->order_id }}">
    @if(array_search("DSSCU",$submoduls))
    <h5 id="row_title" style="display:none;">Update Schedule no : <strong><span id="row_number"></span></strong> <a class="btn btn-xs btn-danger" href="javascript:;" onclick="resetForm()"><i class="fa fa-eraser"></i> Cancel Editing</a></h5>

    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Time</label>
        <div class="col-sm-5">
            <input type="text" data-date-format='yyyy-mm-dd' class="form-control datepicker" id="schedule_date" value="">
            <span id="span_date" class="input-group-btn add-on" onclick="moveToDateBox()" style="padding-right: 39px;">
                <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
            </span>
        </div>
        <div class="col-sm-4">
            <input type="text" class="form-control timepicker-24" id="schedule_time" value="">
            <span id="span_time" class="input-group-btn add-on" onclick="moveToTimeBox()" style="padding-right: 38px;">
                <button class="btn btn-theme" type="button"><i class="fa fa-clock-o"></i></button>
            </span>
        </div>
    </div>
    @if($order_type == "online")
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Zoom Link</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="link_zoom" value="">
        </div>
    </div>
    @endif
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Document Link</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="link_drive" value="">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <a class="btn btn-md btn-theme" onclick="addToTable()" id="save_button"><i class="fa fa-plus"></i> Add</a>
        </div>
    </div>
    @endif

    <div id="table_schedule" @if(count($exist_schedules) == 0) style="display:none" @endif>
        <div class="adv-table">
            <table cellpadding="0" cellspacing="0" class="table table-bordered dt-responsive wrap" id="table-schedule">
                <thead>
                    <th>No</th>
                    <th>Time</th>
                    @if($order_type == "online")
                        <th>Zoom Link</th>
                    @endif
                    <th>Documents Link</th>
                    @if(session('role_id') == 1 || session('role_id') == 2 || session('role_id') == 3)
                        <th>Share Invitation</th>
                    @endif
                    @if(array_search("DSSCU",$submoduls) && array_search("DSSCD",$submoduls))
                    <th>Options</th>
                    @endif
                </thead>
                <tbody id="table-body-schedule">
                    @php($i=1)
                    @foreach($exist_schedules as $key)
                        <tr style="width:100%" id="trow{{ $i }}" class="trow">
                            <td style="width:5%">{{ $i }}</td>
                            <td style="width:15%">{{ date_format(date_create($key->schedule_time), "D, d-m-Y H:i:s") }}</td>
                            <input type="hidden" name="schedule_datetime[]" id="schedule_datetime{{ $i }}" value="{{ $key->schedule_time }}">
                            @if($order_type == "online")
                            <td style="width:25%"><a href="{{ $key->link_zoom }}" target="_blank" id="alink_zoom{{ $i }}">{{ $key->link_zoom }}</a></td>
                            <input type="hidden" name="link_zoom[]" id="link_zoom{{ $i }}" value="{{ $key->link_zoom }}">
                            @endif
                            <td style="width:25%"><a href="{{ $key->link_drive }}" target="_blank" id="alink_drive{{ $i }}">{{ $key->link_drive }}</a></td>
                            <input type="hidden" name="link_drive[]" id="link_drive{{ $i }}" value="{{ $key->link_drive }}">
                            @if(session('role_id') == 1 || session('role_id') == 2 || session('role_id') == 3)
                            <td style="width:10%">
                                @if(OrderDetail::getWALink($key->id,'student') != "")
                                    @if (OrderDetail::getWALink($key->id,'student') == "no_link")
                                        <a class="btn btn-round btn-theme btn-sm" style="margin:1px" disabled="disabled" title="There is no Zoom's Link"><i class="fa fa-whatsapp"></i> Student</a>
                                    @else
                                        <a href="{{ OrderDetail::getWALink($key->id,'student') }}" class="btn btn-round btn-theme btn-sm" target="_blank" style="margin:1px;"><i class="fa fa-whatsapp"></i> Student</a>
                                    @endif

                                @else
                                    @php($copy_text = OrderDetail::getWAText($key->id,'student'))
                                    <a href="javascript:;" class="btn btn-round btn-theme02 btn-sm" id="copytextteacherbtn{{ $i }}" data-toggle="popover" onclick="copyText({{ $key->id }}, '{{ $copy_text }}')"><i class="fa fa-copy"></i> Student</a>
                                @endif
                                @if(OrderDetail::getWALink($key->id,'teacher') != "")
                                    @if (OrderDetail::getWALink($key->id,'teacher') == "no_link")
                                        <a class="btn btn-round btn-theme btn-sm" style="margin:1px" disabled="disabled" title="There is no Zoom's Link"><i class="fa fa-whatsapp"></i> Teacher</a>
                                    @else
                                        <a href="{{ OrderDetail::getWALink($key->id,'teacher') }}" class="btn btn-round btn-theme btn-sm" target="_blank" style="margin:1px;"><i class="fa fa-whatsapp"></i> Teacher</a>
                                    @endif
                                @else
                                    @php($copy_text = OrderDetail::getWAText($key->id,'teacher'))
                                    <a href="javascript:;" class="btn btn-round btn-theme02 btn-sm" id="copytextteacherbtn{{ $i }}" data-toggle="popover" onclick="copyText({{ $key->id }}, '{{ $copy_text }}')"><i class="fa fa-copy"></i> Teacher</a>
                                @endif
                            </td>
                            @endif
                            @if(array_search("DSSCU", $submoduls) || array_search("DSSCD", $submoduls))
                            <td class="text-center" style="width:20%">
                                @if(array_search("DSSCU", $submoduls))
                                <a href="javascript:;" type="button" class="btn btn-primary btn-trans waves-effect w-xs waves-danger m-b-5" onclick="edit_row({{ $i }})">Edit</a>
                                @endif
                                @if(array_search("DSSCD", $submoduls))
                                <a href="javascript:;" type="button" class="btn btn-danger btn-trans waves-effect w-xs waves-danger m-b-5" onclick="deleteItem({{ $i }})">Delete</a>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @php($i++)
                    @endforeach        
                </tbody>
            </table>
        </div>
        @if(array_search("DSSCU", $submoduls))
        <div class="form-group">
            <div class="text-right m-20">
                <button class="btn btn-theme" type="submit"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
        @endif
    </div>
</form>

<script>
    // Date Picker
    jQuery('.datepicker').datepicker({
        todayHighlight: true,
        autoclose: true,
        startDate: '+1d',
    });

    $('.timepicker-24').timepicker({
        autoclose: true,
        minuteStep: 1,
        showSeconds: true,
        showMeridian: false
    });

    function moveToDateBox(){
        $('#schedule_date').data("datepicker").show();
    }

    function moveToTimeBox(){
        $('#schedule_time').data("timepicker-24").show();
    }

    function addToTable(){
        var style_display = document.getElementById('table_schedule').style.display;    
        if(style_display == 'none'){
            document.getElementById('table_schedule').style.display = 'block';
        }

        var order_id = $('#order_id').val();
        var package_meet_number = $('#package_number_meet').text();
        var order_type = $('#order_type').text();
        var schedule_no = $('#row_number').text();
        var date = $('#schedule_date').val();
        var time = $('#schedule_time').val();
        var schedule = date+" "+time;
        var format_schedule = moment(schedule, "YYYY-MM-DD HH:mm:ss").format('ddd, DD-MM-YYYY, HH:mm:ss');

        var link_zoom = $('#link_zoom').val();
        var link_drive = $('#link_drive').val();

        count = $('#table-schedule tbody tr.trow').length+1;

        duplicate = 0;
        $("#table-body-schedule tr").each(function(){
            var value_count = $(this).find('td:eq(0)').text();
            console.log(schedule_no, value_count);
            if(schedule_no == value_count){
                duplicate++;
            }
        });

        if(duplicate == 0){
            if(count <= package_meet_number){
                if(order_type == "online"){
                    var append = '<tr style="width:100%" id="trow'+count+'" class="trow"><td style="width:5%">'+count+'</td><td style="width:15%">'+format_schedule+'</td><input type="hidden" name="schedule_datetime[]" id="schedule_datetime'+count+'" value="'+schedule+'"><td style="width:25%"><a href="'+link_zoom+'" target="_blank" id="alink_zoom'+count+'">'+link_zoom+'</a></td><input type="hidden" name="link_zoom[]" id="link_zoom'+count+'" value="'+link_zoom+'"><td style="width:25%"><a href="'+link_drive+'" target="_blank" id="alink_drive'+count+'">'+link_drive+'</a></td><input type="hidden" name="link_drive[]" id="link_drive'+count+'" value="'+link_drive+'"><td style="width:10%">This schedule need to be saved</td><td class="text-center" style="width:20%"><a href="javascript:;" type="button" class="btn btn-primary btn-trans waves-effect w-xs waves-danger m-b-5" onclick="edit_row('+count+')">Edit</a> <a href="javascript:;" type="button" class="btn btn-danger btn-trans waves-effect w-xs waves-danger m-b-5" onclick="deleteItem('+count+')" >Delete</a></td></tr>';
                }else{
                    var append = '<tr style="width:100%" id="trow'+count+'" class="trow"><td style="width:5%">'+count+'</td><td style="width:15%">'+format_schedule+'</td><input type="hidden" name="schedule_datetime[]" id="schedule_datetime'+count+'" value="'+schedule+'"><td style="width:25%"><a href="'+link_drive+'" target="_blank" id="alink_drive'+count+'">'+link_drive+'</a></td><input type="hidden" name="link_drive[]" id="link_drive'+count+'" value="'+link_drive+'"><td>This schedule need to be saved</td><td class="text-center" style="width:20%"><a href="javascript:;" type="button" class="btn btn-primary btn-trans waves-effect w-xs waves-danger m-b-5" onclick="edit_row('+count+')">Edit</a> <a href="javascript:;" type="button" class="btn btn-danger btn-trans waves-effect w-xs waves-danger m-b-5" onclick="deleteItem('+count+')" >Delete</a></td></tr>';
                }
                $('#table-body-schedule').append(append);
            }else{
                swal(
                    'Failed',
                    'Your Package just have limited meeting schedule',
                    'error'
                );
            }
        }else{
            $("#table-body-schedule tr").each(function(){
                var value_count = $(this).find('td:eq(0)').text();

                if(value_count == schedule_no){
                    $(this).find('td:eq(1)').text(schedule);
                    $('#schedule_datetime'+value_count).val(schedule);

                    btn_drive = '<a href="'+link_drive+'" target="_blank" id="alink_drive'+value_count+'">'+link_drive+'</a>';
                    if(order_type == "online"){
                        btn_zoom = '<a href="'+link_zoom+'" target="_blank" id="alink_zoom'+value_count+'">'+link_zoom+'</a>';
                        $(this).find('td:eq(2)').html(btn_zoom);
                        $('#link_zoom'+value_count).val(link_zoom);

                        $(this).find('td:eq(3)').html(btn_drive);
                        $('#link_drive'+value_count).val(link_drive);
                    }else{
                        $(this).find('td:eq(2)').html(btn_drive);
                        $('#link_drive'+value_count).val(link_drive);
                    }
                }
            });
        }
        resetForm();
    }

    function edit_row(row){
        $("#table-body-schedule tr").each(function(){
            var number = $(this).find('td:eq(0)').text();
            if(number == row){
                var datetime = $(this).find('td:eq(1)').text();
                var link_zoom = $('#alink_zoom'+number).text();
                var link_drive = $('#alink_drive'+number).text();

                var date = moment(datetime, "ddd, DD-MM-YYYY, HH:mm:ss").format('YYYY-MM-DD');
                var time = moment(datetime, "ddd, DD-MM-YYYY, HH:mm:ss").format('HH:mm:ss');
                console.log(date,time);

                $('#schedule_date').val(date);
                $('#schedule_time').val(time);
                $('#link_zoom').val(link_zoom);
                $('#link_drive').val(link_drive);

                document.getElementById('row_number').textContent = row;
                document.getElementById('row_title').style.display = 'block';
                document.getElementById('save_button').innerHTML = '<i class="fa fa-pencil"></i> Update';
            }
        });

    }

    function resetForm(){
        $('#schedule_date').val("");
        $('#schedule_time').val("");
        $('#link_zoom').val("");
        $('#link_drive').val("");
        $('#row_number').html("");
        document.getElementById('row_title').style.display = "none";
    }

    function deleteItem(id){
        $('#trow'+id).remove();
        correctionNumber();
    }

    function correctionNumber(){
        var i = 1;
        $("#table-body-schedule tr").each(function(){
            $(this).find('td:eq(0)').text(i++);
        })
    }

    function copyText(i,text) {
        /* Copy the text inside the text field */
        navigator.clipboard.writeText(text);

        $('#copytextbtn'+i).popover({content: "Whatsapp Text copied to clipboard",animation: true});

        // Set the date we're counting down to (dalam kasus ini ditampilkan selama 5 detik)
        var hide = new Date(new Date().getTime() + 5000).getTime();

        var x = setInterval(function() {
            // code goes here

            // Get today's date and time
            var now = new Date().getTime();
            
            var distance = hide - now;

            if(distance < 0){
                clearInterval(x);
                $('[data-toggle="popover"]').popover("hide");
            }
        }, 1000)
        /* Alert the copied text */
    }
</script>