@extends('layouts.limitless.l_main')
@section('page_title')
    {{$page_title}}
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        PEMBAHASAN {{$page_title}} TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.pembahasanrenja.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">WORKFLOW</a></li>
    <li><a href="{!!route(Helper::getNameOfPage('index'))!!}">{{$page_title}}</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA USULAN {{$page_title}}
                </h5>
                <div class="heading-elements">                    
                    <a href="{!!route(Helper::getNameOfPage('index'))!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>RENJA ID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$renja->RenjaID}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>OPD / SKPD: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">[{{$renja->kode_organisasi}}] {{$renja->OrgNm}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>UNIT KERJA: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">[{{$renja->kode_suborganisasi}}] {{$renja->SOrgNm}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">                            
                                <label class="col-md-4 control-label"><strong>KELOMPOK URUSAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                        @if ($renja->Kd_Urusan==null)
                                            SEMUA URUSAN
                                        @else
                                             [{{$renja->Kd_Urusan}}] {{$renja->Nm_Urusan}}
                                        @endif                                       
                                    </p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>URUSAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                        @if ($renja->Kd_Urusan==null)
                                            SEMUA URUSAN
                                        @else
                                            [{{$renja->Kd_Urusan.'.'.$renja->Kd_Bidang}}] {{$renja->Nm_Bidang}}
                                        @endif                                         
                                    </p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PROGRAM : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                        @if ($renja->Kd_Urusan==null)
                                            [{{$renja->kode_suborganisasi.'.'.$renja->Kd_Prog}}] {{$renja->PrgNm}}
                                        @else
                                            [{{$renja->Kd_Urusan.'.'.$renja->Kd_Bidang.'.'.$renja->Kd_Prog}}] {{$renja->PrgNm}}
                                        @endif   
                                    </p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KEGIATAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                        @if ($renja->Kd_Urusan==null)
                                            [{{$renja->kode_suborganisasi.'.'.$renja->Kd_Prog.'.'.$renja->Kd_SubKeg}}] {{$renja->SubKgtNm}}
                                        @else
                                            [{{$renja->kode_subkegiatan}}] {{$renja->SubKgtNm}}
                                        @endif   
                                    </p>
                                </div>                            
                            </div>  
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>SASARAN KEGIATAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($renja->Sasaran_Angka)}} {{$renja->Sasaran_Uraian}}</p>
                                </div>                            
                            </div>   
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>SASARAN KEGIATAN (N+1): </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($renja->Sasaran_AngkaSetelah)}} {{$renja->Sasaran_UraianSetelah}}</p>
                                </div>                            
                            </div>   
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TARGET (%): </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($renja->Target)}}</p>
                                </div>                            
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NILAI (TA-1 / TA / TA+1): </strong></label>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="form-control-static">{{Helper::formatUang($renja->NilaiSebelum)}}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="form-control-static" id="pNilaiUsulan">{{Helper::formatUang($renja->NilaiUsulan)}}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="form-control-static">{{Helper::formatUang($renja->NilaiSetelah)}}</p>
                                        </div>
                                    </div>                                    
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>INDIKATOR KEGIATAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$renja->NamaIndikator}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>SUMBER DANA: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$renja->Nm_SumberDana}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. BUAT / UBAH: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$renja->created_at)}} / {{Helper::tanggal('d/m/Y H:m',$renja->updated_at)}}</p>
                                </div>                            
                            </div>                     
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info" id="divdatatableindikatorkinerja">
            @include('pages.limitless.rkpd.pembahasanrenja.datatableindikatorkinerja')         
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info" id="divdatatablerinciankegiatan">
            @include('pages.limitless.rkpd.pembahasanrenja.datatablerinciankegiatan')
        </div>
    </div>
</div>
@endsection