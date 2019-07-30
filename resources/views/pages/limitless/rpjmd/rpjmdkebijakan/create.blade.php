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
                        <a href="{!!route('rpjmdkebijakan.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'RPJMD\RPJMDKebijakanController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
                <div class="form-group">
                    {{Form::label('PrioritasStrategiKabID','STRATEGI',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <select name="PrioritasStrategiKabID" id="PrioritasStrategiKabID" class="select">
                            <option></option>
                            @foreach ($daftar_strategi as $k=>$item)
                                <option value="{{$k}}">{{$item}}</option>
                            @endforeach
                        </select>  
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Kd_Kebijakan','KODE KEBIJAKAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_Kebijakan','',['class'=>'form-control','placeholder'=>'KODE KEBIJAKAN RPJMD','maxlength'=>4])}}
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('Nm_Kebijakan','NAMA KEBIJAKAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Nm_Kebijakan','',['class'=>'form-control','placeholder'=>'NAMA KEBIJAKAN RPJMD'])}}
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
    AutoNumeric.multiple(['#Kd_Kebijakan'], {
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
    $('#PrioritasStrategiKabID.select').select2({
        placeholder: "PILIH STRATEGI RPJMD",
        allowClear:true
    });
    $('#frmdata').validate({
        ignore: [],
        rules: {
            PrioritasStrategiKabID : {
                required: true,  
                valueNotEquals : 'none'
            },
            Kd_Kebijakan : {
                required: true,  
                number: true,
                maxlength: 4              
            },
            Kode_Kebijakan : {
                required: true,  
                valueNotEquals : 'none'           
            },
            Nm_Kebijakan : {
                required: true,
                minlength: 5
            }
        },
        messages : {
            PrioritasStrategiKabID : {
                required: "Mohon dipilih Strategi RPJMD !",  
                valueNotEquals: "Mohon dipilih Strategi RPJMD !"
            },
            Kd_Kebijakan : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                number: "Mohon input dengan tipe data bilangan bulat",
                maxlength: "Nilai untuk Kode Urusan maksimal 4 digit"
            },
            Kode_Kebijakan : {
                required: "Mohon dipilih Strategi RPJMD !",
                valueNotEquals: "Mohon dipilih Strategi RPJMD !"
            },
            Nm_Kebijakan : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            }
        }        
    });      
   
});
</script>
@endsection