@extends('layouts.limitless.l_main')
@section('page_title')
    PAGU ANGGARAN ANGGOTA DEWAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        PAGU ANGGARAN ANGGOTA DEWAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.paguanggarandewan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">ANEKA DATA</a></li>
    <li><a href="{!!route('paguanggarandewan.index')!!}">PAGU ANGGARAN ANGGOTA DEWAN</a></li>
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
                        <a href="{!!route('paguanggarandewan.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>['DMaster\PaguAnggaranDewanController@update',$data->PaguAnggaranDewanID],'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}        
                <div class="form-group">
                    {{Form::label('PemilikPokokID','ANGGOTA DEWAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <select name="PemilikPokokID" id="PemilikPokokID" class="select">
                            <option></option>
                            @foreach ($daftar_anggota as $k=>$item)
                                <option value="{{$k}}"{{$k==$data['PemilikPokokID']?' selected':''}}>{{$item}}</option>
                            @endforeach
                        </select>                        
                    </div>
                </div> 
                <div class="form-group">
                    {{Form::label('Jumlah1','PAGU ANGGARAN APBD',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Jumlah1',$data['Jumlah1'],['class'=>'form-control','placeholder'=>'NILAI PAGU ANGGARAN APBD'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Jumlah2','PAGU ANGGARAN APBDP',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Jumlah2',$data['Jumlah2'],['class'=>'form-control','placeholder'=>'NILAI PAGU ANGGARAN APBD'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Descr',$data['Descr'],['class'=>'form-control','placeholder'=>'KETERANGAN'])}}
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
    AutoNumeric.multiple(['#Jumlah1','#Jumlah2'],[{
                                            allowDecimalPadding: false,
                                            decimalCharacter: ",",
                                            digitGroupSeparator: ".",
                                            unformatOnSubmit: true,
                                            showWarnings:false,
                                            modifyValueOnWheel:false
                        }]);
    //styling select
    $('.select').select2({
        placeholder: "PILIH ANGGOTA DEWAN",
        allowClear:true
    });
    $('#frmdata').validate({
        rules: {
            PemilikPokokID : {
                required : true,
            },
            Jumlah1 : {
                required: true 
            },
            Jumlah2 : {
                required: true 
            }
        },
        messages : {
            PemilikPokokID : {
                required: "Mohon dipilih ANGGOTA DEWAN !"
            },
            Jumlah1 : {
                required: "Mohon untuk di isi karena ini diperlukan."                
            },
            Jumlah2 : {
                required: "Mohon untuk di isi karena ini diperlukan."                
            }
        }      
    });   
});
</script>
@endsection