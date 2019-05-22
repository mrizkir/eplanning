@extends('layouts.limitless.l_main')
@section('page_title')
    {{$page_title}}
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        {{$page_title}} TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.usulanrenja.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">WORKFLOW</a></li>
    <li><a href="{!!route(Helper::getNameOfPage('index'))!!}">{{$page_title}}</a></li>
    <li class="active">TAMBAH DATA KEGIATAN</li>
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                TAMBAH DATA KEGIATAN
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route(Helper::getNameOfPage('index'))!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['url'=>route(Helper::getNameOfPage('store')),'method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
        <div class="panel-body">            
            <div class="form-group">
                <label class="col-md-2 control-label">POSISI ENTRI: </label>
                <div class="col-md-10">
                    <p class="form-control-static">
                        <span class="label border-left-primary label-striped">{{$page_title}}</span>
                    </p>
                </div>                            
            </div>
            <div class="form-group">
                {{Form::label('UrsID','NAMA URUSAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="UrsID" id="UrsID" class="select">
                        <option></option>
                        @foreach ($daftar_urusan as $k=>$item)
                            <option value="{{$k}}" {{$UrsID_selected == $k?' selected':''}}>{{$item}}</option>
                        @endforeach
                    </select>                        
                </div>
            </div>         
            <div class="form-group">
                {{Form::label('PrgID','NAMA PROGRAM',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="PrgID" id="PrgID" class="select">
                        <option></option>
                        @foreach ($daftar_program as $k=>$item)
                            <option value="{{$k}}"}}>{{$item}}</option>
                        @endforeach
                    </select>    
                </div>
            </div>          
            <div class="form-group">
                {{Form::label('KgtID','NAMA KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::select('KgtID', [], '',['class'=>'select','id'=>'KgtID'])}}                    
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                {{Form::label('Sasaran_Angka','SASARAN KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            {{Form::text('Sasaran_Angka','',['class'=>'form-control','placeholder'=>'ANGKA SASARAN'])}}
                        </div>
                        <div class="col-md-6">
                            {{Form::textarea('Sasaran_Uraian','',['rows'=>3,'class'=>'form-control','placeholder'=>'URAIAN SASARAN'])}}
                        </div>
                    </div>                        
                </div>                   
            </div>            
            <div class="form-group">
                {{Form::label('Sasaran_AngkaSetelah','SASARAN KEGIATAN (N+1)',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            {{Form::text('Sasaran_AngkaSetelah','',['class'=>'form-control','placeholder'=>'ANGKA SASARAN (N+1)'])}}
                        </div>
                        <div class="col-md-6">
                            {{Form::textarea('Sasaran_UraianSetelah','',['rows'=>3,'class'=>'form-control','placeholder'=>'URAIAN SASARAN (N+1)'])}}
                        </div>
                    </div>                        
                </div>
            </div>                
            <div class="form-group">
                {{Form::label('Target','TARGET (%)',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Target','',['class'=>'form-control','placeholder'=>'PERSENTASE TARGET KEGIATAN'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('NilaiSebelum','NILAI',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-4">
                            {{Form::text('NilaiSebelum','',['class'=>'form-control','placeholder'=>'NILAI (TA-1)'])}}
                        </div>
                        <div class="col-md-4">
                            {{Form::text('NilaiUsulan',0,['class'=>'form-control','placeholder'=>'NILAI USULAN (TA)','id'=>'NilaiUsulan','readonly'=>true])}}
                        </div> 
                        <div class="col-md-4">
                            {{Form::text('NilaiSetelah','',['class'=>'form-control','placeholder'=>'NILAI (TA+1)','id'=>'NilaiSetelah'])}}
                        </div>       
                    </div>                                         
                </div>
            </div>
            <div class="form-group">
                {{Form::label('NamaIndikator','INDIKATOR KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::textarea('NamaIndikator','',['rows'=>3,'class'=>'form-control','placeholder'=>'INDIKATOR KEGIATAN'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('SumberDanaID','SUMBER DANA',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::select('SumberDanaID', $sumber_dana, '',['class'=>'form-control','id'=>'SumberDanaID'])}}
                </div>
            </div>                
            <div class="form-group">
                {{Form::label('Descr','KETERANGAN / CATATAN PENTING',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::textarea('Descr','',['rows'=>3,'class'=>'form-control','placeholder'=>'KETERANGAN'])}}
                </div>
            </div> 
            <div class="form-group">            
                <div class="col-md-10 col-md-offset-2">                        
                    {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}                        
                </div>
            </div>        
        </div>
        {!! Form::close()!!}
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
    AutoNumeric.multiple(['#Sasaran_Angka','#Sasaran_AngkaSetelah'], {
                                            allowDecimalPadding: false,
                                            minimumValue:0,
                                            maximumValue:99999999999,
                                            numericPos:true,
                                            decimalPlaces : 0,
                                            digitGroupSeparator : '',
                                            showWarnings:false,
                                            unformatOnSubmit: true,
                                            modifyValueOnWheel:false
                                        });
    AutoNumeric.multiple(['#Target'], {
                                            allowDecimalPadding: false,
                                            minimumValue:0.00,
                                            maximumValue:100.00,
                                            numericPos:true,
                                            decimalPlaces : 2,
                                            digitGroupSeparator : '',
                                            showWarnings:false,
                                            unformatOnSubmit: true,
                                            modifyValueOnWheel:false
                                        });

    AutoNumeric.multiple(['#NilaiSebelum','#NilaiUsulan','#NilaiSetelah'],{
                                            allowDecimalPadding: false,
                                            decimalCharacter: ",",
                                            digitGroupSeparator: ".",
                                            unformatOnSubmit: true,
                                            showWarnings:false,
                                            modifyValueOnWheel:false
                                        });
    //styling select
    $('#UrsID.select').select2({
        placeholder: "PILIH NAMA URUSAN",
        allowClear:true
    });
    $('#PrgID.select').select2({
        placeholder: "PILIH NAMA PROGRAM",
        allowClear:true
    });
    $('#KgtID.select').select2({
        placeholder: "PILIH NAMA KEGIATAN",
        allowClear:true
    });    
    $(document).on('change','#UrsID',function(ev) {
        ev.preventDefault();
        UrsID=$(this).val();        
        $.ajax({
            type:'post',
            url: '{{route(Helper::getNameOfPage("pilihusulankegiatan"))}}',
            dataType: 'json',
            data: {
                "_token": token,
                "UrsID": UrsID,
            },
            success:function(result)
            {   
                var daftar_program = result.daftar_program;
                var listitems='<option></option>';
                $.each(daftar_program,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                $('#PrgID').html(listitems);
            },
            error:function(xhr, status, error)
            {   
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });
    $(document).on('change','#PrgID',function(ev) {
        ev.preventDefault();
        PrgID=$(this).val();        
        $.ajax({
            type:'post',
            url: '{{route(Helper::getNameOfPage("pilihusulankegiatan"))}}',
            dataType: 'json',
            data: {
                "_token": token,
                "PrgID": PrgID,
            },
            success:function(result)
            {   
                var daftar_kegiatan = result.daftar_kegiatan;
                var listitems='<option></option>';
                $.each(daftar_kegiatan,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                $('#KgtID').html(listitems);
            },
            error:function(xhr, status, error)
            {   
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });

    $('#frmdata').validate({
        ignore:[],
        rules: {
            UrsID : {
                required: true
            },  
            PrgID : {
                required: true
            },
            KgtID : {
                required: true
            },         
            Sasaran_Angka : {
                required: true,
            },
            Sasaran_Uraian : {
                required: true
            },
            Sasaran_AngkaSetelah : {
                required: true
            },
            Sasaran_UraianSetelah : {
                required: true
            },
            Target : {
                required: true
            },
            NilaiSebelum : {
                required: true
            },
            NilaiUsulan : {
                required: true
            },
            NilaiSetelah : {
                required: true
            },
            NamaIndikator : {
                required: true,                
            },
            SumberDanaID : {
                valueNotEquals: 'none'
            }         
        },
        messages : {
            UrsID : {
                required: "Mohon untuk di pilih urusan untuk kegiatan ini.",                
            },            
            PrgID : {
                required: "Mohon untuk di pilih program nama kegiatan.",                
            },
            KgtID : {
                required: "Mohon untuk di pilih nama kegiatan.",                
            },
            Sasaran_Angka : {
                required: "Mohon untuk di isi angka sasaran kegiatan.",                
            },
            Sasaran_Uraian : {
                required: "Mohon untuk di isi uraian sasaran kegiatan.",                
            },
            Sasaran_AngkaSetelah : {
                required: "Mohon untuk di isi angka sasaran kegiatan (N+1).",                
            },
            Sasaran_UraianSetelah : {
                required: "Mohon untuk di isi uraian sasaran kegiatan (N+1).",                
            },
            Target : {
                required: "Mohon untuk di isi persentase target kegiatan.",                
            },
            NilaiSebelum : {
                required: "Mohon untuk di isi nilai (TA-1).",                
            },
            NilaiUsulan : {
                required: "Mohon untuk di isi nilai (TA).",                
            },
            NilaiSetelah : {
                required: "Mohon untuk di isi nilai (TA+1).",                
            },
            NamaIndikator : {
                required: "Mohon untuk di isi indikator kegiatan.",                
            },
            SumberDanaID : {
                valueNotEquals: "Mohon untuk di pilih sumber dana untuk kegiatan ini."                
            }

        }      
    });   
});
</script>
@endsection