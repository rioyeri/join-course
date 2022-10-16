<h4 class="mb"><i class="fa fa-angle-right"></i> Update Data {{ $data->title }}</h4>
<form class="form-horizontal style-form" method="post" action="{{ route('companyprofile.update', ['id' => $data->id]) }}">
    {{ method_field('PUT') }}
    @csrf
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Content</label>
        <div class="col-sm-9">
            @if($data->id == 2)
                <textarea class="form-control" name="content" id="content" rows="3">@isset($data->content){{ $data->content }}@endisset</textarea>
            @else
                <input type="text" class="form-control" name="content" id="content" value="@isset($data->content){{ $data->content }}@endisset">
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit"> Update</button>
        </div>
    </div>
</form>