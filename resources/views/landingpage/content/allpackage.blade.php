@extends('landingpage.layout.page')

@section('content')
<!-- ======= Our Team Section ======= -->
<section id="team" class="team">
    <div class="container" data-aos="fade-up">

        <div class="section-header" style="margin-top: 40px;">
            <h2>Paket Belajar</h2>
        </div>

        @if (!$results)
            <p style="font-style: italic; font-size: 20px;text-align: center; padding-bottom: 60px;">Paket tidak ditemukan</p>
        @else
            <div class="row gy-4">
                <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-package">
                    <thead align="center">
                        <th>No</th>
                        <th>Nama Paket</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Pertemuan</th>
                        <th style="text-align: right">Biaya <span style="color:red">*</span></th>
                        <th style="text-align: right">Diskon (%) <span style="color:red">*</span></th>
                        <th style="text-align: right">Biaya setelah diskon <span style="color:red">*</span></th>
                    </thead>
                    <tbody id="table-body">
                        @php($i=1)
                        @foreach ($results as $result)
                            <tr>
                                <td width="5%">{{ $i++ }}</td>
                                <td width="15%">{{ $result->name }}</td>
                                <td width="30%">{{ $result->description }}</td>
                                <td width="10%" align="center">{{ $result->number_meet }}</td>
                                <td width="15%" align="right">Rp {{ number_format($result->price,2,",",".") }}</td>
                                <td width="10%" align="right">{{ number_format($result->discount_rate,1,",",".") }}%</td>
                                <td width="25%" align="right">Rp {{ number_format($result->price - ($result->price / 100 * $result->discount_rate), 2, ",", ".") }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p>(<span style="color:red">*</span>) Biaya dan diskon bisa berubah sewaktu-waktu</p>

            <div class="text-center" style="float:center">
                <a href="/#contact" class="btn btn-theme">Order Sekarang</a>
            </div>
        @endif
    </div>
</section><!-- End Our Team Section -->
@endsection