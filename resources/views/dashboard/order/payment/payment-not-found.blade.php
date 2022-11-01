@extends('dashboard.layout.single-page')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('title')
    Order Payment
@endsection

@section('content')
<div class="content-panel-single">
    <h4 class="mb"><i class="fa fa-angle-right"></i> Order ID : {{ $order_id }} is Invalid</h4>
    <p>Please check your Order ID or request new Invoice</p>
</div>
@endsection