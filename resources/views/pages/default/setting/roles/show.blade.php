@extends('layouts.default.l_main')
@section('page_title')
    ROLES
@endsection
@section('page_header')
    <i class="fa fa-users"></i> 
    USER ROLES   
@endsection
@section('page-info')
    @include('pages.default.setting.permissions.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="{!!route('roles.index')!!}">USER ROLES</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_asset_css')
<link rel="stylesheet" href="{!!asset('themes/default/assets/admin-lte/plugins/iCheck/minimal/blue.css')!!}">
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="box box-primary with-border">
            <div class="box-header ">
                <h3 class="box-title">
                    <i class="icon-eye"></i> DATA ROLE
                </h3>
                <div class="box-tools">  
                    <a href="{{route('roles.edit',['uuid'=>$data->id])}}" class="btn btn-primary btnEdit" title="Ubah Data Roles">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data Roles" data-id="{{$data->id}}" data-url="{{route('roles.index')}}" class="btn btn-danger btnDelete">
                        <i class='fa fa-trash'></i>
                    </a>
                    <a href="{!!route('roles.index')!!}" class="btn btn-default" title="keluar">
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
        <div class="box box-green">
            <div class="box-header with-border">
                <h3 class="box-title">
                    DAFTAR PERMISSION DARI ROLE "{{$data->name}}"
                </h3>            
                <div class="box-tools"> 
                    
                </div>
            </div>
            @if (count($data_permission) > 0)
            {!! Form::open(['action'=>'Setting\RolesController@storerolepermission','method'=>'POST','class'=>'form-horizontal','name'=>'frmpermission','id'=>'frmpermission'])!!}
            {{ Form::hidden('role_id', $data->id) }}
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
<script src="{!!asset('themes/default/assets/admin-lte/plugins/iCheck/icheck.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $('input[type="checkbox"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue'
    });
    $(document).on('click','.btnDelete', function (ev)
    {
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