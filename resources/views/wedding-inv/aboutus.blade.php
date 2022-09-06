<style>
img.photo{
  object-fit:cover; display:block; width:250px; height:300px;
}
</style>
<!-- ======= About Us Section ======= -->
<section id="about-us" class="about-us padd-section">
  <div class="container " style="margin-bottom: 30px">
    <div class="col-12">
      <h3 class="text-center">
        @foreach(explode (",", $invitation->tagline)  as $tagline)
          <strong>{{ $tagline }}</strong>,<br>
        @endforeach
      </h3>
    </div>
  </div>
  <div class="container" data-aos="fade-up">
      <div class="row justify-content-center">

        @if($invitation->format == 1)
          <div class="col-md-4">
            <div class="about-content text-pria text-right" data-aos="fade-left" data-aos-delay="100">
              <h3><span>{{ $invitation->groom_name }}</span></h3>
              <p>Putra dari {{ $invitation->groom_father }} dan {{ $invitation->groom_mother }}</p>
            </div>
          </div>

          <div class="col-md-2">
            <img class="photo" src="{{ asset('multimedia/'.$invitation->invitation_id.'/'.$invitation->groom_photo) }}" alt="Groom" data-aos="zoom-in" data-aos-delay="100">
          </div>

          <div class="col-md-2">
            <img class="photo" src="{{ asset('multimedia/'.$invitation->invitation_id.'/'.$invitation->bride_photo) }}" alt="Bride" data-aos="zoom-in" data-aos-delay="100">
          </div>

          <div class="col-md-4">
            <div class="about-content text-wanita" data-aos="fade-left" data-aos-delay="100">
              <h3><span>{{ $invitation->bride_name }}</span></h3>
              <p>Putri dari {{ $invitation->bride_father }} dan {{ $invitation->bride_mother }}</p>
            </div>
          </div>
        @else
          <div class="col-md-4">
            <div class="about-content text-wanita" data-aos="fade-left" data-aos-delay="100">
              <h3><span>{{ $invitation->bride_name }}</span></h3>
              <p>Putri dari {{ $invitation->bride_father }} dan {{ $invitation->bride_mother }}</p>
            </div>
          </div>
          <div class="col-md-2">
            <img class="photo" src="{{ asset('multimedia/'.$invitation->invitation_id.'/'.$invitation->bride_photo) }}" alt="Bride" data-aos="zoom-in" data-aos-delay="100">
          </div>
        
          <div class="col-md-2">
            <img class="photo" src="{{ asset('multimedia/'.$invitation->invitation_id.'/'.$invitation->groom_photo) }}" alt="Groom" data-aos="zoom-in" data-aos-delay="100">
          </div>
          <div class="col-md-4">
            <div class="about-content text-pria text-right" data-aos="fade-left" data-aos-delay="100">
              <h3><span>{{ $invitation->groom_name }}</span></h3>
              <p>Putra dari {{ $invitation->groom_father }} dan {{ $invitation->groom_mother }}</p>
            </div>
          </div>          
        @endif
        
      </div>
  </div>
</section><!-- End About Us Section -->