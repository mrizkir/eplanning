@extends('layouts.limitless.l_main')
@section('page_title')
    RAPAT
@endsection
@section('page_header')
    <i class="icon-comments position-left"></i>
    <span class="text-semibold"> 
        RAPAT
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.rapat.info')
@endsection
@section('page_breadcrumb')    
    <li><a href="{!!route('rapat.index')!!}">RAPAT</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA RAPAT
                </h5>                
                <div class="heading-elements">   
                    <a href="{{route('rapat.create')}}" class="btn btn-info btn-icon heading-btn btnTambah" title="Tambah Data Kelompok Urusan">
                        <i class="icon-googleplus5"></i>
                    </a>
                    <a href="{{route('rapat.edit',['id'=>$data->RapatID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data Rapat">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data Rapat" data-id="{{$data->RapatID}}" data-url="{{route('rapat.index')}}" class="btn btn-danger btn-icon heading-btn btnDelete">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('rapat.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>ID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->RapatID}}</p>
                                </div>                            
                            </div>    
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TANGGAL RAPAT: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Tanggal_Rapat}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TA: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->TA}}</p>
                                </div>                            
                            </div>  
                        </div>                        
                    </div>                   
                </div>
            </div>
        </div>
    </div>    
</div>
<div class="panel">
    <div class="panel-body">
        <div class="content-group-lg">
            <h3 class="text-semibold mb-5">
                <a href="#" class="text-default">{{$data->Judul}}</a>
            </h3>
            <div class="content-group">
                {{$data->Isi}}
            </div>
        </div>        
    </div>
</div>
<!-- About author -->
<div class="panel panel-flat">
    <div class="panel-heading">
        <h6 class="panel-title">ANGGOTA</h6>
    </div>
    <div class="media panel-body no-margin">
        <div class="media-body">
            <h6 class="media-heading text-semibold">{{$data->anggota}}</h6>
        </div>
    </div>
</div>
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(".btnDelete").click(function(ev) {
        if (confirm('Apakah Anda ingin menghapus Data Rapat ini ?')) {
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