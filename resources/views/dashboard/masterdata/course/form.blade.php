@isset($data)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Course</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('course.update', ['id' => $data->id]) }}">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Course</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('course.store') }}">
@endif
    @csrf
    <p class="text-muted font-14">
        <label class="col-4 col-form-label">( <span class="text-danger">*</span> ) is a required field</label>
    </p>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Subject Name <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="name" id="name" value="@isset($data){{ $data->name }}@endisset" required>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Subject's Topic</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="topic" id="topic" value="@isset($data){{ $data->topic }}@endisset">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Subject's Description</label>
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