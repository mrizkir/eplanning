@extends('layouts.limitless.l_main')
@section('page_title')
    RENSTRA STRATEGI
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RENSTRA STRATEGI TAHUN {{config('eplanning.renstra_tahun_mulai')}} - {{config('eplanning.renstra_tahun_akhir')}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.renstra.renstrastrategi.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RENSTRA</a></li>
    <li><a href="{!!route('renstrastrategi.index')!!}">STRATEGI</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA RENSTRA STRATEGI
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('renstrastrategi.edit',['id'=>$data->RenstraStrategiID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data RENSTRA Strategi">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data RENSTRA Strategi" data-id="{{$data->RenstraStrategiID}}" data-url="{{route('renstrastrategi.index')}}" class="btn btn-danger btn-icon heading-btn btnDelete">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('renstrastrategi.create')!!}" class="btn btn-primary btn-info heading-btn btnEdit" title="Tambah RENSTRA Strategi">
                        <i class="icon-googleplus5"></i>
                    </a>
                    <a href="{!!route('renstrastrategi.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>RENSTRASTRATEGIID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->RenstraStrategiID}}</p>
                                </div>                            
                            </div>                        
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE STRATEGI : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Kd_RenstraStrategi}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA STRATEGI: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_RenstraStrategi}}</p>
                                </div>                            
                            </div>  
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE SASARAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Kd_RenstraSasaran}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA SASARAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_RenstraSasaran}}</p>
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
        if (confirm('Apakah Anda ingin menghapus Data RENSTRA Strategi ini ?')) {
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