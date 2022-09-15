<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <!-- Favicons -->
    <link href="{{ asset('dashboard/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('dashboard/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <title>@yield('title') | Flash Academy</title>

    @yield('css')
    <!-- Notification css (Toastr) -->
    <link href="{{ asset('dashboard/additionalplugins/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('dashboard/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('dashboard/lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('dashboard/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/css/style-responsive.css') }}" rel="stylesheet">
</head>