@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN MUSRENBANG KABUPATEN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        USULAN MUSRENBANG KABUPATEN TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.musrenbang.usulanmusrenkab.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('usulanmusrenkab.index')!!}">USULAN MUSRENBANG KABUPATEN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('usulanmusrenkab.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection