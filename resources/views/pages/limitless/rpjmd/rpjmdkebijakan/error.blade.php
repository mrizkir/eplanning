@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD PRIORITAS / ARAH KEBIJAKAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        RPJMD PRIORITAS / ARAH KEBIJAKAN TAHUN {{config('eplanning.rpjmd_tahun_mulai')}}-{{config('eplanning.rpjmd_tahun_akhir')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdkebijakan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdkebijakan.index')!!}">PRIORITAS / ARAH KEBIJAKAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('rpjmdkebijakan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection