@extends('layouts.limitless.l_main')
@section('page_title')
    RENSTRA TUJUAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        RENSTRA TUJUAN TAHUN {{config('eplanning.renstra_tahun_mulai')}} - {{config('eplanning.renstra_tahun_akhir')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.renstra.renstratujuan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RENSTRA</a></li>
    <li><a href="{!!route('renstratujuan.index')!!}">TUJUAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('renstratujuan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection