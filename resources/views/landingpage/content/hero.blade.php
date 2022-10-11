<style>
    /* .find-box {
        padding: 15px 40px;
;
    }
    .btn-find {
        position: absolute;
        margin-left: 400px;
        margin-top:4px;
    } */

    .wrapper {
        max-width: 550px;
        /* margin: 150px auto; */
        margin: auto auto 180px 0px;
    }

    .wrapper .search-input{
        background: #fff;
        width: 50%;
        border-radius: 5px;
        position: absolute;
        box-shadow: 0px 1px 5px 3px rgba(0,0,0,0.12);
    }

    .search-input input {
        height: 55px;
        width: 100%;
        outline: none;
        border: none;
        border-radius: 5px;
        padding: 0 60px 0 20px;
        font-size: 18px;
        box-shadow: 0px 1px 5px rgba(0,0,0,0.1)
    }

    .search-input.active input{
        border-radius: 5px 5px 0 0;
    }

    .search-input .autocom-box{
        padding: 0;
        max-height: 280px;
        overflow-y: auto;
        opacity: 0;
        pointer-events: none;
    }

    .search-input.active .autocom-box {
        padding: 10px 8px;
        opacity: 1;
        pointer-events: auto;
    }

    .autocom-box li{
        list-style: none;
        padding: 8px 12px;
        width: 100%;
        height: 100%;
        cursor: default;
        border-radius: 3px;
        display: none;
    }

    .search-input.active .autocom-box li {
        display: block;
    }

    .autocom-box li:hover{
        background: #efefef;
    }

    .search-input .icon{
        position: absolute;
        right: 0px;
        top: 0px;
        height: 55px;
        width: 55px;
        text-align: center;
        line-height: 55px;
        font-size: 20px;
        color: #008374;
        cursor: pointer;
    }

    @media (max-width: 1200px) {
        .wrapper .search-input{
            background: #fff;
            width: 100%;
            border-radius: 5px;
            position: absolute;
            box-shadow: 0px 1px 5px 3px rgba(0,0,0,0.12);
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
                {{-- <div class="d-flex justify-content-center justify-content-lg-start">
                    <input type="text" class="form-control find-box" name="searchbox" id="searchbox" placeholder="Apa yang ingin Anda Pelajari">
                    <a href="#about" class="btn-get-started btn-find"><i class="fa fa-search" id="button-icon"></i><span id="button-text"> Temukan Guru</span></a>
                </div> --}}
                <div class="wrapper">
                    <div class="search-input">
                        <input type="text" id="searchbox" placeholder="Apa yang ingin Anda Pelajari" onkeyup="searchTeacherOrSubject()">
                        <div class="autocom-box">
                        </div>
                        <div class="icon"><i class="fa fa-search"></i></div>
                    </div>
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
