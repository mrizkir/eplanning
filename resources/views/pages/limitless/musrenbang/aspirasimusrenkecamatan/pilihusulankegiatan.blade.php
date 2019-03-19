@extends('layouts.limitless.l_main')
@section('page_title')
    USULAN KECAMATAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        USULAN KECAMATAN TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.musrenbang.aspirasimusrenkecamatan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">ASPIRASI / USULAN</a></li>
    <li><a href="{!!route('aspirasimusrenkecamatan.index')!!}">USULAN KECAMATAN</a></li>
    <li class="active">PILIHH USULAN KEGIATAN</li>
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12" id="divfilter">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"><i class="icon-bookmark2 position-left"></i> Filter Data</h5>
                <div class="heading-elements">                       
                    <ul class="icons-list">
                        <li><a data-action="collapse"></a></li> 
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-2 control-label">KECAMATAN :</label> 
                        <div class="col-md-10">
                            <select name="PmKecamatanID" id="PmKecamatanID" class="select">
                                <option></option>
                                @foreach ($daftar_kecamatan as $k=>$item)
                                    <option value="{{$k}}"{{$k==$filters['PmKecamatanID']?' selected':''}}>{{$item}}</option>
                                @endforeach
                            </select>                              
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">DESA :</label> 
                        <div class="col-md-10">
                            <select name="PmDesaID" id="PmDesaID" class="select">
                                <option></option>  
                                @foreach ($daftar_desa as $k=>$item)
                                    <option value="{{$k}}"{{$k==$filters['PmDesaID']?' selected':''}}>{{$item}}</option>
                                @endforeach                          
                            </select>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title">
                    <i class="icon-search4 position-left"></i>
                    Pencarian Data
                </h5>
            </div>
            <div class="panel-body">
                {!! Form::open(['action'=>'Musrenbang\AspirasiMusrenKecamatanController@search','method'=>'post','class'=>'form-horizontal','id'=>'frmsearch','name'=>'frmsearch'])!!}                                
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kriteria :</label> 
                        <div class="col-md-10">
                            {{Form::select('cmbKriteria', ['No_usulan'=>'KODE','NamaKegiatan'=>'NAMA KEGIATAN'], isset($search['kriteria'])?$search['kriteria']:'replaceit',['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="form-group" id="divKriteria">
                        <label class="col-md-2 control-label">Isi Kriteria :</label>                                                    
                        <div class="col-md-10">                            
                            {{Form::text('txtKriteria',isset($search['isikriteria'])?$search['isikriteria']:'',['class'=>'form-control','placeholder'=>'Isi Kriteria Pencarian','id'=>'txtKriteria'])}}                                                                  
                        </div>
                    </div>                                                     
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            {{ Form::button('<b><i class="icon-search4"></i></b> Cari', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs', 'id'=>'btnSearch'] )  }}                            
                            <a id="btnReset" href="javascript:;" title="Reset Pencarian" class="btn btn-default btn-labeled btn-xs">
                                <b><i class="icon-reset"></i></b> Reset
                            </a>                           
                        </div>
                    </div>  
                {!! Form::close()!!}
            </div>
        </div>
    </div> 
    {!! Form::open(['action'=>'Musrenbang\AspirasiMusrenKecamatanController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                
    <div class="col-md-12">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title">
                    <i class="icon-pencil7 position-left"></i>
                    TENTUKAN OPD / SKPD PELAKSANA
                </h5>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        {{Form::label('UrsID','URUSAN',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            <select name="UrsID" id="UrsID" class="select">
                                <option></option>
                                @foreach ($daftar_urusan as $k=>$item)
                                    <option value="{{$k}}">{{$item}}</option>
                                @endforeach
                            </select>                        
                        </div>
                    </div>  
                    <div class="form-group">
                        {{Form::label('OrgID','OPD / SKPD PELAKSANA',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            <select name="OrgID" id="OrgID" class="select">
                                <option></option>                                
                            </select>                        
                        </div>
                    </div>
                    <div class="form-group">            
                        <div class="col-md-10 col-md-offset-2">                        
                            {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.musrenbang.aspirasimusrenkecamatan.datatablepilihusulankegiatan')
    </div>
    {!! Form::close()!!}
</div>
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/switch.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () { 
    $(".switch").bootstrapSwitch();
    //styling select
    $('#PmKecamatanID.select').select2({
        placeholder: "PILIH KECAMATAN",
        allowClear:true
    });   
    $('#PmDesaID.select').select2({
        placeholder: "PILIH DESA / KELURAHAN",
        allowClear:true
    });
    $('#UrsID.select').select2({
        placeholder: "PILIH URUSAN",
        allowClear:true
    });
    $('#OrgID.select').select2({
        placeholder: "PILIH OPD / SKPD",
        allowClear:true
    });
    $(document).on('change','#PmKecamatanID',function(ev) {
        ev.preventDefault();
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {                
                "_token": token,
                "PmKecamatanIDPilihUsulan": $('#PmKecamatanID').val(),
            },
            success:function(result)
            { 
                var daftar_desa = result.daftar_desa;
                var listitems='<option></option>';
                $.each(daftar_desa,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                $('#PmDesaID').html(listitems);
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });
    $(document).on('change','#PmDesaID',function(ev) {
        ev.preventDefault();   
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {                
                "_token": token,
                "PmDesaIDPilihUsulan": $('#PmDesaID').val(),
            },
            success:function(result)
            { 
                $('#divdatatable').html(result.datatable);
                $(".switch").bootstrapSwitch();
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });     
    }); 
    $(document).on('change','#UrsID',function(ev) {
        ev.preventDefault();   
        $.ajax({
            type:'post',
            url: url_current_page +'/filterurusan',
            dataType: 'json',
            data: {                
                "_token": token,
                "UrsID": $('#UrsID').val(),
            },
            success:function(result)
            { 
                console.log(result);
                var daftar_organisasi = result.daftar_organisasi;
                var listitems='<option></option>';
                $.each(daftar_organisasi,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                $('#OrgID').html(listitems);
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });     
    }); 
    $('#frmdata').validate({
        ignore: [], 
        rules: {
            OrgID : {
                required: true
            },
            'UsulanDesaID[]' : {
                required: true
            }
        },
        messages : {
            OrgID : {
                required: "Mohon untuk di pilih OPD/SKPD pelaksana karena ini diperlukan.",                
            },
            'UsulanDesaID[]' : {
                required: "",                
            }
        }      
    });   
});
</script>
@endsection