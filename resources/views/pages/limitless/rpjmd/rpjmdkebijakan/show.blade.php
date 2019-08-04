@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD PRIORITAS / ARAH KEBIJAKAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RPJMD PRIORITAS / ARAH KEBIJAKAN TAHUN {{HelperKegiatan::getRPJMDTahunMulai()}}-{{HelperKegiatan::getRPJMDTahunAkhir()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdkebijakan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdkebijakan.index')!!}">PRIORITAS / ARAH KEBIJAKAN</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA KEBIJAKAN
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('rpjmdkebijakan.edit',['id'=>$data->PrioritasKebijakanKabID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data RPJMD Kebijakan">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data RPJMD Kebijakan" data-id="{{$data->PrioritasKebijakanKabID}}" data-url="{{route('rpjmdkebijakan.index')}}" class="btn btn-danger btn-icon heading-btn btnDeleteKebijakan">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('rpjmdkebijakan.create')!!}" class="btn btn-primary btn-info heading-btn btnEdit" title="Tambah RPJMD Kebijakan">
                        <i class="icon-googleplus5"></i>
                    </a>
                    <a href="{!!route('rpjmdkebijakan.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PrioritasKebijakanKabID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->PrioritasKebijakanKabID}}</p>
                                </div>                            
                            </div>                        
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE KEBIJAKAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Kd_Kebijakan}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA KEBIJAKAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Kebijakan}}</p>
                                </div>                            
                            </div>  
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE STRATEGI : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Kd_Strategi}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA STRATEGI: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Strategi}}</p>
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
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                TAMBAH RELASI KEBIJAKAN DENGAN URUSAN PROGRAM
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route('rpjmdindikatorkinerja.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['action'=>'RPJMD\RPJMDKebijakanController@store1','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
        {{Form::hidden('PrioritasKebijakanKabID',$data->PrioritasKebijakanKabID)}}                                
        <div class="panel-body">
            <div class="form-group">
                {{Form::label('UrsID','URUSAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="UrsID" id="UrsID" class="select">
                        <option></option>
                        @foreach ($daftar_urusan as $k=>$item)
                            <option value="{{$k}}">{{$item}}</option>
                        @endforeach
                    </select>  
                </div>
            </div>
            <div class="form-group">
                {{Form::label('PrgID','PROGRAM',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="PrgID" id="PrgID" class="select">
                        <option></option>
                    </select>  
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="form-group">            
                <div class="col-md-10 col-md-offset-2">                        
                    {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}
                </div>
            </div>            
        </div>
        {!! Form::close()!!}
    </div>
    <div class="panel panel-flat border-top-lg border-top-info border-bottom-info" id="divdatatableprogramkebijakan">
        @include('pages.limitless.rpjmd.rpjmdkebijakan.datatableprogramkebijakan')
    </div> 
</div>    
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $('#UrsID.select').select2({
        placeholder: "PILIH URUSAN",
        allowClear:true
    });
    $('#PrgID.select').select2({
        placeholder: "PILIH PROGRAM",
        allowClear:true
    });
    $("#divdatatableprogramkebijakan").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data Program Kebijakan ini ?')) {
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
                    'programkebijakan':true
                },
                success:function(result){ 
                    if (result.success==1){
                        $('#divdatatableprogramkebijakan').html(result.datatable);                        
                    }else{
                        console.log("Gagal menghapus data Program Kebijakan dengan id "+id);
                    }                    
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }        
    });
    $(document).on('click',".btnDeleteKebijakan", function(ev) {
        if (confirm('Apakah Anda ingin menghapus Data RPJMD Kebijakan ini ?')) {
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
    $(document).on('change','#UrsID',function(ev) {
        ev.preventDefault();   
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {              
                "_token": token,  
                "UrsID": $('#UrsID').val(),
                "PrioritasKebijakanKabID": $('#PrioritasKebijakanKabID').val(),
                "programkebijakan":true
            },
            success:function(result)
            { 
                var daftar_program = result.daftar_program;
                var listitems='<option></option>';
                $.each(daftar_program,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                $('#PrgID').html(listitems);
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });
    $('#frmdata').validate({
        ignore:[],
        rules: {
            UrsID : {
                required: true
            },
            PrgID : {
                required: true
            }
        },
        messages : {         
            UrsID : {
                required: "Mohon untuk di pilih Urusan untuk indikator ini."                
            },
            PrgID : {
                required: "Mohon untuk di pilih Program Pembangunan untuk indikator ini."             
            }                   
        }      
    });
});
</script>
@endsection