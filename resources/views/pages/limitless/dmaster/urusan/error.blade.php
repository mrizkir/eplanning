@extends('layouts.limitless.l_main')
@section('page_title')
    URUSAN
@endsection
@section('page_header')
    <i class="icon-chess-king position-left"></i>
    <span class="text-semibold">
        URUSAN TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.urusan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">DATA</a></li>
    <li><a href="{!!route('urusan.index')!!}">URUSAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('kelompokurusan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection