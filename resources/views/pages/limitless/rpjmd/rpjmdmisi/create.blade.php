@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD MISI TAHUN {{HelperKegiatan::getRPJMDTahunMulai()}} - {{HelperKegiatan::getRPJMDTahunAkhir()}}
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RPJMD MISI TAHUN {{HelperKegiatan::getRPJMDTahunMulai()}} - {{HelperKegiatan::getRPJMDTahunAkhir()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdmisi.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdmisi.index')!!}">MISI</a></li>    
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
                        <a href="{!!route('rpjmdmisi.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'RPJMD\RPJMDMisiController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
                <div class="form-group">
                    {{Form::label('RpjmdVisiID','VISI',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::select('RpjmdVisiID', $daftar_visi, '',['class'=>'select','id'=>'RpjmdVisiID'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Kd_PrioritasKab','KODE',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Kd_PrioritasKab','',['class'=>'form-control','placeholder'=>'KODE MISI','maxlength'=>'4'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Nm_PrioritasKab','MISI',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Nm_PrioritasKab','',['class'=>'form-control','placeholder'=>'MISI'])}}
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
    AutoNumeric.multiple(['#Kd_PrioritasKab'], {
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
    $('#RpjmdVisiID.select').select2({
        placeholder: "PILIH VISI RPJMD",
        allowClear:true
    });
    $(document).on('change','#RpjmdVisiID',function(ev) {
        ev.preventDefault();
        RpjmdVisiID=$(this).val();        
        $.ajax({
            type:'get',
            url: url_current_page+'/getkodemisi/'+RpjmdVisiID,
            dataType: 'json',
            data: {
                "_token": token,
                "RpjmdVisiID": RpjmdVisiID,
            },
            success:function(result)
            {   
                const element = AutoNumeric.getAutoNumericElement('#Kd_PrioritasKab');
                element.set(result.Kd_PrioritasKab);                                
            },
            error:function(xhr, status, error)
            {   
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });   
    $('#frmdata').validate({
        rules: {
            Kd_PrioritasKab : {
                required: true
            },
            Nm_PrioritasKab : {
                required: true,
                minlength: 2
            }
        },
        messages : {
            Kd_PrioritasKab : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            },
            Nm_PrioritasKab : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            }
        }      
    });     
});
</script>
@endsection