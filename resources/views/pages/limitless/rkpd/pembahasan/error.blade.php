@extends('layouts.limitless.l_main')
@section('page_title')
    PEMBAHASAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        PEMBAHASAN TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.pembahasan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('pembahasan.index')!!}">PEMBAHASAN</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('pembahasan.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection