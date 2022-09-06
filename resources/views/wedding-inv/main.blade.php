
<!DOCTYPE html>
<html lang="en">
  @include('wedding-inv.head')

<body>
  <div id="judul" style="display: none;">
  @include('wedding-inv.header')
  </div>
  @include('wedding-inv.banner')
  <main id="main">
    @include('wedding-inv.aboutus')
    @isset($quote)
      @include('wedding-inv.quote')      
    @endisset
    @include('wedding-inv.invitation')
    @if (count($galleries) != 0)
      @include('wedding-inv.gallery')      
    @endif

    @include('wedding-inv.features')
    @include('wedding-inv.wishes')

    <div class="mute shadow px-3 py-2" style="border-radius: 20px; color: black; background-color: white; display:none;" id="mute" onClick="mute();"><i class="fa fa-volume-up"></i></div>

  </main><!-- End #main -->

  @include('wedding-inv.footer')

  @include('wedding-inv.js')
</body>

</html>