<style>
    .testimonial-img {
        object-fit:cover;
        min-height: 150px;
        min-width: 150px;
        max-height:200px;
        max-width:200px;
        align-content: center;
    }
</style>

<div class="swiper-slide">
    <div class="testimonial-wrap">
        <div class="testimonial-item">
            <div class="d-flex align-items-center">
                <div class="row">
                    <img src="{{ $teacher->image }}" class="testimonial-img flex-shrink-0" alt="">
                </div>
                <div style="margin-left:40px;">
                    <h3><strong>{{ $teacher->name }}</strong></h3>
                    <h5>{{ $teacher->title }}</h5>
                    @if($teacher->location != "")
                        <p><i class="bi bi-pin-map"></i> {{ $teacher->location }}</p>
                    @endif
                    {{-- <div class="stars" style="color:#FFD700; margin-bottom:20px">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div> --}}
                    <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        {{ $teacher->description }}
                        <i class="bi bi-quote quote-icon-right"></i>
                    </p>
                </div>
            </div>
            <div class="goright" style="float:right">
                <a href="/#contact" class="btn btn-theme">Order Sekarang</a>
            </div>
        </div>
    </div>
</div><!-- End testimonial item -->