<style>
    .select2-container--default .select2-selection--multiple{
        width: 100%;
    }
</style>
<script>
    $(".select2").select2({
        width:'100%',
    });
</script>
@isset($data)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Package</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('package.update', ['id' => $data->id]) }}">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Package</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('package.store') }}">
@endif
    @csrf
    <p class="text-muted font-14">
        <label class="col-4 col-form-label">( <span class="text-danger">*</span> ) is a required field</label>
    </p>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Package Name <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="name" id="name" value="@isset($data->name){{ $data->name }}@endisset" required>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Description</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="description" id="description" rows="3">@isset($data->description){{ $data->description }}@endisset</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Grades Level <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <select class="form-control select2 select2-multiple" multiple="multiple" multiple parsley-trigger="change" name="package_grade[]" id="package_grade" data-placeholder="-- Select --">
                @isset($package_grade)
                    @foreach ($grades as $grade)
                        @if(in_array($grade->id, $package_grade))
                            <option value="{{$grade->id}}" selected>{{$grade->name}}</option>
                        @else
                            <option value="{{$grade->id}}" >{{$grade->name}}</option>
                        @endif
                    @endforeach
                @else
                    @foreach ($grades as $grade)
                        <option value="{{$grade->id}}">{{$grade->name}}</option>
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Number of Meet <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="number_meet" id="number_meet" value="@isset($data->number_meet){{ $data->number_meet }}@endisset" required>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Price <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="price" id="price" value="@isset($data->price){{ $data->price }}@endisset" required>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Discount Rate <span class="text-danger">*</span></label>
        <div class="col-sm-2">
            <input type="number" class="form-control" step="0.01" name="discount_rate" id="discount_rate" value="@isset($data->discount_rate){{ $data->discount_rate }}@else{{ 0 }}@endisset" required>
        </div>
        <label class="col-sm-1 control-label" style="margin-left:-20px">%</label>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>