@extends('layouts.limitless.l_main')
@section('page_title')
    RENSTRA PROGRAM, KEGIATAN, DAN PENDANAAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RENSTRA PROGRAM, KEGIATAN, DAN PENDANAAN TAHUN {{HelperKegiatan::getRENSTRATahunMulai()}} - {{HelperKegiatan::getRENSTRATahunAkhir()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.renstra.renstraprogramkegiatanpendanaan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RENSTRA</a></li>
    <li><a href="{!!route('renstraprogramkegiatanpendanaan.index')!!}">PROGRAM, KEGIATAN, DAN PENDANAAN</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA RENSTRA PROGRAM, KEGIATAN, DAN PENDANAAN
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('renstraprogramkegiatanpendanaan.edit',['id'=>$data->RenstraIndikatorID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data RENSTRA Indikator Sasaran">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data RENSTRA Indikator Sasaran" data-id="{{$data->RenstraIndikatorID}}" data-url="{{route('renstraprogramkegiatanpendanaan.index')}}" class="btn btn-danger btn-icon heading-btn btnDelete">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('renstraprogramkegiatanpendanaan.create')!!}" class="btn btn-primary btn-info heading-btn btnEdit" title="Tambah RENSTRA Indikator Sasaran">
                        <i class="icon-googleplus5"></i>
                    </a>
                    <a href="{!!route('renstraprogramkegiatanpendanaan.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>RENSTRAINDIKATORID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->RenstraIndikatorID}}</p>
                                </div>                            
                            </div>                        
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>URUSAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Bidang}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA PROGRAM: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->PrgNm}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA OPD/SKPD: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->OrgNm}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>INDIKATOR KINERJA RPJMD: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->IndikatorKinerja}}</p>
                                </div>                            
                            </div> 
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">                                                       
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>ARAH KEBIJAKAN RENSTRA: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_RenstraKebijakan}}</p>
                                </div>                            
                            </div>     
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA INDIKATOR: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->NamaIndikator}}</p>
                                </div>                            
                            </div>     
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KETERANGAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Descr}}</p>
                                </div>                            
                            </div>     
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. BUAT: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$data->created_at)}}</p>
                                </div>                            
                            </div>                         
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. BUAT / TGL. UBAH: </strong></label>
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
        if (confirm('Apakah Anda ingin menghapus Data RENSTRA Indikator Sasaran ini ?')) {
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