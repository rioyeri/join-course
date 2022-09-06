<!-- jQuery  -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/detect.js') }}"></script>
<script src="{{ asset('assets/js/fastclick.js') }}"></script>
<script src="{{ asset('assets/js/jquery.blockUI.js') }}"></script>
<script src="{{ asset('assets/js/waves.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>
<script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('assets/js/jquery.scrollTo.min.js') }}"></script>

<!-- Toastr js -->
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>

@yield('js')

<!-- App js -->
<script src="{{ asset('assets/js/jquery.core.js') }}"></script>
<script src="{{ asset('assets/js/jquery.app.js') }}"></script>

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
