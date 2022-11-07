@php
    use App\Models\Order;
    if(session('order_id') && session('order_token')){
        $data = Order::getDataOrderforPayment(session('order_id'), session('order_token'));
        $current_order_bill = $data->order_bill;
    }
@endphp

@isset($data->id)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Order Payment</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('orderpayment.update', ['id' => $data->id]) }}" enctype="multipart/form-data">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Order Payment</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('orderpayment.store') }}" enctype="multipart/form-data">
@endif
    @csrf
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Order</label>
        <div class="col-sm-9">
            @if(session('order_id') && session('order_token'))
                <label class="control-label"><strong>{{ $data->order_id }}</strong> <a href="javascript:;" class="btn btn-info btn-round m-5" onclick="printPdf()"><i class="fa fa-file-pdf-o"></i> Download Invoice</a></label>
                <input type="hidden" class="form-control" name="order_id" id="order_id" value="{{ $data->id_order }}" readonly>
                <input type="hidden" id="route" value="{{ route('getInvoice',['order_id' => $data->id_order]) }}">
            @else
                <select class="form-control select2" parsley-trigger="change" name="order_id" id="order_id" onchange="getOrderBill(this.value)">
                    <option value="#" selected disabled>-- Select --</option>
                    @foreach ($orders as $order)
                        @isset($data->order_id)
                            @if($data->order_id == $order->id)
                                <option value="{{$order->id}}" selected>{{$order->order_id}}  ({{ $order->get_student->student->name }} | {{ $order->get_course->name }} | {{ $order->get_teacher->teacher->name }})</option>
                            @else
                                <option value="{{$order->id}}">{{$order->order_id}}  ({{ $order->get_student->student->name }} | {{ $order->get_course->name }} | {{ $order->get_teacher->teacher->name }})</option>
                            @endif
                        @else
                            <option value="{{$order->id}}">{{$order->order_id}}  ({{ $order->get_student->student->name }} | {{ $order->get_course->name }} | {{ $order->get_teacher->teacher->name }})</option>
                        @endisset
                    @endforeach
                </select>
            @endif
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Order Bill</label>
        <div class="col-sm-9">
            <input type="hidden" name="order_bill" id="order_bill" value="@isset($current_order_bill){{ $current_order_bill }}@else{{ 0 }}@endisset">
            <input type="text" class="form-control" id="order_bill_formated" value="@isset($current_order_bill)Rp {{ number_format($current_order_bill,2,",",".") }}@else Rp {{ number_format(0, 2, ",", ".") }}@endisset" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Payment Amount</label>
        <div class="col-sm-9">
            <input type="number" class="form-control" name="payment_amount" id="payment_amount" value="@isset($data->payment_amount){{ $data->payment_amount }}@else{{ 0 }}@endisset" min="0" max="@isset($current_order_bill){{ $current_order_bill }}@endisset">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Payment Method</label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" name="payment_method" id="payment_method">
                <option value="#" disabled selected>-- Select--</option>
                @foreach ($accounts as $account)
                    @isset($data->payment_method)
                        @if($data->payment_method == $account->id)
                            <option value="{{ $account->id }}" selected data-image="{{ asset('dashboard/assets/bank/'.$account->get_bank->icon) }}">{{ $account->account_type }} {{ $account->account_number }} ({{ $account->account_name }})</option>
                        @else
                            <option value="{{ $account->id }}" data-image="{{ asset('dashboard/assets/bank/'.$account->get_bank->icon) }}">{{ $account->account_type }} {{ $account->account_number }} ({{ $account->account_name }})</option>
                        @endif
                    @else
                        <option value="{{ $account->id }}" data-image="{{ asset('dashboard/assets/bank/'.$account->get_bank->icon) }}">{{ $account->account_type }} {{ $account->account_number }} ({{ $account->account_name }})</option>
                    @endisset
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3 col-sm-3">Payment Evidence</label>
        <div class="col-md-9">
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                    <img src="@isset($data->payment_evidence)dashboard/assets/payment/{{ $data->get_order->order_id }}/{{ $data->payment_evidence }} @endisset" alt="" />
                </div>
                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
                </div>
                <div>
                    <span class="btn btn-theme02 btn-file">
                        <span class="fileupload-new"><i class="fa fa-paperclip"></i> Select image</span>
                        <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                        <input type="file" class="default" name="payment_evidence" id="payment_evidence"/>
                    </span>
                    <a href="javascript:;" class="btn btn-theme04 fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> Remove</a>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit">@isset($data->id) Update @else Submit @endisset</button>
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
        if(!optimage){
        return opt.text.toUpperCase();
        } else {
            var $opt = $(
            '<span><img src="' + optimage + '" width="60px" /> ' + opt.text.toUpperCase() + '</span>'
            );
            return $opt;
        }
    };

    // Date Picker
    jQuery('.datepicker').datepicker({
        todayHighlight: true,
        autoclose: true
    });

    function moveToBox(){
        $('#course_start').data("datepicker").show();
    }

    function getOrderBill(id){
        $.ajax({
            url : "{{route('getOrderBill')}}",
            type : "get",
            dataType: 'json',
            data : {
                id:id,
            },
        }).done(function (data) {
            $('#order_bill').val(data);
            $('#order_bill_formated').val("Rp "+number_format(data, 2, ",", "."));
            $('#payment_amount').attr("max", data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function printPdf(){
        windowUrl = $('#route').val();
        console.log(windowUrl)
        windowName = "Invoice";
        var printWindow = window.open(windowUrl, windowName, 'left=50000,top=50000,width=0,height=0');
        printWindow.focus();
        setTimeout(function(){ printWindow.close(); }, 3000);
        printWindow.print();
    }
</script>