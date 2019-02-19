@extends('layouts.default.l_main')
@section('page_title')
    USER ROLES
@endsection
@section('page_header')
    <i class="fa fa-lock"></i> 
    USER ROLES
@endsection
@section('page-info')
    @include('pages.default.setting.roles.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="{!!route('roles.index')!!}">USER ROLES</a></li>
    <li class="active">ERROR</li>
@endsection
@section('page_content')
<div class="alert alert-danger">
    <button type="button" class="close" onclick="location.href='{{route('roles.index')}}'">×</button>
    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
    {{$errormessage}}
</div>
@endsection