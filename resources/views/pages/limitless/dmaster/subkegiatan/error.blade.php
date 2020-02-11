@extends('layouts.limitless.l_main')
@section('page_title')
    SUB KEGIATAN
@endsection
@section('page_header')
    <i class="icon-code position-left"></i>
    <span class="text-semibold">
        SUB KEGIATAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.subkegiatan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">DATA</a></li>
    <li><a href="{!!route('subkegiatan.index')!!}">SUB KEGIATAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('subkegiatan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection