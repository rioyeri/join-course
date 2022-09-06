<style>
    .image-fit{
        object-fit:cover;
        width:415px;
        height:415px;
        border: solid 1px #CCC
    }
</style>
@if(count($galleries) != 0)
<!-- ======= Our Portfolio Section ======= -->
<section id="portfolio" class="portfolio">
    <div class="container">

    <div class="section-title">
        <h2>Galeri Foto</h2>
    </div>

    <div class="row">
        <div class="col-lg-12 d-flex justify-content-center">
        <ul id="portfolio-flters">
            <li data-filter="*" class="filter-active">All</li>
            @foreach($gallery_tags as $tag)
                <li data-filter=".{{ $tag->tag_name }}">{{ str_replace('-', ' ',$tag->tag_name) }}</li>
            @endforeach
        </ul>
        </div>
    </div>

    <div class="row portfolio-container">
        @foreach($galleries as $gallery)
            <div class="col-lg-4 col-md-6 portfolio-item {{ $gallery->tags }}">
                <div class="portfolio-wrap">
                    <img src="{{ asset('assets/images/galleries/'.$gallery->image) }}" class="img-fluid image-fit" alt="">
                    <div class="portfolio-info">
                        <h4>{{ $gallery->title }}</h4>
                        <p>{{ $gallery->description }}</p>
                    </div>
                    <div class="portfolio-links">
                        <a href="{{ asset('assets/images/galleries/'.$gallery->image) }}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Buka Gambar"><i class='bx bx-zoom-in'></i></a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    </div>
</section><!-- End Our Portfolio Section -->
@endif
