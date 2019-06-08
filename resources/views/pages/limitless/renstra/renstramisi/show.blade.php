@extends('layouts.limitless.l_main')
@section('page_title')
    RENSTRA MISI TAHUN {{config('eplanning.renstra_tahun_mulai')}} - {{config('eplanning.renstra_tahun_akhir')}}
@endsection
@section('page_header')
    <i class="icon-strategy position-left"></i>
    <span class="text-semibold"> 
        RENSTRA MISI TAHUN {{config('eplanning.renstra_tahun_mulai')}} - {{config('eplanning.renstra_tahun_akhir')}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.renstra.renstramisi.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RENSTRA</a></li>
    <li><a href="{!!route('renstramisi.index')!!}">MISI</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA RENSTRA MISI
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('renstramisi.edit',['id'=>$data->PrioritasKabID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data RENSTRA Misi">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data RENSTRA Misi" data-id="{{$data->PrioritasKabID}}" data-url="{{route('renstramisi.index')}}" class="btn btn-danger btn-icon heading-btn btnDelete">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('renstramisi.create')!!}" class="btn btn-primary btn-info heading-btn btnEdit" title="Tambah RENSTRA Misi">
                        <i class="icon-googleplus5"></i>
                    </a>
                    <a href="{!!route('renstramisi.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>RENSTRA MISI ID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->PrioritasKabID}}</p>
                                </div>                            
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE MISI: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Kd_PrioritasKab}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA MISI: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_PrioritasKab}}</p>
                                </div>                            
                            </div>  
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
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
        if (confirm('Apakah Anda ingin menghapus Data RENSTRA Misi ini ?')) {
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