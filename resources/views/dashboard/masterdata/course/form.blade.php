@isset($data)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Course</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('course.update', ['id' => $data->id]) }}">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Course</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('course.store') }}">
@endif
    @csrf
    <div class="form-group">
        <label class="col-sm-2 col-sm-2 control-label">Subject Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" id="name" value="@isset($data){{ $data->name }}@endisset">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 col-sm-2 control-label">Subject's Topic</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="topic" id="topic" value="@isset($data){{ $data->topic }}@endisset">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 col-sm-2 control-label">Subject's Description</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="description" id="description" value="@isset($data){{ $data->description }}@endisset">
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>