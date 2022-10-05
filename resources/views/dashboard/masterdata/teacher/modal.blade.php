<h4 class="mb"><i class="fa fa-angle-right"></i> Teacher Profile</h4>
<form class="form-horizontal style-form" method="post" action="{{ route('teacher.update', ['id' => $data->id]) }}">
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="user_id" id="user_id" value="@isset($data){{ $data->teacher->name }}@endisset" disabled>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Your Title</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="title" id="title" placeholder="Misal: Guru Matematika, Pelatih Renang" value="@isset($data){{ $data->title }}@endisset" disabled>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Description</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Misal: Saya telah berpengalaman dalam mengajar bidang ini selama 5 tahun" disabled>@isset($data->description){{ $data->description }}@endisset</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Your Location</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="location" id="location" placeholder="Misal: Kota Bandung" value="@isset($data){{ $data->location }}@endisset" disabled>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>