@extends('layouts.limitless.l_main')
@section('page_title')
    USERS BAPELITBANG (SUPER ADMIN)
@endsection
@section('page_header')
    <i class="icon-users position-left"></i>
    <span class="text-semibold">
        USERS BAPELITBANG (SUPER ADMIN)
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.setting.users.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('users.index')!!}">USERS BAPELITBANG (SUPER ADMIN)</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('users.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection