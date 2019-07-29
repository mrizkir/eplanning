@extends('layouts.limitless.l_main')
@section('page_title')
    RENSTRA PRIORITAS / ARAH KEBIJAKAN
@endsection
@section('page_header')
    <i class="icon-strategy position-left"></i>
    <span class="text-semibold">
        RENSTRA PRIORITAS / ARAH KEBIJAKAN TAHUN {{HelperKegiatan::getRENSTRATahunMulai()}}-{{HelperKegiatan::getRENSTRATahunAkhir()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.renstra.renstrakebijakan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RENSTRA</a></li>
    <li><a href="{!!route('renstrakebijakan.index')!!}">PRIORITAS / ARAH KEBIJAKAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('renstrakebijakan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection