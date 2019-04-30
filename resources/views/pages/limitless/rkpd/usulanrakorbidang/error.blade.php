@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN RAKOR BIDANG OPD/SKPD
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        USULAN RAKOR BIDANG OPD/SKPD TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.usulanrakorbidang.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('usulanrakorbidang.index')!!}">USULAN RAKOR BIDANG OPD/SKPD</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('usulanrakorbidang.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection