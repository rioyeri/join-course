@isset($data)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Role Data</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('role.update', ['id' => $data->id]) }}">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Role Data</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('role.store') }}">
@endif
    @csrf
    <p class="text-muted font-14">
        <label class="col-4 col-form-label">( <span class="text-danger">*</span> ) is a required field</label>
    </p>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Role Name <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="name" id="name" value="@isset($data){{ $data->name }}@endisset">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Description</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="description" id="description" rows="3">@isset($data->description){{ $data->description }}@endisset</textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>