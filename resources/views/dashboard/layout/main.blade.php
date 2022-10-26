<!DOCTYPE html>
<html>
    @include('dashboard.layout.head')
    <body>
        <section id="container">
            @include('dashboard.layout.header')
            @include('dashboard.layout.sidebar')
            <!-- **********************************************************************************************************************************************************
                MAIN CONTENT
                *********************************************************************************************************************************************************** -->
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper">
                    <h3><i class="fa fa-angle-right"></i> @yield('title')</h3>
                    <div class="row mt">
                        <div class="col-lg-12">
                            @yield('content')
                        </div>
                    </div>
                </section>
                <!-- /wrapper -->
            </section>
            <!-- /MAIN CONTENT -->
            <!--main content end-->
        </section>
        <!-- END wrapper -->
       @include('dashboard.layout.js')
    </body>
</html>
