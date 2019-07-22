@extends('layouts.limitless.l_main')
@section('page_title')
    TAHUN PERENCANAAN / ANGGARAN
@endsection
@section('page_header')
    <i class=" icon-calendar2 position-left"></i>
    <span class="text-semibold">
        TAHUN PERENCANAAN / ANGGARAN
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.ta.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">ANEKA DATA</a></li>
    <li><a href="{!!route('ta.index')!!}">TAHUN PERENCANAAN / ANGGARAN</a></li>
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
                        <a href="{!!route('ta.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
        {!! Form::open(['action'=>'DMaster\TAController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                            
            <div class="form-group">
                {{Form::label('TACd','TAHUN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('TACd','',['class'=>'form-control','placeholder'=>'TAHUN','maxlength'=>4])}}
                </div>
            </div>  
            <div class="form-group">
                {{Form::label('TANm','NAMA TAHUN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('TANm','',['class'=>'form-control','placeholder'=>'NAMA TAHUN PERENCANAAN / ANGGARAN'])}}
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
            TACd : {
                required: true,  
                number: true,
                maxlength: 4              
            },
            TANm : {
                required: true,
                minlength: 5
            }
        },
        messages : {
            TACd : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                number: "Mohon input dengan tipe data bilangan bulat",
                maxlength: "Nilai untuk Kode Urusan maksimal 4 digit"
            },
            TANm : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            }
        }      
    });   
});
</script>
@endsection