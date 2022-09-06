<!-- ======= About Us Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Tentang Gereja Kami</h2>
          <p>{{ $aboutus }}</p>
        </div>

        <div class="row">
          <div class="col-lg-6 text-left">
            <img style="float: right;" src="{{ asset('assets/images/aboutus/'.$sejarah->image) }}" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 content">
            <h3>{{ $sejarah->title }}</h3>
            <p class="fst-italic">{{ $sejarah->description }}</p>
          </div>
        </div>
        <br><hr><br>
        <div class="row">
            <div class="col-lg-6 pt-4 pt-lg-0 content">
                <h3>{{ $gembala->title }}</h3>
                <p class="fst-italic">{{ $gembala->description }}</p>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('assets/images/aboutus/'.$gembala->image) }}" class="img-fluid" alt="">
            </div>

        </div>

      </div>
    </section><!-- End About Us Section -->
