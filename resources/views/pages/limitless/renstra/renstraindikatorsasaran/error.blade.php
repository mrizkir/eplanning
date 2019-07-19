@extends('layouts.limitless.l_main')
@section('page_title')
    RENSTRA INDIKATOR SASARAN TAHUN {{config('eplanning.renstra_tahun_mulai')}} - {{config('eplanning.renstra_tahun_akhir')}}
@endsection
@section('page_header')
    <i class="icon-strategy position-left"></i>
    <span class="text-semibold">
        RENSTRA INDIKATOR SASARAN TAHUN {{config('eplanning.renstra_tahun_mulai')}} - {{config('eplanning.renstra_tahun_akhir')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.renstra.renstraindikatorsasaran.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RENSTRA</a></li>
    <li><a href="{!!route('renstraindikatorsasaran.index')!!}">RENSTRA INDIKATOR SASARAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('renstraindikatorsasaran.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection