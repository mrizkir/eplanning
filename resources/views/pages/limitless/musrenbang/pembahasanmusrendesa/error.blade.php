@extends('layouts.limitless.l_main')
@section('page_title')
    PEMBAHASAN MUSRENBANG DESA
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        PEMBAHASAN MUSRENBANG DESA TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.musrenbang.pembahasanmusrendesa.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">PEMBAHASAN</a></li>    
    <li><a href="{!!route('pembahasanmusrendesa.index')!!}">MUSRENBANG DESA</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('pembahasanmusrendesa.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection