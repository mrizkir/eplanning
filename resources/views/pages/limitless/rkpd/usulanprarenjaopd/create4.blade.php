@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN PRA RENJA OPD/SKPD
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        USULAN PRA RENJA OPD/SKPD TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.usulanprarenjaopd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">ASPIRASI / USULAN</a></li>
    <li><a href="{!!route('usulanprarenjaopd.index')!!}">USULAN PRA RENJA OPD/SKPD</a></li>
    <li class="active">TAMBAH DATA RINCIAN KEGIATAN DARI OPD / SKPD</li>
@endsection
@section('page_sidebar')
    @include('layouts.limitless.l_sidebar_prarenja')
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                TAMBAH DATA RINCIAN KEGIATAN DARI OPD / SKPD
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route('usulanprarenjaopd.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['action'=>'RKPD\UsulanPraRenjaOPDController@store4','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                              
        <div class="panel-body">            
            <div class="form-group">
                {{Form::label('No','NOMOR',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('No','',['class'=>'form-control','placeholder'=>'NOMOR URUT KEGIATAN'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Uraian','NAMA/URAIAN KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Uraian','',['class'=>'form-control','placeholder'=>'NAMA ATAU URAIAN KEGIATAN'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Sasaran_Angka1','SASARAN KEGIATAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            {{Form::text('Sasaran_Angka1','',['class'=>'form-control','placeholder'=>'ANGKA SASARAN'])}}
                        </div>
                        <div class="col-md-6">
                            {{Form::text('Sasaran_Uraian1','',['class'=>'form-control','placeholder'=>'URAIAN SASARAN'])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Target1','TARGET',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Target1','',['class'=>'form-control','placeholder'=>'TARGET'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Jumlah1','NILAI USULAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Jumlah1','',['class'=>'form-control','placeholder'=>'NILAI USULAN'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Prioritas','PRIORITAS',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::select('Prioritas', HelperKegiatan::getDaftarPrioritas(),'none',['class'=>'form-control','id'=>'Prioritas'])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::text('Sasaran_Angka1','',['class'=>'form-control','placeholder'=>'ANGKA SASARAN'])}}
                </div>
            </div>            
        </div>
        <div class="panel-body">
            <div class="form-group">
                {{Form::label('PMProvID','PROVINSI',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::select('PMProvID', $daftar_provinsi,config('globalsettings.default_provinsi'),['class'=>'form-control','id'=>'PMProvID','disabled'=>true])}}
                </div>
            </div>       
            <div class="form-group">
                {{Form::label('PmKotaID','KAB. / KOTA',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::select('PmKotaID', $daftar_kota_kab,config('globalsettings.defaul_kota_atau_kab'),['class'=>'form-control','id'=>'PMProvID','disabled'=>true])}}
                </div>
            </div>  
            <div class="form-group">
                <label class="col-md-2 control-label">KECAMATAN :</label> 
                <div class="col-md-10">
                    <select name="PmKecamatanID" id="PmKecamatanID" class="select">
                        <option></option>          
                        @foreach ($daftar_kecamatan as $k=>$item)
                            <option value="{{$k}}">{{$item}}</option>
                        @endforeach              
                    </select>                              
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">DESA :</label> 
                <div class="col-md-10">
                    <select name="PmDesaID" id="PmDesaID" class="select">
                        <option></option>                                                
                    </select>                            
                </div>
            </div>       
        </div>
        <div class="panel-footer">
            <div class="col-md-10 col-md-offset-2">                        
                {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}                                       
            </div>
        </div>
        {!! Form::close()!!}
    </div>
    <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
        <div class="panel-heading">
            <div class="panel-title">
                <h5>DAFTAR RINCIAN KEGIATAN</h5>
            </div>
            <div class="heading-elements">
                
            </div>
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
    $('#PMProvID.select').select2({
        placeholder: "PILIH PROVINSI",
        allowClear:true
    }); 
    $('#PmKotaID.select').select2({
        placeholder: "PILIH KABUPATEN / KOTA",
        allowClear:true
    }); 
    $('#PmKecamatanID.select').select2({
        placeholder: "PILIH KECAMATAN",
        allowClear:true
    }); 
    $('#PmDesaID.select').select2({
        placeholder: "PILIH DESA",
        allowClear:true
    }); 
});
</script>
@endsection