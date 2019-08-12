@extends('layouts.limitless.l_main')
@section('page_title')
    RENSTRA PROGRAM, KEGIATAN, DAN PENDANAAN  {{HelperKegiatan::getRENSTRATahunMulai()}} - {{HelperKegiatan::getRENSTRATahunAkhir()}}
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RENSTRA PROGRAM, KEGIATAN, DAN PENDANAAN TAHUN {{HelperKegiatan::getRENSTRATahunMulai()}} - {{HelperKegiatan::getRENSTRATahunAkhir()}}  
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.renstra.renstraprogramkegiatanpendanaan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RENSTRA</a></li>
    <li><a href="{!!route('renstraprogramkegiatanpendanaan.index')!!}">PROGRAM, KEGIATAN, DAN PENDANAAN</a></li>
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
                        <a href="{!!route('renstraprogramkegiatanpendanaan.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>['RENSTRA\RENSTRAProgramKegiatanPendanaanController@update',$data->RenstraIndikatorID],'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                                            
                <div class="form-group">
                    <label class="col-md-2 control-label">OPD / SKPD: </label>
                    <div class="col-md-10">
                        <p class="form-control-static">
                            <span class="label border-left-primary label-striped">{{$data->OrgNm}}</span>
                        </p>
                    </div>                            
                </div>
                <div class="form-group">
                    {{Form::label('UrsID','NAMA URUSAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <select name="UrsID" id="UrsID" class="select">
                            <option></option>
                            @foreach ($daftar_urusan as $k=>$item)
                                <option value="{{$k}}" {{$data->UrsID == $k?' selected':''}}>{{$item}}</option>
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
                                <option value="{{$k}}" {{$data->PrgID == $k?' selected':''}}>{{$item}}</option>
                            @endforeach
                        </select>    
                    </div>
                </div> 
                <div class="form-group">
                    {{Form::label('IndikatorKinerjaID','INDIKATOR KINERJA RPJMD',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <select name="IndikatorKinerjaID" id="IndikatorKinerjaID" class="select">
                            <option></option> 
                            @foreach ($daftar_indikator as $k=>$item)
                                <option value="{{$k}}" {{$data->IndikatorKinerjaID == $k?' selected':''}}>{{$item}}</option>
                            @endforeach                           
                        </select>    
                    </div>
                </div>       
                <div class="form-group">
                    <label class="col-md-2 control-label">ARAH KEBIJAKAN:</label> 
                    <div class="col-md-10">
                        <select name="RenstraKebijakanID" id="RenstraKebijakanID" class="select">
                            <option></option>
                            @foreach ($daftar_kebijakan as $k=>$item)
                                <option value="{{$k}}" {{$data->RenstraKebijakanID == $k?' selected':''}}>{{$item}}</option>
                            @endforeach
                        </select>                                
                    </div>
                </div>   
                <div class="form-group">
                    {{Form::label('NamaIndikator','NAMA INDIKATOR',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('NamaIndikator',$data->NamaIndikator,['class'=>'form-control','placeholder'=>'Nama Indikator'])}}
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
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    //styling select
    $('#UrsID.select').select2({
        placeholder: "PILIH NAMA URUSAN",
        allowClear:true
    });
    $('#PrgID.select').select2({
        placeholder: "PILIH NAMA PROGRAM",
        allowClear:true
    });    
    $('#RenstraKebijakanID.select').select2({
        placeholder: "PILIH ARAH KEBIJAKAN",
        allowClear:true
    });
    $('#IndikatorKinerjaID.select').select2({
        placeholder: "PILIH INDIKATOR KINERJA RPJMD",
        allowClear:true
    });
    $(document).on('change','#UrsID',function(ev) {
        ev.preventDefault();
        UrsID=$(this).val();        
        $.ajax({
            type:'post',
            url: url_current_page +'/pilihindikatorsasaran',
            dataType: 'json',
            data: {
                "_token": token,
                "UrsID": UrsID,
            },
            success:function(result)
            {   
                var daftar_program = result.daftar_program;
                var listitems='<option></option>';
                $('#IndikatorKinerjaID').html(listitems);
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
            url: url_current_page +'/pilihindikatorsasaran',
            dataType: 'json',
            data: {
                "_token": token,
                "PrgID": PrgID,
            },
            success:function(result)
            {   
                var daftar_indikator = result.daftar_indikator;
                var listitems='<option></option>';
                $.each(daftar_indikator,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                $('#IndikatorKinerjaID').html(listitems);
            },
            error:function(xhr, status, error)
            {   
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });
    $('#frmdata').validate({
        ignore: [],
        rules: {
            RenstraKebijakanID : {
                required: true,
                valueNotEquals: 'none'
            },
            PrgID : {
                required: true,
                valueNotEquals: 'none'
            },
            IndikatorKinerjaID : {
                required: true,
                valueNotEquals: 'none'
            },
            NamaIndikator : {
                required: true,
                minlength: 2
            }
        },
        messages : {
            RenstraKebijakanID : {
                required: "Mohon untuk di pilih karena ini diperlukan.",
                valueNotEquals: "Mohon untuk di pilih karena ini diperlukan.",      
            },
            PrgID : {
                required: "Mohon untuk di pilih karena ini diperlukan.",
                valueNotEquals: "Mohon untuk di pilih karena ini diperlukan.",      
            },
            IndikatorKinerjaID : {
                required: "Mohon untuk di pilih karena ini diperlukan.",
                valueNotEquals: "Mohon untuk di pilih karena ini diperlukan.",      
            },
            NamaIndikator : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 2 karakter atau lebih."
            }
        }      
    });   
});
</script>
@endsection