<!-- js placed at the end of the document so the pages load faster -->
<script src="{{ asset('dashboard/lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('dashboard/lib/jquery-ui-1.9.2.custom.min.js') }}"></script>
<script src="{{ asset('dashboard/lib/bootstrap/js/bootstrap.min.js') }}"></script>
<script class="include" type="text/javascript" src="{{ asset('dashboard/lib/jquery.dcjqaccordion.2.7.js') }}"></script>
<script src="{{ asset('dashboard/lib/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ asset('dashboard/lib/jquery.scrollTo.min.js') }}"></script>
<script src="{{ asset('dashboard/lib/jquery.nicescroll.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/additionalplugins/numberformat/numberformat.js') }}"></script>
<!-- Toastr js -->
<script src="{{ asset('dashboard/additionalplugins/toastr/toastr.min.js') }}"></script>
<!-- Magnific popup -->
<script type="text/javascript" src="{{ asset('dashboard/additionalplugins/magnific-popup/dist/jquery.magnific-popup.min.js') }}"></script>
@yield('js')

<!--common script for all pages-->
<script src="{{ asset('dashboard/lib/common-scripts.js') }}"></script>
<script src="{{ asset('dashboard/lib/numberformat.js') }}"></script>

<!--script for this page-->
@if (session('status'))
    <script>
        var status = "{{session('status')}}";
        // Display a success toast, with a title
        toastr.success(status, 'Success')
    </script>
@elseif(session('warning'))
    <script>
        var status = "{{session('warning')}}";
        // Display a success toast, with a title
        toastr.warning(status, 'Warning!')
    </script>
@elseif(session('failed'))
<script>
    var status = "{{session('failed')}}";
    // Display a success toast, with a title
    toastr.error(status, 'Login Gagal')
</script>
@endif
@if ($errors->any())
    @php
        $er="";
    @endphp
    @foreach ($errors->all() as $error)
        @php
        $er .= "<li>".$error."</li>";
        @endphp
    @endforeach
    <script>
        var error = "<?=$er?>";
        // Display an error toast, with a title
        toastr.error(error, 'Error!!!')
    </script>
@endif
@yield('script-js')