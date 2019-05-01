@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN MUSRENBANG KABUPATEN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        USULAN MUSRENBANG KABUPATEN TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.musrenbang.usulanmusrenkab.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">ASPIRASI / USULAN</a></li>
    <li><a href="{!!route('usulanmusrenkab.index')!!}">USULAN MUSRENBANG KABUPATEN</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA USULAN MUSRENBANG KABUPATEN
                </h5>
                <div class="heading-elements">                       
                    <a href="{{route('usulanmusrenkab.edit',['id'=>$renja->RenjaID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data Usulan Forum OPD / SKPD">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data Usulan Forum OPD / SKPD" data-id="{{$renja->RenjaID}}" data-url="{{route('usulanmusrenkab.index')}}" class="btn btn-danger btn-icon heading-btn btnDeleteRenja">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('usulanmusrenkab.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
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
                                <label class="col-md-4 control-label"><strong>KELOMPOK URUSAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">[{{$renja->Kd_Urusan}}] {{$renja->Nm_Urusan}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>URUSAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">[{{$renja->Kd_Urusan.'.'.$renja->Kd_Bidang}}] {{$renja->Nm_Bidang}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PROGRAM : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">[{{$renja->Kd_Urusan.'.'.$renja->Kd_Bidang.'.'.$renja->Kd_Prog}}] {{$renja->PrgNm}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KEGIATAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">[{{$renja->kode_kegiatan}}] {{$renja->KgtNm}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>SASARAN KEGIATAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($renja->Sasaran_Angka3)}} {{$renja->Sasaran_Uraian3}}</p>
                                </div>                            
                            </div>    
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>SASARAN KEGIATAN (N+1): </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($renja->Sasaran_AngkaSetelah)}} {{$renja->Sasaran_UraianSetelah}}</p>
                                </div>                            
                            </div>   
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TARGET (%): </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($renja->Target3)}}</p>
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
                                            <p class="form-control-static">{{Helper::formatUang($renja->NilaiUsulan3)}}</p>
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
            @include('pages.limitless.musrenbang.usulanmusrenkab.datatableindikatorkinerja')         
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info" id="divdatatablerinciankegiatan">
            @include('pages.limitless.musrenbang.usulanmusrenkab.datatablerinciankegiatan')
        </div>
    </div>
</div>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(".btnDeleteRenja").click(function(ev) {
        if (confirm('Apakah Anda ingin menghapus Data Usulan Forum OPD / SKPD ini ?')) {
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
    
});
</script>
@endsection