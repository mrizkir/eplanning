@extends('layouts.limitless.l_main')
@section('page_title')
    RENSTRA ARAH KEBIJAKAN  {{config('eplanning.renstra_tahun_mulai')}} - {{config('eplanning.renstra_tahun_akhir')}}
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RENSTRA ARAH KEBIJAKAN TAHUN {{config('eplanning.renstra_tahun_mulai')}} - {{config('eplanning.renstra_tahun_akhir')}}  
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.renstra.renstrakebijakan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RENSTRA</a></li>
    <li><a href="{!!route('renstrakebijakan.index')!!}">ARAH KEBIJAKAN</a></li>
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
                        <a href="{!!route('renstrakebijakan.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'RENSTRA\RENSTRAKebijakanController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                            
                <div class="form-group">
                    <label class="col-md-2 control-label">STRATEGI :</label> 
                    <div class="col-md-10">
                        <select name="RenstraStrategiID" id="RenstraStrategiID" class="select">
                            <option></option>
                            @foreach ($daftar_strategi as $k=>$item)
                                <option value="{{$k}}">{{$item}}</option>
                            @endforeach
                        </select>                                
                    </div>
                </div>   
                <div class="form-group">
                    {{Form::label('Kd_RenstraKebijakan','KODE ARAH KEBIJAKAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_RenstraKebijakan','',['class'=>'form-control','placeholder'=>'Kode Kebijakan','maxlength'=>'4'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Nm_RenstraKebijakan','NAMA ARAH KEBIJAKAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Nm_RenstraKebijakan','',['class'=>'form-control','placeholder'=>'Nama Kebijakan'])}}
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
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    AutoNumeric.multiple(['#Kd_RenstraKebijakan'], {
                                        allowDecimalPadding: false,
                                        minimumValue:0,
                                        maximumValue:9999,
                                        numericPos:true,
                                        decimalPlaces : 0,
                                        digitGroupSeparator : '',
                                        showWarnings:false,
                                        unformatOnSubmit: true,
                                        modifyValueOnWheel:false
                                    });
    $('#RenstraStrategiID.select').select2({
        placeholder: "PILIH STRATEGI",
        allowClear:true
    });
    $('#frmdata').validate({
        ignore: [],
        rules: {
            RenstraStrategiID : {
                required: true,
                valueNotEquals: 'none'
            },
            Kd_RenstraKebijakan : {
                required: true,
            },
            Nm_RenstraKebijakan : {
                required: true,
                minlength: 2
            }
        },
        messages : {
            RenstraStrategiID : {
                required: "Mohon untuk di pilih karena ini diperlukan.",
                valueNotEquals: "Mohon untuk di pilih karena ini diperlukan.",      
            },
            Kd_RenstraKebijakan : {
                required: "Mohon untuk di isi karena ini diperlukan.",
            },
            Nm_RenstraKebijakan : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            }
        }      
    });   
});
</script>
@endsection