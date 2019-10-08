@extends('layouts.limitless.l_main')
@section('page_title')
    KOTA
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        KOTA TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.kota.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('kota.index')!!}">KOTA</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('Kota.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection