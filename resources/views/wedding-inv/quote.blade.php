<!-- ======= Quote Section ======= -->
<section id="quote" class="text-center" style="background-image: url({{ asset('multimedia/'.$invitation->invitation_id.'/'.$quote->bg_image) }})">
  <div class="overlay">
    <div class="container-fluid container-full" data-aos="zoom-in">

      <div class="row">
        <div class="col-12">
          <div class="text-center">
            <h1 class="quote-title">{{ $quote->title }}</h1>
            <h4 class="quote-text">{{ $quote->text }}</h4>
          </div>
        </div>
      </div>

    </div>
  </div>
</section><!-- End Quote Section -->