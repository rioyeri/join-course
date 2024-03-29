<script>
    $(".select2").select2({
        width:'100%',
    });
</script>
@isset($data)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Teacher's Profile @if(array_search("MDTCU",$page))<button class="btn btn-theme btn-round" onclick="edit_data()" id="button-edit"><i id="edit-icon" class="fa fa-pencil"></i> Edit</button>@endif</h4> 
    <form class="form-horizontal style-form" method="post" action="{{ route('teacher.update', ['id' => $data->id]) }}">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Teacher</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('teacher.store') }}">
@endisset
    @csrf
    @isset($data)
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="user_id" id="user_id" value="@isset($data){{ $data->teacher->name }}@endisset" disabled>
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
        <label class="col-sm-3 col-sm-3 control-label">Your Title <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="title" id="title" placeholder="Misal: Guru Matematika, Pelatih Renang" @isset($data->title) value="{{ $data->title }}" @endisset @isset($data)disabled @endisset required>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Description <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Misal: Saya telah berpengalaman dalam mengajar bidang ini selama 5 tahun" @isset($data->description) @endisset @isset($data)disabled @endisset required>@isset($data->description){{ $data->description }}@endisset</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Availability</label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="availability" id="availability" required @isset($data->availability) disabled @endisset>
                @isset($data->availability)
                    <option value="0" @if($data->availability == 0) selected @endif>Offline</option>
                    <option value="1" @if($data->availability == 1) selected @endif>Online</option>
                    <option value="2" @if($data->availability == 2) selected @endif>Online & Offline</option>
                @else
                    <option value="0">Offline</option>
                    <option value="1">Online</option>
                    <option value="2" selected>Online & Offline</option>
                @endisset
            </select>
        </div>
    </div>
    @isset($data)
        <div class="form-group" style="display: none;" id="button-update">
            <div class="col-lg-offset-3 col-lg-9">
                <button class="btn btn-theme" type="submit"> Update </button>
            </div>
        </div>
    @else
        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
                <button class="btn btn-theme" type="submit"> Submit </button>
            </div>
        </div>
    @endisset
</form>

<script>
    function edit_data(){
        var btn = document.getElementById('button-update').style.display;
        if(btn == 'none'){
            document.getElementById('title').removeAttribute('disabled');
            document.getElementById('description').removeAttribute('disabled');
            document.getElementById('availability').removeAttribute('disabled');
            document.getElementById('button-update').style.display = 'block';
        }else{
            document.getElementById('title').setAttribute('disabled',true);
            document.getElementById('description').setAttribute('disabled',true);
            document.getElementById('availability').setAttribute('disabled',true);
            document.getElementById('button-update').style.display = 'none';
        }
        change_button();
    }

    function change_button(){
        var btn_color = document.getElementById('button-edit');
        var btn_icon = document.getElementById('edit-icon');
        if(btn_color.classList.contains("btn-theme")){
            btn_color.classList.remove("btn-theme");
            btn_color.classList.add("btn-danger");
            btn_icon.classList.remove("fa-pencil");
            btn_icon.classList.add("fa-times");
            $('#button-edit').contents().filter(function() {
                return this.nodeType == 3 && this.textContent.trim();
            })[0].textContent = ' Cancel Editing';
        }else{
            btn_color.classList.remove("btn-danger");
            btn_color.classList.add("btn-theme");
            btn_icon.classList.remove("fa-times");
            btn_icon.classList.add("fa-pencil");
            $('#button-edit').contents().filter(function() {
                return this.nodeType == 3 && this.textContent.trim();
            })[0].textContent = ' Edit';
        }
    }
</script>