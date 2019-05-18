@extends('layouts.limitless.l_main')
@section('page_title')
    PEMBAHASAN MUSRENBANG KABUPATEN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">  
        PEMBAHASAN MUSRENBANG KABUPATEN TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.musrenbang.pembahasanmusrenkab.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="{!!route('pembahasanmusrenkab.index')!!}">PEMBAHASAN MUSRENBANG KABUPATEN</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DETAIL RINCIAN RENCANA KEGIATAN 
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('pembahasanmusrenkab.edit',['id'=>$renja->RenjaRincID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data Usulan Musren Kabupaten">
                        <i class="icon-pencil7"></i>
                    </a>
                </div>
            </div>
            <div class="panel-body">
                
            </div>
        </div>
    </div>
</div>
@endsection