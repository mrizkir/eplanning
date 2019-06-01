@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD STRATEGI
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        RPJMD STRATEGI TAHUN PERENCANAAN {{config('eplanning.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdstrategi.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdstrategi.index')!!}">STRATEGI</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('rpjmdstrategi.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection