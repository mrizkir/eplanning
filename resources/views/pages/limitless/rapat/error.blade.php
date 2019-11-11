@extends('layouts.limitless.l_main')
@section('page_title')
    RAPAT
@endsection
@section('page_header')
    <i class="icon-comments position-left"></i>
    <span class="text-semibold">
        RAPAT
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rapat.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('rapat.index')!!}">RAPAT</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('rapat.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection