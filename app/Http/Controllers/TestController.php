<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Product;
use App\Coa;
use App\Jurnal;
use App\Salary;
use App\SalaryDet;
use App\Employee;
use App\Sales;
use App\Koordinator;
use App\Perusahaan;
use App\SubKoordinator;
use App\PurchaseDetail;
use App\Purchase;
use App\Customer;
use App\SalesDet;
use App\DeliveryOrder;
use App\DeliveryDetail;
use App\Role;
use App\ReceiveDet;
use GuzzleHttp\Client;

use Carbon\Carbon;

class TestController extends Controller
{
    public function index(){
        $sales = Sales::all();
        foreach($sales as $sale){
            $count = SalesDet::where('trx_id', $sale->id)->count();
            if($count == 0){
                echo $sale->id." ".$sale->jurnal_id."<br>";
            }else{
                Sales::recycleSales($sale->id);
            }
        }
    }

    public function indexmbuh(){
        $no = 1;
        $count = 0;
        $receive = ReceiveDet::where('trx_id', 116)->get();
        foreach($receive as $rec){
            $data = PurchaseDetail::where('trx_id', $rec->trx_id)->where('prod_id', $rec->prod_id)->where('qty', $rec->qty)->first();
            // echo "<pre>";
            // print_r($data);
            if($rec->qty != $data['qty']){
                $rd = ReceiveDet::where('trx_id', $rec->trx_id)->where('prod_id', $rec->prod_id)->sum('qty');
                $pd = PurchaseDetail::where('trx_id', $rec->trx_id)->where('prod_id', $rec->prod_id)->sum('qty');
                $cr = ReceiveDet::where('trx_id', $rec->trx_id)->where('prod_id', $rec->prod_id)->count();
                $cp = PurchaseDetail::where('trx_id', $rec->trx_id)->where('prod_id', $rec->prod_id)->count();

                if($rd != $pd){
                    echo $no++.". ".$rec->id_jurnal." PO ID:".$rec->trx_id." ".$rec->prod_id.", qty:".$rec->qty.", qty total:".$rd.", count receive:".$cr." - ".$data['id'].", qty:".$data['qty'].", qty total:".$pd.", count po:".$cp."<br>";
                }else{
                    if($cp == 1){
                        $purdet = PurchaseDetail::where('trx_id', $rec->trx_id)->where('prod_id', $rec->prod_id)->first();
                        $receivedet = ReceiveDet::where('id', $rec->id)->first();
                        $receivedet->purchasedetail_id = $purdet['id'];
                        $receivedet->save();
                    }elseif($cp > 1){
                        $purdet = PurchaseDetail::where('trx_id', $rec->trx_id)->where('prod_id', $rec->prod_id)->first();
                        $receivedet = ReceiveDet::where('id', $rec->id)->first();
                        $receivedet->purchasedetail_id = $purdet['id'];
                        $receivedet->save();
                        // $purchasedetail = PurchaseDetail::where('trx_id', $rec->trx_id)->where('prod_id',$rec->prod_id)->get();
                        // foreach($purchasedetail as $purdet){
                        //     $new = new ReceiveDet(array(
                        //         'trx_id' => $purdet->trx_id,
                        //         'prod_id' => $purdet->prod_id,
                        //         'purchasedetail_id' => $purdet->id,
                        //         'qty' => $purdet->qty,
                        //         'expired_date' => $rec->expired_date,
                        //         'creator' => $rec->creator,
                        //         'receive_date' => $rec->receive_date,
                        //         'id_jurnal' => $rec->id_jurnal,
                        //     ));
                        //     $new->save();
                        //     $count++;
                        // }
                        // ReceiveDet::where('trx_id', $rec->trx_id)->where('prod_id', $rec->prod_id)->delete();
                        // echo $no++.". CP != CR ".$rec->id_jurnal." PO ID:".$rec->trx_id." ".$rec->prod_id.", qty:".$rec->qty.", qty total:".$rd.", count receive:".$cr." - ".$data['id'].", qty:".$data['qty'].", qty total:".$pd.", count po:".$cp."<br>";
                    }
                }
            }else{
                $receivedet = ReceiveDet::where('id', $rec->id)->first();
                $receivedet->purchasedetail_id = $data['id'];
                $receivedet->save();
            }
        }
        echo $count." ".ReceiveDet::where('purchasedetail_id', null)->count();
    }

    public function indexcheckprice(){
        foreach(PurchaseDetail::all() as $key){
            $query = PurchaseDetail::where('trx_id', $key->trx_id)->where('prod_id', $key->prod_id)->count();
            if($query > 1){
                $qtyrec = ReceiveDet::where('trx_id', $key->trx_id)->where('prod_id', $key->prod_id)->sum('qty');
                echo "<hr>";
                echo "PO.".$key->trx_id." - PO_det ID".$key->id." - ".$key->prod_id." - ".$key->price." : qty=".$key->qty.", qtyrec=".$qtyrec."<br>";
            }
        }
    }

    public function indexinsertpurchasedetailtoreceive(){
        foreach(ReceiveDet::select('id', 'prod_id', 'trx_id')->get() as $key){
            $purchasedet = PurchaseDetail::where('prod_id', $key->prod_id)->where('trx_id', $key->trx_id)->first();

            $key->purchasedetail_id = $purchasedet->id;
            $key->save();
        }
    }

    public function indexso(){
        foreach(SalesDet::all() as $key){
            $query = SalesDet::where('trx_id', $key->trx_id)->where('prod_id', $key->prod_id)->count();
            if($query > 1){
                echo "<hr>";
                echo "SO.".$key->trx_id." - ".$key->prod_id." : ".$query."<br>";
            }
        }
    }

    public function indexpo(){
        foreach(PurchaseDetail::all() as $key){
            $query = PurchaseDetail::where('trx_id', $key->trx_id)->where('prod_id', $key->prod_id)->count();
            if($query > 1){
                echo "<hr>";
                echo "PO.".$key->trx_id." - ".$key->prod_id." - ".$key->price." : ".$query."<br>";
            }
        }
    }

    public function indexab(){
        foreach (DeliveryOrder::all() as $key) {
            $sum = 0;
            $price = 0;
            foreach(DeliveryDetail::where('do_id',$key->id)->get() as $dodet){
                $sumprice = Purchase::join('tblpotrxdet','tblpotrxdet.trx_id','=','tblpotrx.id')->where('tblpotrxdet.prod_id',$dodet->product_id)->where('tblpotrx.tgl','<=',$key->sales->trx_date)->sum(DB::raw('tblpotrxdet.price*tblpotrxdet.qty'));
                $sumqty = Purchase::join('tblpotrxdet','tblpotrxdet.trx_id','=','tblpotrx.id')->where('tblpotrxdet.prod_id',$dodet->product_id)->where('tblpotrx.tgl','<=',$key->sales->trx_date)->sum('tblpotrxdet.qty');

                if($sumprice <> 0 && $sumqty <> 0){
                    $avcharga = $sumprice/$sumqty;
                }else{
                    $avcharga = 0;
                }

                $price += $avcharga * $dodet->qty;
            }
            $sum += $price;
            echo $key->jurnal_id." - ".$price."<br>";
            //insert debet Persediaan Barang di Gudang
            $debet = Jurnal::where('id_jurnal', $key->jurnal_id)->where('AccNo','2.1.3')->where('AccPos', 'Debet')->first();
            $debet->Amount = $price;
            $debet->update();

            //insert credit Persediaan Barang Indent
            $credit = Jurnal::where('id_jurnal', $key->jurnal_id)->where('AccNo','1.1.4.1.2')->where('AccPos', 'Credit')->first();
            $credit->Amount = $price;
            $credit->update();
        }

        echo "total: ".$sum;
    }

    public function index_check(){
        $price = 0;
        foreach (Jurnal::where('id_jurnal','LIKE','DO.%')->where('AccPos','Debet')->get() as $key) {
            $price += $key->Amount;
            echo $key->id_jurnal." - ".number_format($key->Amount)."<br>";
        }
        echo "Total: ".number_format($price);
    }

    public function indexb(){
        $a = DeliveryDetail::where('sales_id',271)->where('product_id','SG351')->sum('qty');
        dd($a);
        // dd(SalesDet::where('trx_id',271)->groupBy('prod_id')->get());
    }

    public function index565(){
        foreach(Sales::all() as $key){
            $harga = SalesDet::where('trx_id',$key->id)->sum(DB::raw('price*qty'));

            if($key->ttl_harga <> $harga){
                echo "<pre>";
                print_r($harga);
            }
        }
    }

    public function recursive($parent){
        $sum = 0;
        foreach($parent as $key2){
            $tot = 0;
            $check = Coa::where('AccParent',$key2->AccNo)->where('AccNo','NOT LIKE',$key2->AccNo)->count();
            if($check > 0){
                $temp = $key2->AccNo;
                $sub = Coa::where('AccParent',$key2->AccNo)->where('AccNo','NOT LIKE',$key2->AccNo)->get();
                TestController::recursive($sub);
            }else{
                $debet = Jurnal::where('AccNo',$key2->AccNo)->where('AccPos','Debet')->sum('Amount');
                $credit = Jurnal::where('AccNo',$key2->AccNo)->where('AccPos','Credit')->sum('Amount');
                $value = $debet-$credit;
                $acparent = $key2->AccParent;
                $sum+=$value;
                // echo $key2->AccNo." - ".$key2->AccName.": ".$value."<br>";
                echo "<tr>
                <td>".$key2->AccNo." - ".$key2->AccName."</td>
                <td>".$value."</td></tr>";
            }
        }
    }

    public function index_cust(){
        foreach(Customer::all() as $item){
            echo $item->apname."<br>";
            $sales = Sales::join('tblproducttrxdet','tblproducttrxdet.trx_id','=','tblproducttrx.id')->where('customer_id',$item->id)->select('tblproducttrxdet.*','tblproducttrx.trx_date')->get();
            $total = 0;

            foreach($sales as $item2){
                // AVG COST
                $avg_cost = PurchaseDetail::avgCost($item2->prod_id,$item2->trx_date);
                // SELISIH
                $selisih = $item2->price - $avg_cost;
                // Result
                $value = $selisih*$item2->qty;
                $total+=$value;
                echo "&nbsp; SO.".$item2->trx_id." - ".$item2->prod_id.": ".$value."<br>";
            }
            echo $total."<hr>";
        }
    }

    public function indexMember(){
        $keyword = 'A Dani';
        $array = array_values(array_column(DB::select('SELECT ktp FROM perusahaanmember WHERE perusahaan_id = 1'),'ktp'));

        $test = Member::whereIn('ktp',$array)->select('id','ktp','nama','scanktp','cetak')->where('nama','LIKE',$keyword.'%')->OrWhere('ktp','LIKE',$keyword.'%')->orderBy('nama')->paginate(10);
        echo "<pre>";
        print_r($test);
        die();
    }

    public function index_receive(){
        $sum = 0;
        foreach (ReceiveDet::all() as $key) {
            $pricedet = PurchaseDetail::where('trx_id',$key->trx_id)->where('prod_id',$key->prod_id)->first()->price;
            $price = $pricedet * $key->qty;

            $jurnal_receive_a = Jurnal::where('id_jurnal',$key->id_jurnal)->where('AccNo','1.1.4.1.2')->first();
            $jurnal_receive_a->amount = $price;
            $jurnal_receive_a->update();

            $jurnal_receive_b = Jurnal::where('id_jurnal',$key->id_jurnal)->where('AccNo','1.1.4.1.1')->first();
            $jurnal_receive_b->amount = $price;
            $jurnal_receive_b->update();
        }
    }
    // public function index(){
    //     $parent = Coa::where('AccNo',1)->select('AccNo','AccName')->first();
    //     echo $parent->AccNo." - ".$parent->AccName."<br>";
    //     $sub = Coa::where('AccParent',$parent->AccNo)->where('AccNo','NOT LIKE',$parent->AccNo)->get();
    //     TestController::test($sub);
    // }


    // public function index(){
    //     $total_credit = 0;
    //     $total_debet = 0;
    //     foreach (Purchase::all() as $key) {
    //         $total_tertahan = PurchaseDetail::where('trx_id',$key->id)->sum(DB::Raw('(price_dist - price)* qty'));

    //         if($total_tertahan < 0){
    //             $total_tertahan = $total_tertahan *-1;
    //             $total_credit+=$total_tertahan;
    //             $jurnal2 = Jurnal::where('id_jurnal',$key->jurnal_id)->where('AccNo','1.1.3.4')->first();
    //             $jurnal2->AccPos = "Credit";
    //             $jurnal2->Amount = $total_tertahan;
    //             $jurnal2->update();
    //         }else{
    //             $total_debet+=$total_tertahan;
    //             $jurnal2 = Jurnal::where('id_jurnal',$key->jurnal_id)->where('AccNo','1.1.3.4')->first();
    //             $jurnal2->AccPos = "Debet";
    //             $jurnal2->Amount = $total_tertahan;
    //             $jurnal2->update();
    //         }
    //     }

    //     echo $total_credit."<br>";
    //     echo $total_debet."<br>";
    //     echo $total_credit-$total_debet."<br>";
    // }

    // public function index(){
    //     $content = file_get_contents('https://www.royalcontrolling.com/api/do/print/19');
    //     $decode = json_decode($content);

    //     $file =  'DO-19.txt';  # nama file temporary yang akan dicetak
    //     $handle = fopen($file, 'w');
    //     $Data = "=========================\r\n";
    //     $Data .= "|       RWH HERBAL    |\r\n";
    //     $Data .= "|    DELIVERY ORDER   |\r\n";
    //     $Data .= "========================\r\n";
    //     $Data .= "TRXID : ".$decode->data[0]->trx_id."\r\n";
    //     $Data .= "DATE : ".$decode->data[0]->trx_date."\r\n";
    //     $Data .= "MARKETING : ".strtoupper($decode->data[0]->customer_name)."\r\n";
    //     $Data .= "==========================\r\n";
    //     $no = 1;
    //     foreach($decode->data as $key){
    //         $Data .= $no.". ".$key->product_name."\r\n";
    //         $Data .= "Qty : ".$key->qty." ".$key->unit."\r\n";
    //         $Data .= "\r\n";
    //         $no++;
    //     }
    //     $Data .= "Approved By\r\nInventory Officer\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n";
    //     Storage::put($file, $Data);
    //     file_put_contents($file, $Data);
    //     fwrite($handle, $Data);
    //     fclose($handle);
    //     copy($file, "//localhost/POS-80C");
    //     unlink($file);
    // }
    // public function index(){
    //     // dd(SalesDet::where('trx_id',21)->select('prod_id')->pluck('prod_id')->toArray());
    //     $prodarray = collect();
    //     foreach (PurchaseDetail::where('trx_id',18)->get() as $key) {
    //         $prodarray->push($key->prod_id);
    //     }
    //     dd($prodarray);
    // }
    public function indexs(){
        $sum = 0;

        foreach (Sales::all() as $key) {
            // SALES
            $cogs = 0;
            foreach (SalesDet::where('trx_id',$key->id)->select('price','qty','prod_id')->get() as $key2) {
                $avcost = Purchase::join('tblpotrxdet','tblpotrxdet.trx_id','=','tblpotrx.id')->where('tblpotrxdet.prod_id',$key2->prod_id)->where('tblpotrx.tgl','<=',$key->trx_date)->avg('tblpotrxdet.price');
                $cogs +=  ($avcost * $key2->qty);
            }
            $sum+=$cogs;
            echo $key->jurnal_id." - ".$cogs."<br>";
        }
        echo "<strong>".$sum."</strong>";
    }

    public function indexa(){
        $sum = 0;

        foreach (Sales::all() as $key) {
            foreach(DeliveryOrder::where('sales_id',$key->id)->select('id','jurnal_id')->get() as $dokey){
                $do_sum = 0;

                foreach(DeliveryDetail::where('do_id',$dokey->id)->get() as $dodet){
                    $do_avcost = Purchase::join('tblpotrxdet','tblpotrxdet.trx_id','=','tblpotrx.id')->where('tblpotrxdet.prod_id',$dodet->product_id)->where('tblpotrx.tgl','<=',$key->trx_date)->avg('tblpotrxdet.price');
                    $price = $do_avcost * $dodet->qty;
                    $do_sum+=$price;
                }
                echo $dokey->jurnal_id." - ".$do_sum."<br>";
                $sum+=$do_sum;
            }
        }
        echo "<strong>".$sum."</strong>";
    }

    public function index_avvg(){
        // $prodarray = collect();
        // foreach (PurchaseDetail::where('trx_id',18)->get() as $key) {
        //     $prodarray->push($key->prod_id);
        // }
        // $getTrxId = SalesDet::whereIn('prod_id',$prodarray)->select('trx_id')->groupBy('trx_id')->orderBy('trx_id')->get();
        // $getTrxId = SalesDet::whereIn('prod_id',['GHB01','GHB02','MHPAR01'])->select('trx_id')->groupBy('trx_id')->orderBy('trx_id')->get();
        // $getTrxId = SalesDet::select('trx_id','prod_id')->groupBy('trx_id')->orderBy('trx_id')->get();
        foreach (Sales::all() as $key) {
            echo "<strong>SO.".$key->id."</strong><br>";
            // SALES
            $sales_jurnal = $key->jurnal_id;
            $cogs = 0;
            foreach (SalesDet::where('trx_id',$key->id)->select('price','qty','prod_id')->get() as $key2) {
                echo "&nbsp;Product: ".$key2->prod_id."&nbsp; Qty: ".$key2."<br>";
                $sumprice = Purchase::join('tblpotrxdet','tblpotrxdet.trx_id','=','tblpotrx.id')->where('tblpotrxdet.prod_id',$key2->prod_id)->where('tblpotrx.tgl','<=',$key->trx_date)->sum(DB::raw('tblpotrxdet.price*tblpotrxdet.qty'));

                $sumqty = Purchase::join('tblpotrxdet','tblpotrxdet.trx_id','=','tblpotrx.id')->where('tblpotrxdet.prod_id',$key2->prod_id)->where('tblpotrx.tgl','<=',$key->trx_date)->sum('tblpotrxdet.qty');

                if($sumprice <> 0 && $sumqty <> 0){
                    $avcost = $sumprice/$sumqty;
                }else{
                    $avcost = 0;
                }
                echo "&nbsp;&nbsp; AVG Cost: ".$avcost."<br>";

                $cogs += $avcost * $key2->qty;
            }
            echo "&nbsp;COGS: ".$cogs."<br><p>";
            // Update Jurnal Sales
            if($sales_jurnal <> 0){
                // debet COGS
                    $jurnal_sales_a = Jurnal::where('id_jurnal',$sales_jurnal)->where('AccNo','5.1')->first();
                    $jurnal_sales_a->amount = $cogs;
                    $jurnal_sales_a->update();
                // Credit Persediaan Barang milik customer
                    $jurnal_sales_b = Jurnal::where('id_jurnal',$sales_jurnal)->where('AccNo','2.1.3')->first();
                    $jurnal_sales_b->amount = $cogs;
                    $jurnal_sales_b->update();
            }

            // DO
            foreach(DeliveryOrder::where('sales_id',$key->id)->select('id','jurnal_id')->get() as $dokey){
                echo "&nbsp;<strong>DO.".$dokey->id."</strong><br>";
                $do_sum = 0;
                // echo "DO.".$dokey->id."<br>";
                foreach(DeliveryDetail::where('do_id',$dokey->id)->select('qty','product_id')->get() as $dodet){
                    echo "&nbsp;&nbsp;Product: ".$dodet->product_id."&nbsp; detail: ".$dodet."<br>";
                    $do_sumprice = Purchase::join('tblpotrxdet','tblpotrxdet.trx_id','=','tblpotrx.id')->where('tblpotrxdet.prod_id',$dodet->product_id)->where('tblpotrx.tgl','<=',$key->trx_date)->sum(DB::raw('tblpotrxdet.price*tblpotrxdet.qty'));

                    $do_sumqty = Purchase::join('tblpotrxdet','tblpotrxdet.trx_id','=','tblpotrx.id')->where('tblpotrxdet.prod_id',$dodet->product_id)->where('tblpotrx.tgl','<=',$key->trx_date)->sum('tblpotrxdet.qty');

                    // echo "&nbsp;".$dodet->prod_id."<br>";
                    // echo "&nbsp; SUMPRICE: ".$do_sumprice."<br>";
                    // echo "&nbsp; SUMQTY: ".$do_sumprice."<br>";
                    if($do_sumprice <> 0 && $do_sumqty <> 0){
                        $do_avcost = $do_sumprice/$do_sumqty;
                    }else{
                        $do_avcost = 0;
                    }
                    echo "&nbsp;&nbsp;&nbsp; AVG Cost: ".$do_avcost."<br>";
                    $price = $do_avcost * $dodet->qty;
                    $do_sum+=$price;
                }
                echo "&nbsp;&nbsp; DO SUM: ".$do_sum."<br>";
                // Update Jurnal DO
                    // debet Persediaan Barang milik Customer
                        $jurnal_do_a = Jurnal::where('id_jurnal',$dokey->jurnal_id)->where('AccNo','2.1.3')->first();
                        $jurnal_do_a->amount = $do_sum;
                        $jurnal_do_a->update();
                    // credit Persediaan Barang digudang
                        $jurnal_do_b = Jurnal::where('id_jurnal',$dokey->jurnal_id)->where('AccNo','1.1.4.1.2')->first();
                        $jurnal_do_b->amount = $do_sum;
                        $jurnal_do_b->update();
            }
            echo "<hr>";
        }
    }
    // public function index(){
    //     $date = '2020-02-01';
    //     // GET Parent Account  = ASSET
    //     $parent = Coa::where('AccNo','1')->select('AccNo','AccName')->first();
    //     $sum = 0;
    //     $data = collect();

    //     // GET 2nd inheritance
    //     $collect2 = collect();
    //     foreach(Coa::where('AccParent',$parent->AccNo)->where('AccNo','NOT LIKE',$parent->AccNo)->get() as $key2){
    //         $sum2 = 0;
    //         $col2 = collect();

    //         // GET 3rd Inheritance
    //         $collect3 = collect();
    //         foreach(Coa::where('AccParent',$key2->AccNo)->get() as $key3){
    //             $sum3 = 0;
    //             $col3 = collect();

    //             // GET 4th Inheritance
    //             $collect4 = collect();
    //             foreach(Coa::where('AccParent',$key3->AccNo)->get() as $key4){
    //                 $sum4 = 0;
    //                 $col4 = collect();

    //                 // Get 5th Inheritance
    //                 $collect5 = collect();
    //                 foreach(Coa::where('AccParent',$key4->AccNo)->get() as $key5){
    //                     $col5 = collect();
    //                     if($key5->StatusAccount == 'Detail'){
    //                         // Get Total Amount From Jurnal
    //                         $sales5 = Jurnal::where('AccNo',$key5->AccNo);
    //                         if($date <> NULL){
    //                             $sales5->where('date','<=',$date);
    //                         }
    //                         $amount5 = $sales5->sum('Amount');

    //                         // Incement
    //                         $sum4+=$amount5;
    //                     }

    //                     $col5->put('name',$key5->AccName);
    //                     $col5->put('no',$key5->AccNo);
    //                     $col5->put('amount',$amount5);

    //                     $collect5->push($col5);
    //                 }

    //                 // Cek if Detail or not
    //                 if($key4->StatusAccount == 'Detail'){
    //                     // Get Total Amount From Jurnal
    //                     $sales4 = Jurnal::where('AccNo',$key4->AccNo);
    //                     if($date <> NULL){
    //                         $sales4->where('date','<=',$date);
    //                     }
    //                     $amount4 = $sales4->sum('Amount');

    //                     // Incement
    //                     $sum3+=$amount4;
    //                 }else{
    //                     $sum3+=$sum4;
    //                     $amount4=$sum3;
    //                 }

    //                 $col4->put('name',$key4->AccName);
    //                 $col4->put('no',$key4->AccNo);
    //                 $col4->put('amount',$amount4);
    //                 $col4->put('data',$collect5);

    //                 $collect4->push($col4);
    //             }

    //             // Cek if Detail or not
    //             if($key3->StatusAccount == 'Detail'){
    //                 // Get Total Amount From Jurnal
    //                 $sales3 = Jurnal::where('AccNo',$key3->AccNo);
    //                 if($date <> NULL){
    //                     $sales3->where('date','<=',$date);
    //                 }
    //                 $amount3 = $sales3->sum('Amount');

    //                 // Incement
    //                 $sum2+=$amount3;
    //             }else{
    //                 $sum2+=$sum3;
    //                 $amount3=$sum2;
    //             }

    //             $col3->put('name',$key3->AccName);
    //             $col3->put('no',$key3->AccNo);
    //             $col3->put('amount',$amount3);
    //             $col3->put('data',$collect4);

    //             $collect3->push($col3);
    //     }

    //         // Cek if Detail or not
    //         if($key2->StatusAccount == 'Detail'){
    //             // Get Total Amount From Jurnal
    //             $sales2 = Jurnal::where('AccNo',$sum2->AccNo);
    //             if($date <> NULL){
    //                 $sales2->where('date','<=',$date);
    //             }
    //             $amount2 = $sales2->sum('Amount');

    //             // Incement
    //             $sum+=$amount2;
    //         }else{
    //             $sum+=$sum2;
    //             $amount2=$sum;
    //         }
    //         $col2->put('name',$key2->AccName);
    //         $col2->put('no',$key2->AccNo);
    //         $col2->put('amount',$amount2);
    //         $col2->put('data',$collect3);

    //         $collect2->push($col2);
    //     }

    //     $data->put('name',$parent->AccName);
    //     $data->put('no',$parent->AccNo);
    //     $data->put('amount',$sum);
    //     $data->put('data',$collect2);

    //     dd($data);
    // }

    // public function index(){
    //     dd(Jurnal::where('date','<=','2019-11-04')->get());
    // }

    // public function index(){
    //     $data_parent = collect();
    //     $subparent = collect();
    //     $parent_sum = 0;

    //     foreach(Coa::where('AccNo','LIKE','6.3')->orwhere('AccNo','LIKE','6.4')->orwhere('AccNo','LIKE','7.3')->orwhere('AccNo','LIKE','7.4')->get() as $key){
    //         $subsub = collect();
    //         $sub_coa_collect = collect();
    //         $sub_sum = 0;

    //         foreach (Coa::where('AccParent',$key->AccNo)->get() as $key2) {

    //             $coa_collect = collect();
    //             $sales = Jurnal::where('AccNo',$key2->AccNo);
    //             // if($start <> NULL && $end <> NULL){
    //             //     $sales->whereBetween('date',[$start,$end]);
    //             // }

    //             $coasum = $sales->sum('Amount');
    //             $sub_sum+=$coasum;

    //             $coa_collect->put('name',$key2->AccName);
    //             $coa_collect->put('amount',$coasum);

    //             $sub_coa_collect->push($coa_collect);
    //         }

    //         $parent_sum+=$sub_sum;

    //         $subsub->put('name',$key->AccName);
    //         $subsub->put('amount',$sub_sum);
    //         $subsub->put('data',$sub_coa_collect);
    //         $subparent->push($subsub);
    //     }
    //     $data_parent->put('name',"Laba/Rugi Bersih Non Operasional");
    //     $data_parent->put('amount',$parent_sum);
    //     $data_parent->put('data',$subparent);

    //     dd($data_parent);
    // }

    // public function index(){
    //     foreach (Jurnal::where('AccNo','LIKE','PO%')->get() as $key) {
    //         $check = Purchase::where('jurnal_id',$key->id_jurnal)->count('jurnal_id');
    //         if($check > 0){
    //             echo $check."<br>";
    //         }else{
    //             echo $key->id_jurnal."<br>";
    //         }
    //     }
    // }

    // public function index(){
    //     $data = collect();

    //     foreach(Customer::all() as $key){
    //         $temp = collect();
    //         $detail = Sales::join('tblproducttrxdet','tblproducttrx.id','=','tblproducttrxdet.trx_id');

    //         // if($start <> NULL && $end <> NULL){
    //         //     $detail->whereBetween('tblproducttrx.trx_date',[$start,$end]);
    //         // }

    //         $bv = $detail->where('tblproducttrx.customer_id',$key->id)->sum('tblproducttrxdet.sub_ttl_pv');
    //         $price = $detail->where('tblproducttrx.customer_id',$key->id)->sum('tblproducttrxdet.sub_ttl');

    //         $temp->put('customer',$key->apname);
    //         $temp->put('price',$price);
    //         $temp->put('bv',$bv);

    //         $data->push($temp);
    //     }
    //     dd($data);
    // }

    // public function indexww(){
    //     $total_tertahan = PurchaseDetail::where('trx_id',4)->sum(DB::Raw('(price - price_dist)* qty'));
    //     echo $total_tertahan;
    // }

    // public function indexfd(){
    //     dd(Employee::select('id','username')->join('tblemployeerole as er','er.username','=','tblemployee.username')->join('tblrole as r','r.id','er.role_id')->where('r.role_name','LIKE','Staff%')->select('tblemployee.id','tblemployee.username')->get());
    // }

    // public function indextime(){
    //     $date = '2019-11-17';
    //     $date = Carbon::createFromFormat('Y-m-d H:i:s',$date.'00:00:00');
    //     echo $date."<br>";
    //     $today = Carbon::now();
    //     echo $today."<br>";

    //     $interval = date_diff($today, $date);
    //     $selisih = intval($interval->format('%R%a'));
    //     echo $interval->format('%R%a days')."<br>";
    //     echo $selisih."<br>";

    //     if($interval->days > 2){
    //         echo "lebih";
    //     }else{
    //         echo "masih bisa";
    //     }

    // }

    // public function indexwq(){
    //     dd(session('role'));
    //     $sales = Sales::all();
    //     $collect = collect();
    //     foreach($sales as $sale){
    //         $sale->put('status',1);
    //         // $colect = collect();
    //         // $colect->push($sale);
    //         // $colect->put('status',1);
    //         // $collect->push($colect);
    //         dd($sale);
    //     }
    //     dd($collect);
    // }
    // public function indexxxxx(){
    //     $collection = collect([
    //         ['id' => 1, 'value' => 10],
    //         ['id' => 2, 'value' => 20],
    //         ['id' => 3, 'value' => 100],
    //         ['id' => 4, 'value' => 250],
    //         ['id' => 5, 'value' => 150],
    //     ]);
    //     $sorted = $collection->sortByDesc('value');
    //     // 5.1
    //     dd($sorted->values()->first());
    // }

    // public function indexs(){
    //     echo $test = Hash::make("canik123");
    //     // foreach(PriceDet::groupBy('prod_id')->distinct()->get() as $key){
    //     //     $product = Product::where('prod_id',$key->prod_id)->first();
    //     //     if($product){
    //     //         echo $key->prod_id." ADA <br>";
    //     //     }else{
    //     //         PriceDet::where('prod_id',$key->prod_id)->delete();
    //     //         echo $key->prod_id." Ga Ada <br>";
    //     //     }
    //     // }
    // }

    // public function indexktp(){
    //     // ktp
    //     $dir = 'D:/DATA/Kerja/RWH/KERAJ/mv/atm';
    //     if (is_dir($dir)){
    //         $files = scandir($dir);
    //         $filecount = count($files);
    //         for ($i=0; $i < $filecount ; $i++) {
    //             if ($files[$i] != '.' && $files[$i] != '..') {
    //                 $subdir = $dir."/".$files[$i];
    //                 // $filebaru = $subdir."/".$files[$i].".jpg";
    //                 $subfiles = array_values(array_diff(scandir($subdir), array('..', '.')));
    //                 if(is_array($subfiles) && $subfiles <> null){

    //                     // echo "<pre>";
    //                     // print_r($subdir."/".$subfiles[0]);
    //                     $old = $subdir."/".$subfiles[0];
    //                     $new = $subdir."/".$files[$i].".jpg";
    //                     // echo "<pre>";
    //                     // print_r($old);
    //                     // echo "<pre>";
    //                     // print_r($new);
    //                     $member = Member_copy::where('ktp',$files[$i])->first();
    //                     if($member){
    //                         rename($old,$new);
    //                         $member->scanktp = $files[$i].".jpg";
    //                         $member->save();
    //                     //     // $files_next = scandir($subdir);
    //                     //     echo $files[$i]." Ada<br>";
    //                     }else{
    //                     //     echo $files[$i]." Tidak Ada<br>";
    //                     }
    //                     // $member = Member::where('ktp',$files[$i])->first();
    //                     // if($member != null){
    //                     //     $scanktp = $files[$i].".jpg";
    //                     //     $member->scanktp = $scanktp;
    //                     //     $member->update();
    //                     // }
    //                 }
    //             }
    //         }
    //         die();
    //     }
    // }

    // public function indexz(){
    //     $dir = 'D:/DATA/Kerja/RWH/KERAJ/mv/ktp';
    //     if (is_dir($dir)){
    //         $files = scandir($dir);
    //         $filecount = count($files);

    //         for ($i=0; $i < $filecount ; $i++) {
    //             if ($files[$i] != '.' && $files[$i] != '..') {
    //                 $subdir = $dir."/".$files[$i];
    //                 $member = Member_copy::where('ktp',$files[$i])->first();
    //                 if($member){
    //                     $files_next = scandir($subdir);
    //                     echo "<pre>";
    //                     print_r($files_next);
    //                 }else{
    //                     echo "Ga ada Cuk <br>";
    //                 }
    //                 echo "----------- <br>";
    //             }
    //         }
    //         die();
    //     }
    // }

    // public function indexd(){
    //     // buat ganti koor dan sub koor
    //     $perusahaans = Perusahaan::all();
    //     foreach($perusahaans as $per){
    //         echo "<pre>";
    //         print_r($per->nama);
    //     print_r(DB::table('perusahaanmember_copy')->where('perusahaan_id',$per->nama)->get());
    //         // dd(DB::table('tbl_member_copy_copy1')->get());
    //         // $do = DB::Raw("UPDATE tblmember_copy_copy1 SET subkoor = $koor->id WHERE subkoor = '$koor->nama'");per
    //         // DB::table('perusahaanmember_copy')->where('perusahaan_id',$per->nama)->update(['perusahaan_id' => $per->id]);
    //     }
    // }

    // public function indexb(){
    //     $bankmember = BankMember_copy::all();

    //     foreach ($bankmember as $key) {
    //         $dir = 'D:/DATA/Kerja/RWH/KERAJ/mv/tabungan/'.str_replace(' ', '', $key->ktp).'/'.str_replace(' ', '', $key->norek);

    //         if(!is_dir($dir)){
    //         }else{

    //             $filebaru = $dir."/".str_replace(' ', '', $key->norek).".jpg";
    //             $subfiles = array_values(array_diff(scandir($dir), array('..', '.')));
    //             if(is_array($subfiles) && $subfiles <> null){
    //                 // echo "<pre>";
    //                 // print_r($subfiles);
    //                 rename($dir."/".$subfiles[0],$filebaru);
    //                 // echo $subfiles[0]."<br>";
    //                 // echo "<pre>";
    //                 // print_r($subfiles);
    //                 // $atm = BankMember::where('ktp',str_replace(' ', '', $key->ktp))->first();
    //                 $key->scantabungan = str_replace(' ', '', $key->norek).".jpg";
    //                 $key->update();
    //             }

    //             // echo "<pre>";
    //             // print_r($subfiles);
    //             // echo "enggak<br>";
    //         }
    //     }
    // }

    // public function indexpm(){
    //     foreach(PerusahaanMember_copy::all() as $key){
    //         $member = Member_copy::where('ktp',$key->ktp)->first();
    //         if($member){
    //             echo $member->ktp."<br>";
    //         }else{
    //             // $key->delete();
    //             echo "Ga ada Cuk <br>";
    //         }
    //         echo "----------- <br>";
    //     }
    // }

    // public function index2(Request $request){
    //     $keyword = $request->get('search');
    //     $datas = User::where('name', 'LIKE',$keyword . '%')
    //         ->paginate();
    //     $data = collect();
    //     $i=1;
    //     foreach ($datas as $key) {
    //         $memcollect = collect();
    //         $memcollect->put('no',$i);
    //         $memcollect->put('ktp',$key->name);
    //         $memcollect->put('nama',$key->email);
    //         $data->push($memcollect);
    //         $i++;
    //     }
    //     $data2 = $datas->links();
    //     $datas->withPath('yourPath');
    //     $datas->appends($request->all());

    //     echo "<pre>";
    //     print_r($datas);die();
    //     if ($request->ajax()) {
    //         return response()->json(view('test.list',compact('data','datas','data2'))->render());
    //     }
    //     return view('test.index',compact('data', 'keyword','data2'));
    // }

    // public function index3(){
    //     $member = Member_copy::all();
    //     foreach ($member as $key) {
    //         if($key->tgllhr != "-" || $key->tgllhr !="---"){
    //             $newDate = date("Y-m-d", strtotime($key->tgllhr));
    //             $key->tgllhr = $newDate;
    //             $key->update();
    //         }

    //     }

    // }

    // public function indexaa(){
    //     // foreach(ManageHarga2::groupBy('prod_id')->distinct()->get() as $key){
    //     //     $product = Product::where('prod_id',$key->prod_id)->first();
    //     //     if($product){
    //     //         echo $key->prod_id." ADA <br>";
    //     //     }else{
    //     //         ManageHarga2::where('prod_id',$key->prod_id)->delete();
    //     //         echo $key->prod_id." Ga Ada <br>";
    //     //     }
    //     // }
    //     $encrypted = Crypt::encryptString('Belajar Laravel Di malasngoding.com');
	// 	$decrypted = Crypt::decryptString('$2y$10$Rchoh5O7de3roYe84yGfweyAQFkMHm3SYrevYfBk/oBXzV7A4P4p2');

	// 	echo "Hasil Enkripsi : " . $encrypted;
	// 	echo "<br/>";
	// 	echo "<br/>";
	// 	echo "Hasil Dekripsi : " . $decrypted;
    // }
}
