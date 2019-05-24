@extends('layouts.limitless.l_main')
@section('page_title')
    PEMBAHASAN MUSRENBANG DESA
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        PEMBAHASAN MUSRENBANG DESA TAHUN PERENCANAAN {{config('eplanning.tahun_perencanaan')}}  
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.musrenbang.pembahasanmusrendesa.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">PEMBAHASAN</a></li>
    <li class="active">MUSRENBANG DESA</li>
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
                {!! Form::open(['action'=>'Musrenbang\PembahasanMusrenDesaController@search','method'=>'post','class'=>'form-horizontal','id'=>'frmsearch','name'=>'frmsearch'])!!}                                
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kriteria :</label> 
                        <div class="col-md-10">
                            {{Form::select('cmbKriteria', ['No_usulan'=>'KODE','NamaKegiatan'=>'NAMA KEGIATAN'], isset($search['kriteria'])?$search['kriteria']:'KODE',['class'=>'form-control'])}}
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
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.musrenbang.pembahasanmusrendesa.datatable')
    </div>
</div>
@endsection
@section('page_asset_js')
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
    $(document).on('change','#PmKecamatanID',function(ev) {
        ev.preventDefault();
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {                
                "_token": token,
                "PmKecamatanID": $('#PmKecamatanID').val(),
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
                "PmDesaID": $('#PmDesaID').val(),
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
    $(document).on('switchChange.bootstrapSwitch', '.switch',function (ev) {
        ev.preventDefault();
        var UsulanDesaID = $(this).val();        
        var checked = $(this).prop('checked') == true ? 1 :0;
        $.ajax({
            type:'post',
            url: url_current_page +'/'+UsulanDesaID,
            dataType: 'json',
            data: {                
                "_token": token,
                "_method": 'PUT',
                "Privilege":checked
            },
            success:function(result)
            { 
                $('#divdatatable').html(result.datatable);                
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });
});
</script>
@endsection