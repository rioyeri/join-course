<!-- ======= Testimonials Section ======= -->
<section id="testimonials" class="testimonials">
    <div class="container" data-aos="fade-up">
        <div class="section-header">
            <h2>{{ $content[3]->title }}</h2>
            <p>{{ $content[3]->subtitle }}</p>
        </div>
        <div class="slides-3 swiper" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper">
                @foreach ($content[3]->detail as $detail)
                    <div class="swiper-slide">
                        <div class="testimonial-wrap">
                            <div class="testimonial-item">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $detail->image }}" class="testimonial-img flex-shrink-0" alt="">
                                    <div>
                                        <h3>{{ $detail->title }}</h3>
                                        <h4>{{ $detail->subtitle }}</h4>
                                    </div>
                                </div>
                                <p>
                                    <i class="bi bi-quote quote-icon-left"></i>
                                    {{ $detail->description }}
                                    <i class="bi bi-quote quote-icon-right"></i>
                                </p>
                            </div>
                        </div>
                    </div><!-- End testimonial item -->                    
                @endforeach

            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section><!-- End Testimonials Section -->
