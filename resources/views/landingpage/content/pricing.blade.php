<!-- ======= Pricing Section ======= -->
<section id="pricing" class="pricing sections-bg">
    <div class="container" data-aos="fade-up">

        <div class="section-header">
            <h2>{{ $content[5]->title }}</h2>
            <p>{{ $content[5]->subtitle }}</p>
        </div>

        <div class="row g-4 py-lg-5" data-aos="zoom-out" data-aos-delay="100">
            @foreach ($promos as $promo)
            <div class="col-lg-4">
                <div class="pricing-item @if($promo->category == 1) featured @endif">
                    <h3>{{ $promo->name }}</h3>
                    <div class="icon">
                        <i class="{{ $promo->icon }}"></i>
                    </div>
                    <h4><sup>Rp </sup>@if(is_int($promo->price)) {{ number_format($promo->price,0,",",".") }} @else {{ $promo->price }} @endif<span> / {{ $promo->time_signature }}</span></h4>
                    <ul>
                        @foreach ($promo->detail as $det)
                            @if ($det->status==0)
                                <li class="na"><i class="bi bi-x"></i> <span>{{ $det->text }}</span></li>
                            @else
                                <li><i class="bi bi-check"></i> {{ $det->text }}</li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="text-center"><a href="{{ $promo->link }}" class="buy-btn">{{ $promo->link_text }}</a></div>
                </div>
            </div><!-- End Pricing Item -->                
            @endforeach
        </div>

    </div>
</section><!-- End Pricing Section -->
