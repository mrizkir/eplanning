@extends('layouts.limitless.l_main')
@section('page_title')
    PEMILIK POKOK PIKIRAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        PEMILIK POKOK PIKIRAN TAHUN PERENCANAAN {{config('eplanning.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.pokir.pemilikpokokpikiran.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">POKIR / RESES</a></li>
    <li><a href="{!!route('pemilikpokokpikiran.index')!!}">PEMILIK POKOK PIKIRAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('pemilikpokokpikiran.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection