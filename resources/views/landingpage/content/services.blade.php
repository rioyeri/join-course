<!-- ======= Our Services Section ======= -->
<section id="services" class="services sections-bg">
    <div class="container" data-aos="fade-up">
        <div class="section-header">
            <h2>{{ $content[1]->title }}</h2>
            <p>{{ $content[1]->subtitle }}</p>
        </div>
        <div class="row gy-4" data-aos="fade-up" data-aos-delay="100">
            @foreach($content[1]->detail as $detail)
                <div class="{{ $content[1]->column_size_detail }} col-md-6">
                    <div class="service-item  position-relative">
                        <div class="icon">
                            <i class="{{ $detail->image }}"></i>
                        </div>
                        <h3>{{ $detail->title }}</h3>
                        <p>{{ $detail->description }}</p>
                        @if($detail->link_text != NULL)
                            <a href="{{ $detail->link }}" class="readmore stretched-link">{{ $detail->link_text }} <i class="bi bi-arrow-right"></i></a>
                        @endif
                    </div>
                </div><!-- End Service Item -->
            @endforeach
        </div>
    </div>
</section><!-- End Our Services Section -->