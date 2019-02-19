@extends('layouts.default.l_main')
@section('page_title')
    USER PERMISSIONS
@endsection
@section('page_header')
    <i class="fa fa-lock"></i> 
    USER PERMISSIONS
@endsection
@section('page-info')
    @include('pages.default.setting.permissions.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="{!!route('permissions.index')!!}">USER PERMISSIONS</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-eye"></i> 
                    DATA PERMISSION
                </h3>
                <div class="box-tools"> 
                    <a href="javascript:;" title="Hapus Data Permission" data-id="{{$data->id}}" data-url="{{route('permissions.index')}}" class="btn btn-danger btnDelete">
                        <i class='fa fa-trash'></i>
                    </a>
                    <a href="{!!route('permissions.index')!!}" class="btn btn-default" title="keluar">
                        <i class="fa fa-close"></i>
                    </a>            
                </div>
            </div>
            <div class="box-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>ID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->id}}</p>
                                </div>                            
                            </div>                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAME / GUARD: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->name}} / {{$data->guard_name}}</p>
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
        if (confirm('Apakah Anda ingin menghapus Data Permission ini ?')) {
            let url_ = $(this).attr("data-url");
            let id = $(this).attr("data-id");
            let token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({            
                type:'POST',
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