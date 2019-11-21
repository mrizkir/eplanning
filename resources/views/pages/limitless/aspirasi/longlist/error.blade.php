@extends('layouts.limitless.l_main')
@section('page_title')
    LONG LIST
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        LONG LIST TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.aspirasi.longlist.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">ASPIRASI</a></li>
    <li><a href="{!!route('longlist.index')!!}">LONG LIST</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('longlist.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection