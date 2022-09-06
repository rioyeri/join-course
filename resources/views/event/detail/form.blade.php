<div class="card-box">
    <h4 class="m-t-0 header-title">Create New Event</h4>
    <div class="row">
        <div class="col-12">
            <div class="p-20">
                <div class="form-group row">
                    <label class="col-2 col-form-label">Event's Name<span class="text-danger">*</span></label>
                    <div class="col-10">
                        <input type="text" class="form-control" parsley-trigger="change" required name="name" id="name" placeholder="Wedding Ceremony, or Party, etc">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Event's Date<span class="text-danger">*</span></label>
                    <div class="col-10">
                        <input type="text" class="form-control datepicker" parsley-trigger="change" required name="date" id="date" data-date-format='yyyy-mm-dd' placeholder="YYYY-MM-DD">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Event's Start Time<span class="text-danger">*</span></label>
                    <div class="col-10">
                        <input type="time" class="form-control" parsley-trigger="change" required name="time_start" id="time_start" placeholder="Format : 24 Hours">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Event's End Time</label>
                    <div class="col-10">
                        <input type="time" class="form-control" parsley-trigger="change" name="time_end" id="time_end" placeholder="Format : 24 Hours">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Event's Time Zone<span class="text-danger">*</span></label>
                    <div class="col-10">
                        <input type="text" class="form-control" parsley-trigger="change" required name="time_zone" id="time_zone" placeholder="WIB/WITA/WIT or GMT Format">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Event's Venue<span class="text-danger">*</span></label>
                    <div class="col-10">
                        <input type="text" class="form-control" required name="location" id="location" placeholder="Name of Place">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Venue's Address<span class="text-danger">*</span></label>
                    <div class="col-10">
                        <input type="text" class="form-control" parsley-trigger="change" required name="address" id="address" placeholder="Address of Place">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Venue's Map Link</label>
                    <div class="col-10">
                        <input type="text" class="form-control" parsley-trigger="change" name="location_url" id="location_url" placeholder="You can drop a Google Maps URL, Waze URL, etc">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Event's Streaming Channel</label>
                    <div class="col-10">
                        <input type="text" class="form-control" parsley-trigger="change" name="streaming_channel" id="streaming_channel" placeholder="Channel Name and Platform Name">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Link Streaming</label>
                    <div class="col-10">
                        <input type="text" class="form-control" parsley-trigger="change" name="streaming_link" id="streaming_link" placeholder="You can drop a Youtube Channel URL or others platform URL">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group text-right m-b-0">
        <input type="hidden" id="event_id" value="0">
        <a href="javascript:;" class="btn btn-primary waves-effect waves-light" onclick="addToTable()">Submit</a>
    </div>
</div>

<script>
    $(".datepicker").datepicker()
    // Select2
    $(".select2").select2({
        templateResult: formatState,
        templateSelection: formatState
    });

    // Dropfiy
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

    function addToTable(){
        name = $('#name').val();
        date = $('#date').val();
        time_start = $('#time_start').val();
        time_end = $('#time_end').val();
        time_zone = $('#time_zone').val();
        venue = $('#location').val();
        address = $('#address').val();
        location_url = $('#location_url').val();
        streaming_channel = $('#streaming_channel').val();
        streaming_link = $('#streaming_link').val();
        event_id = $('#event_id').val();
        count = $('#responsive-datatable tbody tr.trow_event_detail').length;

        if(event_id == 0){
            $.ajax({
                url : "{{route('addEventToTable')}}",
                type : "get",
                dataType: 'json',
                data:{
                    "name":name,
                    "date":date,
                    "time_start":time_start,
                    "time_end":time_end,
                    "time_zone":time_zone,
                    "location":venue,
                    "address":address,
                    "location_url":location_url,
                    "streaming_channel":streaming_channel,
                    "streaming_link":streaming_link,
                    "count":count,
                },
                
            }).done(function (data) {
                $('#tbody-events').append(data.append);
                resetAll();
            }).fail(function (msg) {
                alert('Gagal menampilkan data, silahkan refresh halaman.');
            });
        }else{
            $("#tbody-events tr").each(function(){
                var value_number = $(this).find('td:eq(0)').text();
                
                if(value_number == event_id){
                    $('#event_name'+value_number).val(name);
                    $(this).find('td:eq(1)').text(name);
                    $('#event_date'+value_number).val(date);
                    $(this).find('td:eq(2)').text(date);
                    $('#event_time_start'+value_number).val(time_start);
                    $(this).find('td:eq(3)').text(time_start);
                    $('#event_time_end'+value_number).val(time_end);
                    $(this).find('td:eq(4)').text(time_end);
                    $('#event_time_zone'+value_number).val(time_zone);
                    $(this).find('td:eq(5)').text(time_zone);
                    $('#event_location'+value_number).val(venue);
                    $(this).find('td:eq(6)').text(venue);
                    $('#event_location_address'+value_number).val(address);
                    $(this).find('td:eq(7)').text(address);
                    $('#event_location_url'+value_number).val(location_url);
                    $(this).find('td:eq(8)').text(location_url);
                    $('#event_streaming_channel'+value_number).val(streaming_channel);
                    $(this).find('td:eq(9)').text(streaming_channel);
                    $('#event_streaming_link'+value_number).val(streaming_link);
                    $(this).find('td:eq(10)').text(streaming_link);
                    resetAll();
                    $('#event_id').val(0);
                }
            });
        }
    }

    function resetAll(){
        $('#name').val("");
        $('#date').val("");
        $('#time_start').val("");
        $('#time_end').val("");
        $('#time_zone').val("");
        $('#location').val("");
        $('#address').val("");
        $('#location_url').val("");
        $('#streaming_channel').val("");
        $('#streaming_link').val("");
        $('#form-eventdetail').html("");
        element = document.getElementById("responsive-datatable");
        element.scrollIntoView();
    }
</script>
