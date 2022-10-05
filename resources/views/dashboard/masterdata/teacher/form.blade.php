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
        <label class="col-sm-3 col-sm-3 control-label">Your Title</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="title" id="title" placeholder="Misal: Guru Matematika, Pelatih Renang" @isset($data) value="{{ $data->title }}" disabled @endisset>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Description</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Misal: Saya telah berpengalaman dalam mengajar bidang ini selama 5 tahun" @isset($data->description) disabled @endisset>@isset($data->description){{ $data->description }}@endisset</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Your Location</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="location" id="location" placeholder="Misal: Kota Bandung" @isset($data) value="{{ $data->location }}" disabled @endisset>
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
            document.getElementById('location').removeAttribute('disabled');
            document.getElementById('button-update').style.display = 'block';
        }else{
            document.getElementById('title').setAttribute('disabled',true);
            document.getElementById('description').setAttribute('disabled',true);
            document.getElementById('location').setAttribute('disabled',true);
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