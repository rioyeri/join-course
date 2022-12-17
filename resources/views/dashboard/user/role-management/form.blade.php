<script>
    $(".select2").select2({
        width:'100%',
    });
</script>

@isset($data->id)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Users Role Data</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('rolemapping.update', ['id' => $data->id]) }}">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Users Role Data</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('rolemapping.store') }}">
@endif
    @csrf
    <p class="text-muted font-14">
        <label class="col-4 col-form-label">( <span class="text-danger">*</span> ) is a required field</label>
    </p>
    @isset($data)
        <div class="form-group">
            <label class="col-sm-3 col-sm-3 control-label">User Name <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="username" id="username" value="@isset($data->username){{ $data->user->name }}@endisset" disabled>
            </div>
        </div>
    @else
        <div class="form-group">
            <label class="col-sm-3 col-sm-3 control-label">Choose User <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <select class="form-control select2" parsley-trigger="change" name="username" id="username" required>
                    <option value="#" selected disabled>-- Choose --</option>
                    @foreach ($users as $user)
                        <option value="{{$user->username}}">{{$user->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endisset
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Role <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="role_id" id="role_id" required>
                <option value="#" selected disabled>-- Choose --</option>
                @foreach ($roles as $role)
                    @isset($data->role_id)
                        @if ($data->role_id == $role->id)
                            <option value="{{$role->id}}" selected>{{$role->name}}</option>
                        @else
                            <option value="{{$role->id}}">{{$role->name}}</option>
                        @endif
                    @else
                        <option value="{{$role->id}}">{{$role->name}}</option>
                    @endisset
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>