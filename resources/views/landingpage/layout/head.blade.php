<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="description">
  <meta content="" name="keywords">

  <title>Flash Academia</title>

  <!-- Favicons -->
  <link href="{{ asset('landingpage/assets/img/favicon.ico') }}" rel="icon" type="image/x-icon">
  <link href="{{ asset('landingpage/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('landingpage/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('landingpage/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('landingpage/assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('landingpage/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('landingpage/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Notification css (Toastr) -->
  <link href="{{ asset('dashboard/additionalplugins/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
  <!-- Select2 -->
  <link href="{{ asset('dashboard/additionalplugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
  <!-- Multi Item Selection examples -->
  <link href="{{ asset('dashboard/additionalplugins/datatables/select.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
  <!--Magnific popup-->
  <link rel="stylesheet" href="{{ asset('dashboard/additionalplugins/magnific-popup/dist/magnific-popup.css') }}"/>


  <!--external css-->
  <link href="{{ asset('dashboard/lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
  <!-- Template Main CSS File -->
  <link href="{{ asset('landingpage/assets/css/main.css') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <script type="application/ld+json">
    {
      "@context":"https://schema.org",
      "@graph":[
        {
          "@type":"Organization",
          "@id":"https://flashacademia.com/#organization",
          "name":"flash Academia",
          "url":"https://flashacademia.com/",
          "sameAs":
          [
            "https://facebook.com/Flash-Academia-105087401511212/",
            "https://www.instagram.com/flashacademia/",
            "https://www.youtube.com/channel/UCNKhMpWSiUzCJgD3KHoxsUA?sub_confirmation=1"
          ],
          "logo":
          {
            "@type":"ImageObject",
            "@id":"https://flashacademia.com/#logo",
            "inLanguage":"en-US",
            "url":"https://flashacademia.com/landingpage/assets/img/flashacademia-logo.webp",
            "width":144,
            "height":144,
            "caption":"flashacademia.com"
          },
          "image":
          {
            "@id":"https://flashacademia.id/#logo"
          }
        },
        {
          "@type":"WebSite",
          "@id":"https://flashacademia.com/#website",
          "url":"https://flashacademia.com/",
          "name":"flashacademia.com",
          "description":"Flashacademia.com",
          "publisher":
        {
          "@id":"https://flashacademia.com/#organization"
        },
        "potentialAction":
          [
            {
              "@type":"SearchAction",
              "target":"https://flashacademia.com/search/{search_term_string}",
              "query-input":"required name=search_term_string"
            }
          ],
          "inLanguage":"en-US"
        },
        {
          "@type":"ImageObject",
          "@id":"https://flashacademia.com/#primaryimage",
          "inLanguage":"en-US",
          "url":"https://www.flashacademia.com/landingpage/assets/img/olimpiade/3.jpg",
          "width":251,"height":252
        },
        {
          "@type":"WebPage",
          "@id":"https://flashacademia.com/#webpage",
          "url":"https://flashacademia.com/",
          "name":"Flash Academia",
          "isPartOf":
          {
            "@id":"https://flashacademia.com/#website"
          },
          "about":
          {
            "@id":"https://flashacademia.com/#organization"
          },
          "primaryImageOfPage":
          {
            "@id":"https://flashacademia.com/#primaryimage"
          },
          "inLanguage":"en-US",
          "potentialAction":
          [
            {
              "@type":"ReadAction",
              "target":["https://flashacademia.com/"]
            }
          ]
        }
      ]
    }
  </script>

  <!-- =======================================================
  * Template Name: Impact - v1.0.0
  * Template URL: https://bootstrapmade.com/impact-bootstrap-business-website-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <!-- Google tag (gtag.)9)-->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-Y4KW53C62C"></script>
  <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-Y4KW53C62C');
  </script>
</head>