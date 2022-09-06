<!DOCTYPE html>
<html>
    @include('layout.head')


    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">

            @include('layout.header')


            @include('layout.sidebar')



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">

                        @yield('content')

                    </div> <!-- container -->

                </div> <!-- content -->

                <footer class="footer text-right">
                    2021 GPT Tulungagung
                </footer>

            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->


       @include('layout.js')

    </body>
</html>
