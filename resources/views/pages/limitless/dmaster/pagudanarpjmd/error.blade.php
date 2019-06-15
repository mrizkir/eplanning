@extends('layouts.limitless.l_main')
@section('page_title')
    PAGUDANARPJMD
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        PAGUDANARPJMD TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.pagudanarpjmd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('pagudanarpjmd.index')!!}">PAGUDANARPJMD</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('kelompokurusan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection