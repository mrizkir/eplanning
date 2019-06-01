@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD TUJUAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RPJMD TUJUAN TAHUN {{config('eplanning.rpjmd_tahun_mulai')}} - {{config('eplanning.rpjmd_tahun_akhir')}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdtujuan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdtujuan.index')!!}">TUJUAN</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA RPJMD TUJUAN
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('rpjmdtujuan.edit',['id'=>$data->PrioritasTujuanKabID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data RPJMD Tujuan">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data RPJMD Tujuan" data-id="{{$data->PrioritasTujuanKabID}}" data-url="{{route('rpjmdtujuan.index')}}" class="btn btn-danger btn-icon heading-btn btnDelete">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('rpjmdtujuan.create')!!}" class="btn btn-primary btn-info heading-btn btnEdit" title="Tambah RPJMD Tujuan">
                        <i class="icon-googleplus5"></i>
                    </a>
                    <a href="{!!route('rpjmdtujuan.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PrioritasTujuanKabID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->PrioritasTujuanKabID}}</p>
                                </div>                            
                            </div>                        
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE TUJUAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Kd_Tujuan}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA TUJUAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Tujuan}}</p>
                                </div>                            
                            </div>  
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE MISI : </strong></label>
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
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. BUAT / TGL. UBAH: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$data->created_at)}} / {{Helper::tanggal('d/m/Y H:m',$data->updated_at)}}</p>
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
        if (confirm('Apakah Anda ingin menghapus Data RPJMD Tujuan ini ?')) {
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