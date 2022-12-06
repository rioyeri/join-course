<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

use App\Models\Order;
use App\Models\OrderPayment;

class PaymentInvoiceMail extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data_payment = OrderPayment::where('id', $this->id)->first();
        $data_order = Order::where('id', $data_payment->order_id)->first();

        return $this->from('admin@nadasederhana.com', 'Admin Flash Academia')->subject('[NO REPLY] Your Payment is Confirmed in Flash Academia')->markdown('emails.payment_invoice', compact('data_order', 'data_payment'));
        // return $this->from('admin@nadasederhana.com', 'Admin Flash Academia')->subject('[NO REPLY] Your Payment is Confirmed in Flash Academia')->markdown('emails.payment_invoice');
    }
}
