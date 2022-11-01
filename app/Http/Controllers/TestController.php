<?php

namespace App\Http\Controllers;

use App\Models\BootstrapIcon;
use Illuminate\Http\Request;

use App\Models\Order;

class TestController extends Controller
{
    public function index(){
        // echo Order::generateInvoiceID(4);
        // die;
        $business_details = array(
            "name" => "businessname",
            "id" => "adafasf",
            "phone" => "081221231231",
            "location" => "Jl. afdasfashha asdfasdfsa",
            "zip" => "55445",
            "city" => "Jakarta",
            "country" => "Indonesia",
        );

        $customer_details = array(
            "name" => "customername",
            "id" => "adafasf",
            "phone" => "081221231231",
            "location" => "Jl. afdasfashha asdfasdfsa",
            "zip" => "55445",
            "city" => "Jakarta",
            "country" => "Indonesia",
        );
        $item = array(
            "id" => 123,
            "name" => "name",
            "price" => "234324234",
            "amount" => 1,
            "totalPrice" => "2342342",
        );

        $items = array();

        for($i=0; $i<3; $i++){
            array_push($items, $item);
        }

        $tax = array(
            "name" => "name",
            "tax_type" => "percentage",
            "tax" => 11,
            "taxPrice" => 1213,
        );
        $tax_rates = array();
        for($i=0; $i<2; $i++){
            array_push($tax_rates, $tax);
        }
        $data = array(
            "duplicate_header" => FALSE,
            "name" => "Flash Academia",
            "logo_height" => "50px",
            "logo" => "",
            "date" => "2022-10-10",
            "due_date" => "2022-10-10",
            "number" => "1111113323131231",
            "business_details" => $business_details,
            "customer_details" => $customer_details,
            "items" => $items,
            "notes" => "aslndfaslfkasnfas",
            "footnote" => "kkkkkkkkkkk",
            "tax_rates" => $tax_rates,
            "subTotalPrice" => "342242",
            "totalPrice" => "123131",
        );

        $invoice = json_decode(json_encode($data),FALSE);
        // echo "<pre>";
        // print_r($invoice);
        // die;
        return view('dashboard.order.order.invoice', compact('invoice'));
    }
}
