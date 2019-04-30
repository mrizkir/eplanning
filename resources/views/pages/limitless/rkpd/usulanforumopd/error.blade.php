@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN FORUM OPD/SKPD
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        USULAN FORUM OPD/SKPD TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.usulanforumopd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('usulanforumopd.index')!!}">USULAN FORUM OPD/SKPD</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('usulanforumopd.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection