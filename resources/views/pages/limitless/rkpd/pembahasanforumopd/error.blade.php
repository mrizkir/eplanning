@extends('layouts.limitless.l_main')
@section('page_title')
    PEMBAHASAN FORUM OPD / SKPD
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        PEMBAHASAN FORUM OPD / SKPD TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.pembahasanforumopd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('pembahasanforumopd.index')!!}">PEMBAHASAN FORUM OPD / SKPD</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('pembahasanforumopd.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection