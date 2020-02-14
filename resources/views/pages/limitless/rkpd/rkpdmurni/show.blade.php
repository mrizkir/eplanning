@extends('layouts.limitless.l_main')
@section('page_title')
    RKPD
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RKPD TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.rkpdmurni.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('rkpdmurni.index')!!}">RKPD</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  
                    DATA RENCANA KERJA KEGIATAN                   
                </h5>
                <div class="heading-elements"> 
                    @if (empty($rkpd->RKPDID))
                    <a id="btnTransfer" href="#" class="btn btn-primary btn-icon heading-btn btnEdit" title="Transfer RENJA Ke RKPD">
                        <i class="icon-play4"></i>
                    </a>
                    @endif                                               
                    <a href="{!!route('rkpdmurni.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>RKPD ID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$rkpd->RKPDID}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>OPD / SKPD: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">[{{$rkpd->kode_organisasi}}] {{$rkpd->OrgNm}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>UNIT KERJA: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">[{{$rkpd->kode_suborganisasi}}] {{$rkpd->SOrgNm}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">                            
                                <label class="col-md-4 control-label"><strong>KELOMPOK URUSAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                        @if ($rkpd->Kd_Urusan==null)
                                            SEMUA URUSAN
                                        @else
                                             [{{$rkpd->Kd_Urusan}}] {{$rkpd->Nm_Urusan}}
                                        @endif                                       
                                    </p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>URUSAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                        @if ($rkpd->Kd_Urusan==null)
                                            SEMUA URUSAN
                                        @else
                                            [{{$rkpd->Kd_Urusan.'.'.$rkpd->Kd_Bidang}}] {{$rkpd->Nm_Bidang}}
                                        @endif                                         
                                    </p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PROGRAM : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                        @if ($rkpd->Kd_Urusan==null)
                                            [{{$rkpd->kode_suborganisasi.'.'.$rkpd->Kd_Prog}}] {{$rkpd->PrgNm}}
                                        @else
                                            [{{$rkpd->Kd_Urusan.'.'.$rkpd->Kd_Bidang.'.'.$rkpd->Kd_Prog}}] {{$rkpd->PrgNm}}
                                        @endif   
                                    </p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KEGIATAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                        @if ($rkpd->Kd_Urusan==null)
                                            [{{$rkpd->kode_suborganisasi.'.'.$rkpd->Kd_Prog.'.'.$rkpd->Kd_SubKeg}}] {{$rkpd->KgtNm}}
                                        @else
                                            [{{$rkpd->kode_kegiatan}}] {{$rkpd->KgtNm}}
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
                                    <p class="form-control-static">{{Helper::formatAngka($rkpd->Sasaran_Angka)}} {{$rkpd->Sasaran_Uraian}}</p>
                                </div>                            
                            </div>   
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>SASARAN KEGIATAN (N+1): </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($rkpd->Sasaran_AngkaSetelah)}} {{$rkpd->Sasaran_UraianSetelah}}</p>
                                </div>                            
                            </div>   
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TARGET (%): </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($rkpd->Target)}}</p>
                                </div>                            
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NILAI (TA-1 / TA / TA+1): </strong></label>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="form-control-static">{{Helper::formatUang($rkpd->NilaiSebelum)}}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="form-control-static" id="pNilaiUsulan">{{Helper::formatUang($rkpd->NilaiUsulan)}}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="form-control-static">{{Helper::formatUang($rkpd->NilaiSetelah)}}</p>
                                        </div>
                                    </div>                                    
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>INDIKATOR KEGIATAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$rkpd->NamaIndikator}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>SUMBER DANA: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$rkpd->Nm_SumberDana}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. BUAT / UBAH: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$rkpd->created_at)}} / {{Helper::tanggal('d/m/Y H:m',$rkpd->updated_at)}}</p>
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
            @include('pages.limitless.rkpd.rkpdmurni.datatablerinciankegiatan')         
        </div>
    </div>    
</div>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    
});
</script>
@endsection