@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN KECAMATAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        USULAN KECAMATAN TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.musrenbang.aspirasimusrenkecamatan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">ASPIRASI / USULAN</a></li>
    <li><a href="{!!route('aspirasimusrenkecamatan.index')!!}">USULAN KECAMATAN</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA USULAN KECAMATAN
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('aspirasimusrenkecamatan.edit',['id'=>$data->UsulanKecID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data Kegiatan">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data Kegiatan" data-id="{{$data->UsulanKecID}}" data-url="{{route('aspirasimusrenkecamatan.index')}}" class="btn btn-danger btn-icon heading-btn btnDelete">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('aspirasimusrenkecamatan.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->No_usulan}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA KEGIATAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->NamaKegiatan}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>HASIL / OUTPUT: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Output}}</p>
                                </div>                            
                            </div> 
                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NILAI USULAN: </strong></label>
                                <div class="col-md-8">
                                        <p class="form-control-static">{{Helper::formatUang($data->NilaiUsulan)}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>VOLUME: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Target_Angka}} {{$data->Target_Uraian}}</p>
                                </div>                            
                            </div>                             
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PRIORITAS: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{HelperKegiatan::getNamaPrioritas($data->Prioritas)}}</p>
                                </div>                            
                            </div>                
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>JENIS KEGIATAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Prioritas==1?'FISIK':'NON-FISIK'}}</p>
                                </div>                            
                            </div> 
                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>DESA: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Desa}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KECAMATAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Kecamatan}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>LOKASI: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Lokasi}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. BUAT: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$data->created_at)}}</p>
                                </div>                            
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. UBAH: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$data->updated_at)}}</p>
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
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(".btnDelete").click(function(ev) {
        if (confirm('Apakah Anda ingin menghapus Data AspirasiMusrenKecamatan ini ?')) {
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