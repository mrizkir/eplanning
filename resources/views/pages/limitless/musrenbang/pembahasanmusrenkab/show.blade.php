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
                    <i class="icon-eye"></i>  
                    DETAIL RINCIAN RENCANA KEGIATAN 
                    @if ($renja->Privilege==1))
                        <span class="label label-success label-rounded">SUDAH DI TRANSFER KE VERIFIKASI TAPD</span>
                    @endif
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('pembahasanmusrenkab.edit',['id'=>$renja->RenjaRincID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data Usulan">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="{!!route('pembahasanmusrenkab.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>  
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>RENJA RINCIAN ID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$renja->RenjaRincID}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NO: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$renja->No}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>URAIAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$renja->Uraian}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NILAI PAGU: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatUang($renja->Jumlah4)}}</p>                               
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>SASARAN KEGIATAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($renja->Sasaran_Angka4)}} {{$renja->Sasaran_Uraian4}}</p>
                                </div>                            
                            </div>                                
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal"> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TARGET (%): </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($renja->Target4)}}</p>
                                </div>                            
                            </div>                             
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>STATUS: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                        @include('layouts.limitless.l_status_kegiatan')
                                    </p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. BUAT: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$renja->created_at)}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. UBAH: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$renja->updated_at)}}</p>
                                </div>                            
                            </div>                     
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection