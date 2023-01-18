<!-- ======= Pricing Section ======= -->
<section id="pricing" class="pricing sections-bg">
    <div class="container">

        <div class="section-header">
            <h2 data-aos="zoom-in-down">{{ $content[5]->title }}</h2>
            <p data-aos="zoom-out-up" data-aos-delay="250">{{ $content[5]->subtitle }}</p>
        </div>

        <div class="row g-4 py-lg-5" data-aos="flip-left">
            @php($i=1)
            @foreach ($promos as $promo)
            <div class="{{ $promo->column_size_detail }}">
                <div class="pricing-item @if($promo->category == 1) featured @endif">
                    <h3>{{ $promo->package_name }}</h3>
                    <div class="icon">
                        <i class="{{ $promo->icon }}"></i>
                    </div>
                    {{-- <h5><span>Mulai dari</span><br>@if(is_int($promo->price)) {{ number_format($promo->price,0,",",".") }} @else {{ $promo->price }} @endif<span> / {{ $promo->time_signature }}</span></h5> --}}
                    <h5>@if($promo->discount_rate != 0)<span><del>&ensp;Rp {{ number_format($promo->price, 0,",",".") }}&ensp;</del> <span style="background: #dd3214; color:white; border-radius: 3px; padding: 0 5px 0 5px;">{{ $promo->discount_rate }}% off</span>@endif</span><br>Rp {{ number_format($promo->discount_price,0,",",".") }}</h5>
                    <ul>
                        @foreach ($promo->detail as $det)
                            @if ($det->status==0)
                                <li class="na"><i class="bi bi-x"></i> <span>{{ $det->text }}</span></li>
                            @else
                                <li><i class="bi bi-check"></i> {{ $det->text }}</li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="text-center"><a href="#contact" class="buy-btn">{{ $promo->link_text }}</a></div>
                </div>
            </div><!-- End Pricing Item -->
            @php($i++)                
            @endforeach
        </div>
        <div class="text-center" style="margin: 40px 0 0 0">
            <a href="{{ route('showAllPackage') }}">Tampilkan semua Paket <i class="bi bi-caret-down-fill"></i></a>
        </div>
    </div>
</section><!-- End Pricing Section -->
