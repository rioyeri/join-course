<!-- ======= Our Team Section ======= -->
<section id="team" class="team">
    <div class="container" data-aos="fade-up">

        <div class="section-header">
            <h2>Guru-guru kami</h2>
            <p>Kami telah bekerja sama dengan guru-guru yang profesional dan memiliki passion mengajar</p>
        </div>

        <div class="row gy-4">
            @php($i=1)
            @foreach ($teachers as $teacher)
                <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="@php(100 * $i)">
                    <div class="member">
                        <img src="{{ asset('landingpage/assets/img/team/team-'.$i.'.jpg') }}" class="img-fluid" alt="">
                        <h4>{{ $teacher->teacher->name }}</h4>
                        <span>{{ $teacher->title }}</span>
                        <div class="social">
                            <a href=""><i class="bi bi-twitter"></i></a>
                            <a href=""><i class="bi bi-facebook"></i></a>
                            <a href=""><i class="bi bi-instagram"></i></a>
                            <a href=""><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div><!-- End Team Member -->
                @php($i++)
            @endforeach
        </div>

    </div>
</section><!-- End Our Team Section -->
