<!-- ======= Frequently Asked Questions Section ======= -->
<section id="faq" class="faq">
    <div class="container">

        <div class="row gy-4">

            <div class="col-lg-4">
                <div class="content px-xl-5">
                    <h3 data-aos="zoom-in-down"><strong>{{ $content[4]->title }}</strong></h3>
                    <p data-aos="zoom-out-right" data-aos-delay="100">{{ $content[4]->subtitle }}</p>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="accordion accordion-flush" id="faqlist">
                    @php($i=1)
                    @foreach ($content[4]->detail as $detail)
                        <div class="accordion-item" data-aos="flip-down" data-aos-delay="{{ $i * 50 }}">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq-content-{{ $i }}">
                                    <span class="num">{{ $i }}.</span>
                                    {{ $detail->title }}
                                </button>
                            </h3>
                            <div id="faq-content-{{ $i }}" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                                <div class="accordion-body">
                                    {{ $detail->description }}
                                </div>
                            </div>
                        </div><!-- # Faq item-->     
                        @php($i++)                   
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</section><!-- End Frequently Asked Questions Section -->
