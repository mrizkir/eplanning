@extends('layouts.limitless.l_main')
@section('page_title')
    PROGRAM
@endsection
@section('page_header')
    <i class="icon-codepen position-left"></i>
    <span class="text-semibold"> 
        PROGRAM TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.program.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('program.index')!!}">PROGRAM</a></li>
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
                        <a href="{!!route('program.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'DMaster\ProgramController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
                <div class="form-group">
                    {{Form::label('Jns','PROGRAM UNTUK',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">                        
                        <label class="radio-inline">
                            {{Form::radio('Jns','0',true,['class'=>'styled'])}}
                            Seluruh Urusan
                        </label> 
                        <label class="radio-inline">
                            {{Form::radio('Jns','1',false,['class'=>'styled'])}}
                            Per Urusan
                        </label>                                             
                    </div>
                </div>       
                <div class="form-group">
                    {{Form::label('UrsID','URUSAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <select name="UrsID" id="UrsID" class="select" disabled>
                            <option></option>
                            @foreach ($daftar_urusan as $k=>$item)
                                <option value="{{$k}}">{{$item}}</option>
                            @endforeach
                        </select>                        
                    </div>
                </div>         
                <div class="form-group">
                    {{Form::label('Kd_Prog','KODE PROGRAM',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_Prog','',['class'=>'form-control','placeholder'=>'KODE PROGRAM','maxlength'=>4])}}
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('PrgNm','NAMA PROGRAM',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('PrgNm','',['class'=>'form-control','placeholder'=>'NAMA PROGRAM'])}}
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
                        {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}
                    </div>
                </div>
            {!! Form::close()!!}
        </div>
    </div>
</div>   
@endsection
@section('page_asset_js')
<script src="{!!asset('limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('limitless/assets/js/uniform.min.js')!!}"></script>
<script src="{!!asset('limitless/assets/js/select2.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    //styling radio button
    $(".styled").uniform();
    //styling select
    $('.select').select2({
        placeholder: "Pilih Urusan",
        allowClear:true
    });

    $(document).on('change','#Jns',function (ev){
        jns = $(this).val();
        if (jns == 1)
        {
            $("#UrsID").prop("disabled", false);
        }
        else
        {
            $("#UrsID").prop("disabled", true);
            $("#UrsID").val('').trigger('change');
        }
    });
    $('#frmdata').validate({
        ignore: [],  
        rules: {
            UrsID : {
                required : true
            },
            Kd_Prog : {
                required: true,  
                number: true,
                maxlength: 4              
            },
            Kode_Program : {
                required: true,  
                valueNotEquals : 'none'           
            },
            PrgNm : {
                required: true,
                minlength: 5
            }
        },
        messages : {
            UrsID : {
                required: "Mohon dipilih Urusan !"
            },
            Kd_Prog : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                number: "Mohon input dengan tipe data bilangan bulat",
                maxlength: "Nilai untuk Kode Program maksimal 4 digit"
            },
            Kode_Bidang : {
                required: "Mohon dipilih Urusan !",
                valueNotEquals: "Mohon dipilih Urusan !"
            },
            PrgNm : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            }
        }        
    });   
});
</script>
@endsection