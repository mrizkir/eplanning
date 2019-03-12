@include('layouts.default.l_header')
<body class="hold-transition skin-blue login-page">
@yield('page_content')
<script src="{!!asset('themes/default/assets/jquery/dist/jquery.min.js')!!}"></script>
<script src="{!!asset('themes/default/assets/bootstrap/dist/js/bootstrap.min.js')!!}"></script>
<script src="{!!asset('themes/default/assets/jquery-slimscroll/jquery.slimscroll.min.js')!!}"></script>
<script src="{!!asset('themes/default/assets/fastclick/lib/fastclick.js')!!}"></script>
@yield('page_asset_js')
<script src="{!!asset('themes/default/assets/admin-lte/dist/js/adminlte.min.js')!!}"></script>
@yield('page_custom_js')
<script src="{!!asset('themes/default/app.js')!!}"></script>
</body>
</html>