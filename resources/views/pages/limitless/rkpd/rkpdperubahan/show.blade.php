@extends('layouts.limitless.l_main')
@section('page_title')
    {{$page_title}}
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        USULAN {{$page_title}} TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.rkpdperubahan.info')
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
                    <a href="{{route(Helper::getNameOfPage('create'))}}" class="btn btn-info btn-icon heading-btn btnTambah" title="Tambah Usulan Kegiatan">
                        <i class="icon-googleplus5"></i>
                    </a> 
                    @if ($rkpd->Privilege==0)
                    <a href="{{route(Helper::getNameOfPage('edit'),['uuid'=>$rkpd->RKPDID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data Usulan {{$page_title}}">
                        <i class="icon-pencil7"></i>
                    </a>                    
                    @if ($rkpd->EntryLvl==5 && $rkpd->Status==3)    
                    <a href="javascript:;" title="Hapus Data Usulan {{$page_title}}" data-id="{{$rkpd->RKPDID}}" data-url="{{route(Helper::getNameOfPage('index'))}}" class="btn btn-danger btn-icon heading-btn btnDeleteRenja">
                        <i class='icon-trash'></i>
                    </a>
                    @endif
                    @endif
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
            @include('pages.limitless.rkpd.rkpdperubahan.datatableindikatorkinerja')         
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info" id="divdatatablerinciankegiatan">
            @include('pages.limitless.rkpd.rkpdperubahan.datatablerinciankegiatan')
        </div>
    </div>
</div>
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(document).on('click',".btnDeleteRenja", function(ev) {
        ev.preventDefault();
        if (confirm('Apakah Anda ingin menghapus Data Usulan {{$page_title}} ini ?')) {
            let url_ = $(this).attr("data-url");
            let id = $(this).attr("data-id");
            let token = $('meta[name="csrf-token"]').attr('content');            
            $.ajax({            
                type:'post',
                url:url_+'/'+id,
                dataType: 'json',
                data: {
                    "_method": 'DELETE',
                    "_token": token,
                    "id": id,
                    "pid":'rkpd'
                },
                success:function(data){ 
                    window.location.replace(url_);                        
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }
    });
    $("#divdatatableindikatorkinerja").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data Indikator Kegiatan {{$page_title}} ini ?')) {
            let url_ = $(this).attr("data-url");
            let id = $(this).attr("data-id");
            $.ajax({            
                type:'post',
                url:url_+'/'+id,
                dataType: 'json',
                data: {
                    "_method": 'DELETE',
                    "_token": token,
                    "id": id,
                    'indikatorkinerja':true
                },
                success:function(result){ 
                    if (result.success==1){
                        $('#divdatatableindikatorkinerja').html(result.datatable);                        
                    }else{
                        console.log("Gagal menghapus data indikator kinerja {{$page_title}} dengan id "+id);
                    }                    
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }        
    });
    $("#divdatatablerinciankegiatan").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data Rincian Kegiatan {{$page_title}} ini ?')) {
            let url_ = $(this).attr("data-url");
            let id = $(this).attr("data-id");
            $.ajax({            
                type:'post',
                url:url_+'/'+id,
                dataType: 'json',
                data: {
                    "_method": 'DELETE',
                    "_token": token,
                    "id": id,
                    'rinciankegiatan':true
                },
                success:function(result){ 
                    if (result.success==1){
                        $('#divdatatablerinciankegiatan').html(result.datatable); 
                        
                        $('#pNilaiUsulan').html(result.NilaiUsulan);     
                        
                        new AutoNumeric ('#pNilaiUsulan',{
                                                            allowDecimalPadding: false,
                                                            decimalCharacter: ",",
                                                            digitGroupSeparator: ".",
                                                            showWarnings:false
                                                        });
                    }else{
                        console.log("Gagal menghapus data rincian kegiatan {{$page_title}} dengan id "+id);
                    }                    
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }        
    });
});
</script>
@endsection