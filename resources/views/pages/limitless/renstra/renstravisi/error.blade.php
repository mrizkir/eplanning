@extends('layouts.limitless.l_main')
@section('page_title')
    RENSTRAVISI
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        RENSTRAVISI TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.renstra.renstravisi.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('renstravisi.index')!!}">RENSTRAVISI</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('kelompokurusan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection