@extends('layouts.limitless.l_main')
@section('page_title')
    KELOMPOKURUSAN
@endsection
@section('page_header')
    <i class="fa fa-lock"></i> 
    KELOMPOKURUSAN
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.kelompokurusan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('kelompokurusan.index')!!}">KELOMPOKURUSAN</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="box box-primary with-border">
            <div class="box-header ">
                <h3 class="box-title">
                    <i class="icon-eye"></i> DATA KELOMPOKURUSAN
                </h3>
                <div class="box-tools">  
                    <a href="{{route('kelompokurusan.edit',['id'=>$data->kelompokurusan_id])}}" class="btn btn-primary btnEdit" title="Ubah Data KelompokUrusan">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data KelompokUrusan" data-id="{{$data->kelompokurusan_id}}" data-url="{{route('kelompokurusan.index')}}" class="btn btn-danger btnDelete">
                        <i class='fa fa-trash'></i>
                    </a>
                    <a href="{!!route('kelompokurusan.index')!!}" class="btn btn-default" title="keluar">
                        <i class="fa fa-close"></i>
                    </a>            
                </div>
            </div>
            <div class="box-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>kelompokurusan id: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->kelompokurusan_id}}</p>
                                </div>                            
                            </div>                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>replaceit: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">replaceit</p>
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
        if (confirm('Apakah Anda ingin menghapus Data KelompokUrusan ini ?')) {
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