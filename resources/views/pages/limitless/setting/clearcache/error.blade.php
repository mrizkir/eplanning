@extends('layouts.limitless.l_main')
@section('page_title')
    CLEAR CACHE
@endsection
@section('page_header')
    <i class="icon-database-refresh position-left"></i>
    <span class="text-semibold">
        CLEAR CACHE
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.setting.clearcache.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="#">CACHE</a></li>
    <li><a href="{!!route('clearcache.index')!!}">CLEAR CACHE</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger alert-styled-left alert-bordered">
    <button type="button" class="close" onclick="location.href='{{route('clearcache.index')}}'">×</button>
    {{$errormessage}}
</div>
@endsection