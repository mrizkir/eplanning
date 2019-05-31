@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD STRATEGI
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RPJMD STRATEGI TAHUN PERENCANAAN {{config('eplanning.tahun_perencanaan')}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdstrategi.info')
@endsection
@section('page_breadcrumb')
    <li><a href="{!!route('rpjmdstrategi.index')!!}">RPJMD STRATEGI</a></li>
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
                        <a href="{!!route('rpjmdstrategi.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>['RPJMD\RPJMDStrategiController@update',$data->PrioritasStrategiKabID],'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}        
                <div class="form-group">
                    <label class="col-md-2 control-label">TUJUAN RPJMD :</label> 
                    <div class="col-md-10">
                        <select name="PrioritasSasaranKabID" id="PrioritasSasaranKabID" class="select">
                            <option></option>
                            @foreach ($daftar_sasaran as $k=>$item)
                                <option value="{{$k}}""{{$k==$data->PrioritasSasaranKabID ?' selected':''}}>{{$item}}</option>
                            @endforeach
                        </select>                                
                    </div>
                </div>   
                <div class="form-group">
                    {{Form::label('Kd_Strategi','KODE STRATEGI',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_Strategi',$data->Kd_Strategi,['class'=>'form-control','placeholder'=>'Kode Strategi','maxlength'=>'4'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Nm_Strategi','NAMA STRATEGI',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Nm_Strategi',$data->Nm_Strategi,['class'=>'form-control','placeholder'=>'Nama Strategi'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr',$data->Descr,['class'=>'form-control','placeholder'=>'KETERANGAN','rows' => 2, 'cols' => 40])}}
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
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    AutoNumeric.multiple(['#Kd_Strategi'], {
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
    $('#PrioritasSasaranKabID.select').select2({
        placeholder: "PILIH TUJUAN RPJMD",
        allowClear:true
    });
    $('#frmdata').validate({
        ignore: [],
        rules: {
            PrioritasSasaranKabID : {
                required: true,
                valueNotEquals: 'none'
            },
            Kd_Strategi : {
                required: true,
            },
            Nm_Strategi : {
                required: true,
                minlength: 2
            }
        },
        messages : {
            PrioritasSasaranKabID : {
                required: "Mohon untuk di pilih karena ini diperlukan.",
                valueNotEquals: "Mohon untuk di pilih karena ini diperlukan.",      
            },
            Kd_Strategi : {
                required: "Mohon untuk di isi karena ini diperlukan.",
            },
            Nm_Strategi : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            }
        }      
    });   
});
</script>
@endsection