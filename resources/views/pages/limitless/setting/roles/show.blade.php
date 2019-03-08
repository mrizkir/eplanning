@extends('layouts.limitless.l_main')
@section('page_title')
    USER ROLES
@endsection
@section('page_header')
    <i class="icon-user-tie position-left"></i>
    <span class="text-semibold"> 
        USER ROLES
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.setting.roles.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="{!!route('roles.index')!!}">USER ROLES</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA ROLE
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('roles.edit',['id'=>$data->id])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data Role">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data Role" data-id="{{$data->id}}" data-url="{{route('roles.index')}}" class="btn btn-danger btn-icon heading-btn btnDelete">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('roles.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
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
                                    <p class="form-control-static">{{$data->id}}</p>
                                </div>                            
                            </div>     
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>GUARD: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->guard_name}}</p>
                                </div>                            
                            </div>                        
                        </div>                       
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>ROLE NAME / GUARD: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->name}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>JUMLAH USER: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->jumlah}}</p>
                                </div>                            
                            </div>                      
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        @if (count($data_permission) > 0)
        {!! Form::open(['action'=>'Setting\RolesController@storerolepermission','method'=>'POST','class'=>'form-horizontal','name'=>'frmpermission','id'=>'frmpermission'])!!}
        {{ Form::hidden('role_id', $data->id) }}
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="table-responsive">
                <table id="data" class="table table-striped table-hover">
                    <thead>
                        <tr class="bg-teal-700">                                
                            <th width="55">ID</th>
                            <th>PERMISSION</th>      
                            <th width="100">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody> 
                    @foreach ($data_permission as $key=>$item)
                        <tr>
                            <td>{{$item->id}}</td>                   
                            <td>{{$item->name}}</td>
                            <td>
                                <div class="checkbox">
                                    {{Form::checkbox("chkpermission[]", $item->name,isset($permission_selected[$item->id]),['class'=>'switch'])}}  
                                </div>
                            </td>
                        </tr>
                    @endforeach 
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-right" colspan="3">                                    
                                {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        {!! Form::close()!!}
        @else
        <div class="panel-body">
            <div class="alert alert-info alert-styled-left alert-bordered">
                <span class="text-semibold">Info!</span>
                Belum ada data yang bisa ditampilkan.
            </div>   
        </div>
        @endif 
    </div>
</div>
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/switch.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(".switch").bootstrapSwitch();
    $(".btnDelete").click(function(ev) {
        if (confirm('Apakah Anda ingin menghapus Data Role ini ?')) {
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