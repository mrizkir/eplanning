@extends('layouts.limitless.l_main')
@section('page_title')
    RAPAT
@endsection
@section('page_header')
    <i class="icon-comments position-left"></i>
    <span class="text-semibold"> 
        RAPAT
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rapat.info')
@endsection
@section('page_breadcrumb')    
    <li><a href="{!!route('rapat.index')!!}">RAPAT</a></li>
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
                        <a href="{!!route('rapat.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>['RapatController@update',$data->RapatID],'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
                <div class="form-group">
                    {{Form::label('Judul','JUDUL',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Judul',$data['Judul'],['class'=>'form-control','placeholder'=>'JUDUL'])}}
                    </div>
                </div> 
                <div class="form-group">
                    {{Form::label('Isi','ISI RAPAT',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Isi',$data['Isi'],['class'=>'form-control','placeholder'=>'ISI RAPAT'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('pimpinan','PIMPINAN RAPAT',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('pimpinan',$data['pimpinan'],['class'=>'form-control','placeholder'=>'PIMPINAN RAPAT'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('anggota','ANGGOTA',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('anggota',$data['anggota'],['class'=>'form-control','placeholder'=>'ANGGOTA RAPAT'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Tempat_Rapat','TEMPAT RAPAT',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Tempat_Rapat',$data['Tempat_Rapat'],['class'=>'form-control','placeholder'=>'TEMPAT RAPAT'])}}
                    </div>
                </div>                                
                <div class="form-group">
                    {{Form::label('Tanggal_Rapat','TANGGAL RAPAT',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                            {{Form::text('Tanggal_Rapat',Helper::tanggal('d/m/Y',$data['Tanggal_Rapat']),['class'=>'form-control daterange-single','placeholder'=>'TANGGAL RAPAT'])}}
                        </div>
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
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/moment.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/daterangepicker.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {    
    // Single picker
    $('.daterange-single').daterangepicker({ 
        singleDatePicker: true,
        format: 'dd/mm/yyyy'
    });
    $('#frmdata').validate({
        ignore:[],
        rules: {
            Judul : {
                required: true,
                minlength: 2
            },
            Isi : {
                required: true,
            },
            pimpinan : {
                required: true,
            },
            anggota : {
                required: true,
            },
            Tempat_Rapat : {
                required: true,
            },
        },
        messages : {
            Judul : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            },
            Isi : {
                required: "Mohon untuk di isi karena ini diperlukan.",
            },
            pimpinan : {
                required: "Mohon untuk di isi karena ini diperlukan.",
            },            
            anggota : {
                required: "Mohon untuk di isi karena ini diperlukan.",
            },            
            Tempat_Rapat : {
                required: "Mohon untuk di isi karena ini diperlukan.",
            },            
        },        
    });   
});
</script>
@endsection