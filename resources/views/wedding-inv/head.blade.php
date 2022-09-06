<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
  
    <title>Pernikahan {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- Favicons -->
    {{-- <link href="{{ asset('assets_eStartup/img/favicon.png') }}" rel="icon"> --}}
    <link href="{{ asset('multimedia/'.$invitation->invitation_id.'/icon.ico') }}" rel="icon">
    <link href="{{ asset('multimedia/'.$invitation->invitation_id.'/apple-touch-icon.png') }}" rel="apple-touch-icon">
  
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Roboto:100,300,400,500,700|Philosopher:400,400i,700,700i" rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets_eStartup/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_eStartup/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_eStartup/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_eStartup/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_eStartup/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
  
    <!-- Template Main CSS File -->
    <link href="{{ asset('assets_eStartup/css/style.css') }}" rel="stylesheet">
    <audio id="audio" style="width:0px; height:0px;"><source src="{{ asset('multimedia/'.$invitation->invitation_id.'/'.$complement->song) }}" type="audio/mpeg"></audio>
  
    <!-- =======================================================
    * Template Name: eStartup - v4.7.0
    * Template URL: https://bootstrapmade.com/estartup-bootstrap-landing-page-template/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
  </head>

  <style>
    .mute{
      display: block;
      position: fixed;
      bottom: 70px;
      right: 20px;
      color: #ccc;
      font-size: 20px;
      z-index: 99;
    }
    body{
      overflow:hidden;
    }
</style>
