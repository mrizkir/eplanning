<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
    <title>@yield('page_title')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{!!asset('default/assets/bootstrap/dist/css/bootstrap.min.css')!!}">    
    <link rel="stylesheet" href="{!!asset('default/assets/font-awesome/css/font-awesome.min.css')!!}">    
    <link rel="stylesheet" href="{!!asset('default/assets/Ionicons/css/ionicons.min.css')!!}">    
    <link rel="stylesheet" href="{!!asset('default/assets/admin-lte/dist/css/AdminLTE.min.css')!!}">  
    <link rel="stylesheet" href="{!!asset('default/assets/admin-lte/dist/css/skins/skin-blue.min.css')!!}">  
    <link rel="stylesheet" href="{!!asset('default/assets/PACE/themes/red/pace-theme-loading-bar.css')!!}">
    @yield('page_asset_css')
	  <link href="{!!asset('default/app.css')!!}" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>