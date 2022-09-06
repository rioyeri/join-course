<style>
  .image-fit{
      object-fit:cover;
      width:415px;
      height:415px;
      border: solid 1px #CCC
  }
</style>
<!-- ======= Screenshots Section ======= -->
<section id="screenshots" class="padd-section text-center">

    <div class="container" data-aos="fade-up">
      <div class="section-title text-center">
        <h2>Galeri Foto</h2>
        <p class="separator">Beberapa potret kebahagiaan kami</p>
      </div>

      <div class="screens-slider swiper">
        <div class="swiper-wrapper align-items-center">
          @foreach ($galleries as $gallery)
            <div class="swiper-slide"><img src="{{ asset('multimedia/'.$invitation->invitation_id.'/galleries/'.$gallery->image) }}" class="img-fluid image-fit" alt=""></div>
          @endforeach
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>

  </section><!-- End Screenshots Section -->