<style>
    .find-box {
        /* padding-right: 50px */
        padding: 15px 40px;
;
    }
    .btn-find {
        position: absolute;
        margin-left: 400px;
        margin-top:4px;
    }

    @media (max-width: 640px) {
        #button-text {
            display:none;
            margin-left:50px;
        }

        .btn-find {
            position: absolute;
            margin-left:250px;
            padding: 10px 10px;
        }

        .find-box::placeholder {
            padding-right:-64px;
        }
    }
</style>

<!-- ======= Hero Section ======= -->
<section id="hero" class="hero">
    <div class="container position-relative" style="margin-bottom:150px; margin-top:150px;">
        <div class="row gy-5" data-aos="fade-in">
            <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-start">
                <h2>Temukan Guru yang sempurna bersama <span>FlashAcademia</span></h2>
                <p>Online atau tatap muka,
                    tentukan guru pilihanmu
                </p>
                <div class="d-flex justify-content-center justify-content-lg-start">
                    {{-- <a href="#about" class="btn-get-started">Get Started</a> --}}
                    <input type="text" class="form-control find-box" name="searchbox" id="searchbox" placeholder="Apa yang ingin Anda Pelajari">
                    <a href="#about" class="btn-get-started btn-find"><i class="fa fa-search" id="button-icon"></i><span id="button-text"> Temukan Guru</span></a>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2">
                <img src="{{ asset('landingpage/assets/img/hero-img.svg') }}" class="img-fluid" alt=""
                    data-aos="zoom-out" data-aos-delay="100">
            </div>
        </div>
    </div>
</section>
<!-- End Hero Section -->
