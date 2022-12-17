@isset($data)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Payment Account</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('paymentaccount.update', ['id' => $data->id]) }}">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Payment Account</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('paymentaccount.store') }}">
@endif
    @csrf
    <p class="text-muted font-14">
        <label class="col-4 col-form-label">( <span class="text-danger">*</span> ) is a required field</label>
    </p>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Account Type <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="account_type" id="account_type" required>
                <option value="#" selected disabled>-- Choose --</option>
                @foreach ($account_types as $type)
                    @isset($data->account_type)
                        @if($data->account_type == $type->name)
                            <option data-image="{{ asset('dashboard/assets/bank/'.$type->icon) }}" value="{{$type->name}}" selected>{{$type->name}}</option>
                        @else
                            <option data-image="{{ asset('dashboard/assets/bank/'.$type->icon) }}" value="{{$type->name}}">{{$type->name}}</option>
                        @endif
                    @else
                        <option data-image="{{ asset('dashboard/assets/bank/'.$type->icon) }}" value="{{$type->name}}">{{$type->name}}</option>
                    @endisset
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Account Number <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="account_number" id="account_number" value="@isset($data->account_number){{ $data->account_number }}@endisset" required>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Account Name <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="account_name" id="account_name" value="@isset($data->account_name){{ $data->account_name }}@endisset" required>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>

<script>
    $(".select2").select2({
        width:'100%',
        templateResult: formatState,
        templateSelection: formatState
    });
    function formatState (opt) {
        if (!opt.id) {
            return opt.text.toUpperCase();
        }

        var optimage = $(opt.element).attr('data-image');
        console.log(optimage)
        if(!optimage){
        return opt.text.toUpperCase();
        } else {
            var $opt = $(
            '<span><img src="' + optimage + '" width="60px" /> ' + opt.text.toUpperCase() + '</span>'
            );
            return $opt;
        }
    };
</script>
