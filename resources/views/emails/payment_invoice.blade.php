@component('mail::message')
Your Payment Successfully Confirmed.

Order ID : {{ $data_order->order_id }}<br><br>
Payment Invoice ID : {{ $data_payment->invoice_id }}<br>
Your Payment Amount : Rp {{ number_format($data_payment->payment_amount,2,",",".") }}<br>
Destination Account : {{ $data_payment->get_paymentmethod->account_type }} - {{ $data_payment->get_paymentmethod->account_number }} - {{ $data_payment->get_paymentmethod->account_name }}<br>
Time : {{ $data_payment->get_paymentmethod->created_at->format('Y-m-d H:i') }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
