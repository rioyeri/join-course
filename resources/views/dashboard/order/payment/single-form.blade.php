@extends('dashboard.layout.single-page')

@section('css')
<!-- Select2 -->
<link href="{{ asset('dashboard/additionalplugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Sweet Alert css -->
<link href="{{ asset('dashboard/additionalplugins/sweet-alert/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Magnific Pop-up-->
<link rel="stylesheet" href="{{ asset('dashboard/additionalplugins/magnific-popup/dist/magnific-popup.css') }}"/>
<!-- File Upload-->
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/lib/bootstrap-fileupload/bootstrap-fileupload.css') }}" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('title')
    Order Payment
@endsection

@section('content')
<div class="content-panel-single">
    <h4 class="mb"><i class="fa fa-angle-right"></i> Upload Payment Evidence for {{ $data->order_id }}</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('orderpayment.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="order_id" id="order_id" value="{{ $data->id }}">
        <input type="hidden" name="order_token" id="order_token" value="{{ $data->order_token }}">
        <div class="form-group">
            <label class="col-sm-3 col-sm-3 control-label">Order Bill</label>
            <div class="col-sm-9">
                <input type="hidden" name="order_bill" id="order_bill" value="@isset($data->order_bill){{ $data->order_bill }}@else{{ 0 }}@endisset">
                <input type="text" class="form-control" id="order_bill_formated" value="@isset($data->order_bill)Rp {{ number_format($data->order_bill,2,",",".") }}@else{{ 0 }}@endisset" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 col-sm-3 control-label">Payment Amount</label>
            <div class="col-sm-9">
                <input type="number" class="form-control" name="payment_amount" id="payment_amount" value="0" min="0">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 col-sm-3 control-label">Payment Method</label>
            <div class="col-sm-9">
                <select class="form-control select2" parsley-trigger="change" name="payment_method" id="payment_method">
                    <option value="#" disabled selected>-- Select--</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}" data-image="{{ asset('dashboard/assets/bank/'.$account->get_bank->icon) }}">{{ $account->account_type }} {{ $account->account_number }} ({{ $account->account_name }})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3 col-sm-3">Payment Evidence</label>
            <div class="col-md-9">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                        <img src="" alt="" />
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
                <button class="btn btn-theme" type="submit">Submit</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')
    <!-- Select2 -->
    <script src="{{ asset('dashboard/additionalplugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
    <!-- Sweet Alert Js  -->
    <script src="{{ asset('dashboard/additionalplugins/sweet-alert/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('dashboard/additionalpages/jquery.sweet-alert.init.js') }}"></script>
    <!-- File Upload -->
    <script type="text/javascript" src="{{ asset('dashboard/lib/bootstrap-fileupload/bootstrap-fileupload.js') }}"></script>
    <!-- Magnific popup -->
    <script type="text/javascript" src="{{ asset('dashboard/additionalplugins/magnific-popup/dist/jquery.magnific-popup.min.js') }}"></script>
@endsection

@section('script-js')
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
</script>
@endsection