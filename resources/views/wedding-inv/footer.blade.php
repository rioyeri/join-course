<style>
  svg{
    margin-bottom:-1px;
  }
</style>
{{-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 180" style="stroke: transparent; stroke-width: 0px;"><path fill="#74929b" fill-opacity="0.9" d="M0,32L48,42.7C96,53,192,75,288,85.3C384,96,480,96,576,80C672,64,768,32,864,58.7C960,85,1056,171,1152,176C1248,181,1344,107,1392,69.3L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg> --}}
<svg width="100%" height="100%" id="svg" viewBox="0 0 1440 330" xmlns="http://www.w3.org/2000/svg" class="transition duration-300 ease-in-out delay-150"><path d="M 0,400 C 0,400 0,133 0,133 C 102.04615384615386,155.8846153846154 204.0923076923077,178.76923076923077 273,176 C 341.9076923076923,173.23076923076923 377.67692307692306,144.8076923076923 436,138 C 494.32307692307694,131.1923076923077 575.1999999999999,146 677,137 C 778.8000000000001,127.99999999999999 901.523076923077,95.19230769230768 984,94 C 1066.476923076923,92.80769230769232 1108.7076923076922,123.23076923076923 1178,135 C 1247.2923076923078,146.76923076923077 1343.646153846154,139.8846153846154 1440,133 C 1440,133 1440,400 1440,400 Z" stroke="none" stroke-width="0" fill="#74929b88" class="transition-all duration-300 ease-in-out delay-150 path-0"></path><path d="M 0,400 C 0,400 0,266 0,266 C 65.43333333333334,242.21538461538464 130.86666666666667,218.43076923076927 217,234 C 303.1333333333333,249.56923076923073 409.9666666666666,304.49230769230763 499,313 C 588.0333333333334,321.50769230769237 659.2666666666668,283.6 741,273 C 822.7333333333332,262.4 914.9666666666667,279.10769230769233 982,282 C 1049.0333333333333,284.89230769230767 1090.8666666666668,273.96923076923076 1163,269 C 1235.1333333333332,264.03076923076924 1337.5666666666666,265.0153846153846 1440,266 C 1440,266 1440,400 1440,400 Z" stroke="none" stroke-width="0" fill="#74929bff" class="transition-all duration-300 ease-in-out delay-150 path-1"></path></svg>
<!-- ======= Footer ======= -->
<footer class="footer">
    <div class="container">
      <div class="row">
        <div class="text-center">
          <h1 class="text-white">Undangan Digital</h1>
          <h4 style="margin-top: 20px;">Pernikahan</h4>
          <div id="logo">
            @if ($invitation->format == 1)
              <h1><span>{{ $invitation->groom_nickname }}</span>&<span>{{ $invitation->bride_nickname }}</span></h1>
            @else
              <h1><span>{{ $invitation->bride_nickname }}</span>&<span>{{ $invitation->groom_nickname }}</span></h1>
            @endif

          </div>
        </div>
      </div>
    </div>
  </footer><!-- End  Footer -->

  
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>