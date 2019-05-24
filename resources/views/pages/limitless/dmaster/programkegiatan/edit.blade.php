@extends('layouts.limitless.l_main')
@section('page_title')
    KEGIATAN
@endsection
@section('page_header')
    <i class="icon-code position-left"></i>
    <span class="text-semibold"> 
        KEGIATAN TAHUN PERENCANAAN {{config('eplanning.tahun_perencanaan')}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.programkegiatan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">DATA</a></li>
    <li><a href="{!!route('programkegiatan.index')!!}">KEGIATAN</a></li>
    <li class="active">UBAH DATA</li>
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                UBAH DATA
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>
                        <a href="{!!route('programkegiatan.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>['DMaster\ProgramKegiatanController@update',$data->KgtID],'method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}        
                {{Form::hidden('_method','PUT')}}
                <div class="form-group">
                    {{Form::label('PrgID','PROGRAM',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('PrgID', $daftar_program, $data['PrgID'],['class'=>'select','id'=>'PrgID'])}}
                        {{Form::hidden('Kode_Program','none',['id'=>'Kode_Program'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Kd_Keg','KODE KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_Keg',$data['Kd_Keg'],['class'=>'form-control','placeholder'=>'KODE KEGIATAN','maxlength'=>4])}}
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('KgtNm','NAMA KEGIATAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('KgtNm',$data['KgtNm'],['class'=>'form-control','placeholder'=>'NAMA KEGIATAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr',$data['Descr'],['class'=>'form-control','placeholder'=>'KETERANGAN','rows' => 2, 'cols' => 40])}}
                    </div>
                </div>
                <div class="form-group">            
                    <div class="col-md-10 col-md-offset-2">                        
                        {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] )  }}                        
                    </div>
                </div>
            {!! Form::close()!!}
        </div>
    </div>
</div>  
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    //styling select
    $('.select').select2({
        placeholder: "PILIH PROGRAM",
        allowClear:true
    });
    $('#frmdata').validate({
        ignore:[],
        rules: {
            PrgID : {
                required : true,
            },
            Kd_Keg : {
                required: true,  
                number: true,
                maxlength: 4              
            },
            KgtNm : {
                required: true,
                minlength: 5      
            }
        },
        messages : {
            PrgID : {
                required: "Mohon dipilih Program !"
            },
            Kd_Keg : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                number: "Mohon input dengan tipe data bilangan bulat",
                maxlength: "Nilai untuk Kode Urusan maksimal 4 digit"
            },
            Kode_Program : {
                required: true,  
                valueNotEquals : 'none'           
            },
            KgtNm : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            }
        }     
    });       
    $(document).on('change','#PrgID',function(ev) {
        ev.preventDefault();  
        PrgID=$(this).val();
        if (PrgID == null)
        {
            $("#frmdata :input").not('[name=PrgID]').prop("disabled", true);
            $("#Kode_Program").val('none');  
        }
        else
        {
            $("#frmdata *").prop("disabled", false);
            // $.ajax({
            //     type:'get',
            //     url: '{{route('kelompokurusan.index')}}/getkodekelompokurusan/'+KUrsID,
            //     dataType: 'json',
            //     success:function(result)
            //     {          
            //         $("#Kode_Bidang").val(result.kodekelompokurusan);  
            //     },
            //     error:function(xhr, status, error)
            //     {   
            //         console.log(parseMessageAjaxEror(xhr, status, error));                           
            //     },
            // });            
        }
    });
});
</script>
@endsection