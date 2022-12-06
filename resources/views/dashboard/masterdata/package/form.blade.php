@isset($data)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Package</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('package.update', ['id' => $data->id]) }}">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Package</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('package.store') }}">
@endif
    @csrf
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Package Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="name" id="name" value="@isset($data->name){{ $data->name }}@endisset">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Description</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="description" id="description" rows="3">@isset($data->description){{ $data->description }}@endisset</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Number of Meet</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="number_meet" id="number_meet" value="@isset($data->number_meet){{ $data->number_meet }}@endisset">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Price</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="price" id="price" value="@isset($data->price){{ $data->price }}@endisset">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Discount Rate</label>
        <div class="col-sm-2">
            <input type="number" class="form-control" step="0.01" name="discount_rate" id="discount_rate" value="@isset($data->discount_rate){{ $data->discount_rate }}@else{{ 0 }}@endisset">
        </div>
        <label class="col-sm-1 control-label" style="margin-left:-20px">%</label>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>