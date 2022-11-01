<!DOCTYPE html>
<html>
    <head>
        <title>Invoice #{{ $data->invoice_id }}</title>
        <!-- Bootstrap core CSS -->
        <link href="{{ asset('dashboard/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    
        <style>
            * {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            h1{
                font-family: sans-serif;
                font-weight: 700;
            }
            h2,h3,h4,h5,h6,p,span,div { 
                font-family: sans-serif; 
                font-size:17px;
                font-weight: normal;
            }
            th,td { 
                font-family: sans-serif; 
                font-size:17px;
            }
            .panel {
                margin-bottom: 20px;
                background-color: #fff;
                border: 1px solid transparent;
                border-radius: 4px;
                -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
                box-shadow: 0 1px 1px rgba(0,0,0,.05);
            }
            .panel-default {
                border-color: #ddd;
            }
            .panel-body {
                padding: 15px;
            }
            table {
                width: 100%;
                max-width: 100%;
                margin: 0px;
                border-spacing: 0;
                border-collapse: collapse;
                background-color: transparent;
            }
            thead  {
                text-align: left;
                display: table-header-group;
                vertical-align: middle;
            }
            th, td  {
                border: 1px solid #ddd;
                padding: 6px;
            }
            .well {
                min-height: 20px;
                padding: 19px;
                margin-bottom: 20px;
                background-color: #f5f5f5;
                border: 1px solid #e3e3e3;
                border-radius: 4px;
                -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
                box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
            }

            .img-logo{
                margin-top: 30px;
                margin-left: 60px;
                height: 100px;
                width: 100px;
                position:absolute;
            }
        </style>
    </head>
    <body style="margin:10px;">
        <header>
            {{-- <div style="display:inline-block">
                <img class="img-logo" src="{{ asset('flashacademia-logo-small.webp') }}">
                <div style="float:right;">
                    <h1>INVOICE</h1>
                </div>
            </div> --}}
            <div style="width:100%;">
                <div style="margin-left:20px;">
                    <img class="img-logo" src="{{ asset('dashboard/img/flashacademia-logo-small.webp') }}">
                </div>
                <div style="float: right; margin-right: 50px;">
                    <h1>INVOICE</h1>
                </div>
            </div>
            <br>
            <div style="margin-bottom:30px; margin-top:130px; width:100%;">
                <div style="margin-left:20px;">
                    Nama : <strong>{{ $data->student_name }}</strong><br>
                    <span style="margin-left:60px">{{ $data->student_phone }}</span>
                </div>
                <div style="float: right; margin-right: 50px; margin-top:-45px">
                    Tanggal: {{ $data->date }}<br>
                    Invoice: <b>#{{ $data->invoice_id }}</b><br>
                </div>
            </div>
        </header>
        <main>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Paket</th>
                        <th>Pelajaran</th>
                        <th>Kelas</th>
                        <th>Guru</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i=1)
                    @foreach ($data->items as $item)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $item->package_name }}</td>
                            <td>{{ $item->course_name }}</td>
                            <td>{{ $item->grade }}</td>
                            <td>{{ $item->teacher_name }}</td>
                            <td>Rp {{ number_format($item->order_bill, 2,",",".") }}</td>
                        </tr>
                        @php($i++)
                    @endforeach
                </tbody>
            </table>
            <div style="clear:both; position:relative;">
                <div style="position:absolute; left:0pt; width:250pt;">
                    <h4>Notes:</h4>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            * Jika terdapat kesalahan dalam invoice, silahkan hubungi admin kami
                        </div>
                    </div>
                </div>
                <div style="margin-left: 300pt;">
                    <br>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><b>Subtotal</b></td>
                                <td style="text-align: right">Rp {{ number_format($data->subtotal,2,",",".") }}</td>
                            </tr>
                            @isset($data->tax_name)
                            <tr>
                                <td><b>{{ $data->tax_name }}</b></td>
                                <td style="text-align: right">Rp {{ number_format($data->tax_value,2,",",".") }}</td>
                            </tr>
                            @endisset
                            @isset($data->paid)
                            <tr>
                                <td><b>Sudah terbayar</b></td>
                                <td style="text-align: right">Rp {{ number_format($data->paid,2,",",".") }}</td>
                            </tr>
                            @endisset
                            <tr>
                                <td><b>TOTAL</b></td>
                                <td style="text-align: right"><b>Rp {{ number_format($data->total_bill,2,",",".") }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </body>
</html>