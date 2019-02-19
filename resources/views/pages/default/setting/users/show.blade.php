@extends('layouts.default.l_main')
@section('page_title')
    USERS
@endsection
@section('page_header')
    <i class="fa fa-lock"></i> 
    USERS
@endsection
@section('page-info')
    @include('pages.default.setting.users.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="{!!route('users.index')!!}">USERS</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_asset_css')
<link rel="stylesheet" href="{!!asset('default/assets/admin-lte/plugins/iCheck/minimal/blue.css')!!}">
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="box box-primary with-border">
            <div class="box-header ">
                <h3 class="box-title">
                    <i class="icon-eye"></i> DATA USERS
                </h3>
                <div class="box-tools">  
                    <a href="{{route('users.edit',['id'=>$data->id])}}" class="btn btn-primary btnEdit" title="Ubah Data User">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data User" data-id="{{$data->id}}" data-url="{{route('users.index')}}" class="btn btn-danger btnDelete">
                        <i class='fa fa-trash'></i>
                    </a>
                    <a href="{!!route('users.index')!!}" class="btn btn-default" title="keluar">
                        <i class="fa fa-close"></i>
                    </a>            
                </div>
            </div>
            <div class="box-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>USER ID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->id}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>USERNAME: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->username}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>ROLE: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                       @php
                                            $roles=$data->roles->pluck('name', 'name')->toArray();
                                            foreach ($roles as $v )
                                            {
                                                echo "[$v] ";
                                            }
                                       @endphp                                        
                                    </p>
                                </div>                            
                            </div>                                   
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->name}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>EMAIL: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->email}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>CREATED / UPDATED: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{\Carbon\Carbon::parse($data->created_at)->format('d/m/Y H:m')}} / {{\Carbon\Carbon::parse($data->updated_at)->format('d/m/Y H:m')}}</p>
                                </div>                            
                            </div>                                   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box box-green">
            <div class="box-header with-border">
                <h3 class="box-title">
                    DAFTAR PERMISSION
                </h3>            
                <div class="box-tools"> 
                    
                </div>
            </div>
            @if (count($data_permission) > 0)
            {!! Form::open(['action'=>'Setting\UsersController@storeuserpermission','method'=>'post','class'=>'form-horizontal','name'=>'formdata','id'=>'formdata'])!!}
            {{ Form::hidden('user_id', $data->id) }}
                <div class="box-body table-responsive no-padding"> 
                    <table id="data" class="table table-striped table-hover">
                        <thead>
                            <tr>                                
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
                                        {{Form::checkbox("chkpermission[]", $item->name,isset($permission_selected[$item->id]),['class'=>'minimal'])}}  
                                    </div>
                                </td>
                            </tr>
                        @endforeach 
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right" colspan="3">
                                    {{ Form::button('<i class="fa fa-save"></i> Simpan', ['type' => 'submit', 'class' => 'btn btn-primary'] )  }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            {!! Form::close()!!}
            @else
            <div class="box-body">
                <div class="alert alert-info">
                    <h4>
                        <i class="icon fa fa-info"></i>
                        Info!
                    </h4>            
                    Belum ada data permission yang bisa ditampilkan.
                </div>   
            </div>
            @endif 
        </div>
    </div>
</div>
@endsection
@section('page_asset_js')
<script src="{!!asset('default/assets/admin-lte/plugins/iCheck/icheck.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $('input[type="checkbox"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue'
    });
    $(".btnDelete").click(function(ev) {
        if (confirm('Apakah Anda ingin menghapus Data User ini ?')) {
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
    $(document).on('click','.btnInitializePermissionUser',function(ev) {
        ev.preventDefault();
        $.ajax({
            type:'post',
            url:url_current_page+'/initializeserpermission',
            dataType: 'json',
            data: {                
                "_token": token,                
                "id":{{$data->id}},                
            },
            success:function(result)
            {          
                $(selector).html(result.datatable);           
            },
            error:function(xhr, status, error)
            {
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });
});
</script>
@endsection