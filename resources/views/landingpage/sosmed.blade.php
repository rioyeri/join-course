    <!-- ======= Cta Section ======= -->
    <section class="cta">
        <div class="container">

        <div class="text-center">
            <h3>Kunjungi Media Sosial Kami</h3>

            <div class="row g-4 py-5 row-cols-1 row-cols-lg-{{ $links->count() }}">
                @foreach($links as $link)
                <div class="feature col">
                    <div class="feature-icon bg-gradient" style="background: #8484FD;">
                        <i class="{{ $link->get_category->icon }}"></i>
                    </div>
                    <h3>{{ $link->title }}</h3>
                    <p>{{ $link->description }}</p>
                    <a href="{{ $link->link }}" class="cta-btn">Kunjungi <i class='bx bxs-chevron-right-square bx-fade-right'></i></a>
                </div>
                @endforeach
            </div>
        </div>
    </section><!-- End Cta Section -->
