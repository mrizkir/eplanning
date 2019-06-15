@extends('layouts.limitless.l_main')
@section('page_title')
    URUSAN
@endsection
@section('page_header')
    <i class="icon-chess-king position-left"></i>
    <span class="text-semibold"> 
        URUSAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.urusan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">DATA</a></li>
    <li><a href="{!!route('urusan.index')!!}">URUSAN</a></li>
    <li class="active">TAMBAH DATA</li>
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                TAMBAH DATA
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route('urusan.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'DMaster\UrusanController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
                <div class="form-group">
                    {{Form::label('KUrsID','KELOMPOK',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('KUrsID', $kelompok_urusan, '',['class'=>'form-control','id'=>'KUrsID'])}}
                        {{Form::hidden('Kode_Bidang','none',['id'=>'Kode_Bidang'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Kd_Bidang','KODE URUSAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_Bidang','',['class'=>'form-control','placeholder'=>'KODE URUSAN','maxlength'=>4])}}
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('Nm_Bidang','NAMA URUSAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Nm_Bidang','',['class'=>'form-control','placeholder'=>'NAMA URUSAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr','',['class'=>'form-control','placeholder'=>'KETERANGAN','rows' => 2, 'cols' => 40])}}
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
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $('#frmdata').validate({
        rules: {
            KUrsID : {
                valueNotEquals : 'none'
            },
            Kd_Bidang : {
                required: true,  
                number: true,
                maxlength: 4              
            },
            Kode_Bidang : {
                required: true,  
                valueNotEquals : 'none'           
            },
            Nm_Bidang : {
                required: true,
                minlength: 5
            }
        },
        messages : {
            KUrsID : {
                valueNotEquals: "Mohon dipilih Kelompok Urusan !"
            },
            Kd_Bidang : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                number: "Mohon input dengan tipe data bilangan bulat",
                maxlength: "Nilai untuk Kode Urusan maksimal 4 digit"
            },
            Kode_Bidang : {
                required: "Mohon dipilih Kelompok Urusan !",
                valueNotEquals: "Mohon dipilih Kelompok Urusan !"
            },
            Nm_Bidang : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            }
        }        
    }); 
    $("#frmdata :input").not('[name=KUrsID]').prop("disabled", true);
    $(document).on('change','#KUrsID',function(ev) {
        ev.preventDefault();  
        KUrsID=$(this).val();
        
        if (KUrsID == 'none')
        {
            $("#frmdata :input").not('[name=KUrsID]').prop("disabled", true);
            $("#Kode_Bidang").val('none');  
        }
        else
        {
            $("#frmdata *").prop("disabled", false);
            $.ajax({
                type:'get',
                url: '{{route('kelompokurusan.index')}}/getkodekelompokurusan/'+KUrsID,
                dataType: 'json',
                success:function(result)
                {          
                    $("#Kode_Bidang").val(result.kodekelompokurusan);  
                },
                error:function(xhr, status, error)
                {   
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });            
        }
    });
});
</script>
@endsection