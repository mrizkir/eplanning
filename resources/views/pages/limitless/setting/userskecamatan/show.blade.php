@extends('layouts.limitless.l_main')
@section('page_title')
    USERS KECAMATAN
@endsection
@section('page_header')
    <i class="icon-users position-left"></i>
    <span class="text-semibold"> 
        USERS KECAMATAN
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.setting.userskecamatan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">SETTING</a></li>
    <li><a href="{!!route('userskecamatan.index')!!}">USERS KECAMATAN</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA USER KECAMATAN
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('userskecamatan.create')}}" class="btn btn-info btn-icon heading-btn btnTambah" title="Tambah Data Kelompok Urusan">
                        <i class="icon-googleplus5"></i>
                    </a>
                    <a href="{{route('userskecamatan.edit',['id'=>$data->id])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data User Kecamatan">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data User Kecamatan" data-id="{{$data->id}}" data-url="{{route('userskecamatan.index')}}" class="btn btn-danger btn-icon heading-btn btnDelete">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('userskecamatan.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
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
                                <label class="col-md-4 control-label"><strong>USERNAME: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->username}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->name}}</p>
                                </div>                            
                            </div>  
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">     
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>EMAIL: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->email}}</p>
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
    <div class="col-md-12">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">
                    <i class="icon-pencil7 position-left"></i> 
                    TAMBAH KECAMATAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
                </h5>                
            </div>
            <div class="panel-body">
                {!! Form::open(['action'=>['Setting\UsersKecamatanController@store1',$data->id],'method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                                  
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nama Kecamatan :</label> 
                        <div class="col-md-10">
                            <select name="PmKecamatanID" id="PmKecamatanID" class="select">
                                <option></option>
                                @foreach ($daftar_kecamatan as $k=>$item)
                                    <option value="{{$k}}">{{$item}}</option>
                                @endforeach
                            </select>                              
                        </div>
                    </div>                           
                    <div class="form-group">            
                        <div class="col-md-10 col-md-offset-2">                        
                            {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}
                        </div>
                    </div>
                {!! Form::close()!!}
            </div>
        </div>
    </div>    
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.setting.userskecamatan.datatablekecamatan')
    </div>
</div>
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    //styling select
    $('#PmKecamatanID.select').select2({
        placeholder: "PILIH KECAMATAN",
        allowClear:true
    });  
    $(".btnDelete").click(function(ev) {
        if (confirm('Apakah Anda ingin menghapus Data User Kecamatan ini ?')) {
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
    $('#frmdata').validate({
        ignore:[],
        rules: {                      
            PmKecamatanID : {
                required: true,
            }
        },
        messages : {            
            PmKecamatanID : {                
                required: "Mohon di pilih Anggota Kecamatan untuk user ini",
            }
        },        
    });   
    $("#divdatatable").on("click",".btnDeleteKecamatan", function(){
        if (confirm('Apakah Anda ingin menghapus Data Hak Akses pada Kecamatan ini ?')) {
            let url_ = $(this).attr("data-url");
            let id = $(this).attr("data-id");
            $.ajax({            
                type:'post',
                url:url_+'/'+id,
                dataType: 'json',
                data: {
                    "_method": 'DELETE',
                    "_token": token,
                    "id": id,
                    'userkecamatan':true
                },
                success:function(result)
                {                     
                    $('#divdatatable').html(result.datatable); 
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