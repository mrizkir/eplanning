<script src="{!!asset('themes/default/assets/jquery/dist/jquery.min.js')!!}"></script>
<script src="{!!asset('themes/default/assets/bootstrap/dist/js/bootstrap.min.js')!!}"></script>
<script src="{!!asset('themes/default/assets/jquery-slimscroll/jquery.slimscroll.min.js')!!}"></script>
<script src="{!!asset('themes/default/assets/fastclick/lib/fastclick.js')!!}"></script>
<script src="{!!asset('themes/default/assets/PACE/pace.min.js')!!}"></script>
@yield('page_asset_js')
<script src="{!!asset('themes/default/assets/admin-lte/dist/js/adminlte.min.js')!!}"></script>
<script type="text/javascript">
    let url_admin="{{route('dashboard.index')}}";
    let url_current_page="{{Helper::getCurrentPageURL()}}";
    let token = "{{ csrf_token() }}";
    let baseUserImageURL = "{{asset('storage/images/users')}}/";
</script>
<script src="{!!asset('themes/default/app.js')!!}"></script>
@yield('page_custom_js')
</body>
</html>