@extends('layouts.limitless.l_main')
@section('page_title')
    PERMISSIONS
@endsection
@section('page_header')
    <i class="icon-users position-left"></i>
    <span class="text-semibold">
        PERMISSIONS TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.setting.permissions.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('permissions.index')!!}">PERMISSIONS</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('kelompokurusan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection