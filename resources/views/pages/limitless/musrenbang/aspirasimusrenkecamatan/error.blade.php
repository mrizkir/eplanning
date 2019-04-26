@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN KECAMATAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        USULAN KECAMATAN TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.musrenbang.aspirasimusrenkecamatan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">ASPIRASI / USULAN</a></li>
    <li><a href="{!!route('aspirasimusrenkecamatan.index')!!}">USULAN KECAMATAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('aspirasimusrenkecamatan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection