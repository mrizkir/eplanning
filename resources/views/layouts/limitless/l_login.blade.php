@include('layouts.limitless.l_header')
<body class="hold-transition skin-blue login-page">
@yield('page_content')
<script src="{!!asset('assets/limitless/assets/jquery/dist/jquery.min.js')!!}"></script>
<script src="{!!asset('assets/limitless/assets/bootstrap/dist/js/bootstrap.min.js')!!}"></script>
<script src="{!!asset('assets/limitless/assets/jquery-slimscroll/jquery.slimscroll.min.js')!!}"></script>
<script src="{!!asset('assets/limitless/assets/fastclick/lib/fastclick.js')!!}"></script>
@yield('page_asset_js')
<script src="{!!asset('assets/limitless/assets/admin-lte/dist/js/adminlte.min.js')!!}"></script>
@yield('page_custom_js')
<script src="{!!asset('assets/limitless/app.js')!!}"></script>
</body>
</html>