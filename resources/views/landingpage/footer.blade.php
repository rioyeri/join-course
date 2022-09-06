<!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">

            <div class="col-lg-12 col-md-8 footer-info" style="float: centered;">
                <h2>{{ $profil->first_name }}</h2>
                <h3>{{ $profil->mid_name }} {{ $profil->last_name }}</h3>
                <p>
                    {{ $profil->address }}<br>
                    @if($profil->phone != null)
                        <strong>Phone:</strong> {{ $profil->phone }}<br>
                    @endif
                    @if($profil->email != null)
                        <strong>Email:</strong> <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $profil->email }}" target="_blank">{{ $profil->email }}</a><br>
                    @endif
                </p>
                <div class="social-links mt-3">
                @foreach($links as $link)
                    <a href="{{ $link->link }}" class="{{ $link->category }}" target="_blank"><i class="{{ $link->get_category->icon }}"></i></a>
                @endforeach
                </div>
            </div>

            </div>
        </div>
    </div>

    <div class="container">
        <div class="copyright">
            &copy; Copyright <strong><span>Shuffle</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/bootstrap-3-one-page-template-free-shuffle/ -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
        </div>
  </footer><!-- End Footer -->
