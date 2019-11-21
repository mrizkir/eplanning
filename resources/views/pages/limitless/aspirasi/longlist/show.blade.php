@extends('layouts.limitless.l_main')
@section('page_title')
    LONG LIST
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        LONG LIST TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.aspirasi.longlist.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">ASPIRASI</a></li>
    <li><a href="{!!route('longlist.index')!!}">LONG LIST</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA LONG LIST
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('longlist.create')}}" class="btn btn-info btn-icon heading-btn btnTambah" title="Tambah Data Long List">
                        <i class="icon-googleplus5"></i>
                    </a>
                    @if ($data->Privilege==0)
                    <a href="{{route('longlist.edit',['id'=>$data->LongListID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data Long List">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data Long List" data-id="{{$data->LongListID}}" data-url="{{route('longlist.index')}}" class="btn btn-danger btn-icon heading-btn btnDelete">
                        <i class='icon-trash'></i>
                    </a>
                    @endif                    
                    <a href="{!!route('longlist.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>LONGLISTID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->LongListID}}</p>
                                </div>                            
                            </div>    
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA KEGIATAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->KgtNm}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>SASARAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($data->Sasaran_Angka)}} {{$data->Sasaran_Uraian}}</p>
                                </div>                            
                            </div> 
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>LOKASI: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Lokasi}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>OPD / SKPD: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">[{{$data->kode_organisasi}}] {{$data->OrgNm}}</p>
                                </div>                            
                            </div>                                                
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KETERANGAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Descr}}</p>
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
        if (confirm('Apakah Anda ingin menghapus Data Long List ini ?')) {
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