@extends('layouts.limitless.l_main')
@section('page_title')
    PEMBAHASAN MUSRENBANG KABUPATEN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        PEMBAHASAN MUSRENBANG KABUPATEN TAHUN PERENCANAAN {{config('globalsettings.tahun_perencanaan')}}  
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.musrenbang.pembahasanmusrenkab.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">PEMBAHASAN</a></li>
    <li class="active">PEMBAHASAN MUSRENBANG KABUPATEN</li>
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
                        <label class="col-md-2 control-label">OPD / SKPD :</label> 
                        <div class="col-md-10">
                            <select name="OrgID" id="OrgID" class="select">
                                <option></option>
                                @foreach ($daftar_opd as $k=>$item)
                                    <option value="{{$k}}"{{$k==$filters['OrgID']?' selected':''}}>{{$item}}</option>
                                @endforeach
                            </select>                              
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">UNIT KERJA :</label> 
                        <div class="col-md-10">
                            <select name="SOrgID" id="SOrgID" class="select">
                                <option></option>
                                @foreach ($daftar_unitkerja as $k=>$item)
                                    <option value="{{$k}}"{{$k==$filters['SOrgID']?' selected':''}}>{{$item}}</option>
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
            {!! Form::open(['action'=>'Musrenbang\PembahasanMusrenKabController@search','method'=>'post','class'=>'form-horizontal','id'=>'frmsearch','name'=>'frmsearch'])!!}                                
                <div class="form-group">
                        <label class="col-md-2 control-label">Kriteria :</label> 
                        <div class="col-md-10">
                            {{Form::select('cmbKriteria', ['kode_kegiatan'=>'KODE KEGIATAN','KgtNm'=>'NAMA KEGIATAN','Uraian'=>'NAMA URAIAN'], isset($search['kriteria'])?$search['kriteria']:'kode_kegiatan',['class'=>'form-control'])}}
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
        @include('pages.limitless.musrenbang.pembahasanmusrenkab.datatable')
    </div>    
    <div class="col-md-12">
        <table id="datastatus" class="table table-responsive"> 
            <thead class="bg-info-300">
                <tr>
                    <th colspan="4" class="text-center">TOTAL PAGU INDIKATIF OPD / SKPD</th>
                    <th colspan="4" class="text-center">TOTAL PAGU INDIKATIF UNIT KERJA</th>
                </tr>
            </thead>
            <tbody class="bg-grey-300" style="font-weight:bold">   
                <tr>
                    <td class="text-right">STATUS DRAFT [0]</td>
                    <td id="totalstatusopd0" class="text-right">{{Helper::formatUang($totalpaguindikatifopd[0])}}</td>                     
                    <td colspan="2">&nbsp;</td>
                    <td class="text-right">STATUS DRAFT [0]</td>
                    <td id="totalstatusunitkerja0" class="text-right">{{Helper::formatUang($totalpaguindikatifunitkerja[0])}}</td>                     
                    <td colspan="2">&nbsp;</td>
                </tr>               
                <tr>
                    <td class="text-right">STATUS SETUJU [1]</td>
                    <td id="totalstatusopd1" class="text-right">{{Helper::formatUang($totalpaguindikatifopd[1])}}</td> 
                    <td colspan="2">&nbsp;</td>
                    <td class="text-right">STATUS SETUJU [1]</td>
                    <td id="totalstatusunitkerja1" class="text-right">{{Helper::formatUang($totalpaguindikatifunitkerja[1])}}</td> 
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-right">STATUS SETUJU DENGAN CATATAN [2]</td>
                    <td id="totalstatusopd2" class="text-right">
                        {{Helper::formatUang($totalpaguindikatifopd[2])}}                        
                    </td>
                    <td width="100">[1+2] = </td> 
                    <td id="totalstatusopd12">
                        {{Helper::formatUang($totalpaguindikatifopd[1]+$totalpaguindikatifopd[2])}}
                    </td>
                    <td class="text-right">STATUS SETUJU DENGAN CATATAN [2]</td>
                    <td id="totalstatusunitkerja2" class="text-right">
                        {{Helper::formatUang($totalpaguindikatifunitkerja[2])}}                        
                    </td>
                    <td width="100">[1+2] = </td> 
                    <td id="totalstatusunitkerja12">
                        {{Helper::formatUang($totalpaguindikatifunitkerja[1]+$totalpaguindikatifunitkerja[2])}}
                    </td>
                </tr>
                <tr>
                    <td class="text-right">STATUS PENDING [3]</td>
                    <td id="totalstatusopd3" class="text-right">{{Helper::formatUang($totalpaguindikatifopd[3])}}</td> 
                    <td colspan="2">&nbsp;</td>
                    <td class="text-right">STATUS PENDING [3]</td>
                    <td id="totalstatusunitkerja3" class="text-right">{{Helper::formatUang($totalpaguindikatifunitkerja[3])}}</td> 
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-right">TOTAL KESELURUHAN (0+1+2+3)</td>
                    <td id="totalstatusopd" class="text-right">{{Helper::formatUang($totalpaguindikatifopd['total'])}}</td> 
                    <td colspan="2">&nbsp;</td>
                    <td class="text-right">TOTAL KESELURUHAN (0+1+2+3)</td>
                    <td id="totalstatusunitkerja" class="text-right">{{Helper::formatUang($totalpaguindikatifunitkerja['total'])}}</td> 
                    <td colspan="2">&nbsp;</td>
                </tr>
            </tbody>            
        </table>               
    </div>    
</div>
@endsection
@section('page_custom_html')
    @include('layouts.limitless.l_modal_histori_renja_only_pagu')
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {      
    //styling select
    $('#OrgID.select').select2({
        placeholder: "PILIH OPD / SKPD",
        allowClear:true
    }); 
    $('#SOrgID.select').select2({
        placeholder: "PILIH UNIT KERJA",
        allowClear:true
    });  
    $(document).on('change','#OrgID',function(ev) {
        ev.preventDefault();   
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {                
                "_token": token,
                "OrgID": $('#OrgID').val(),
            },
            success:function(result)
            { 
                var daftar_unitkerja = result.daftar_unitkerja;
                var listitems='<option></option>';
                $.each(daftar_unitkerja,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });                
                $('#SOrgID').html(listitems);
                $('#divdatatable').html(result.datatable);
                formatPaguTotalIndikatifOPD(result.totalpaguindikatifopd);
                formatPaguTotalIndikatifUnitKerja(result.totalpaguindikatifunitkerja);
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });     
    });
    $(document).on('change','#SOrgID',function(ev) {
        ev.preventDefault();   
        $.ajax({
            type:'post',
            url: url_current_page +'/filter',
            dataType: 'json',
            data: {                
                "_token": token,
                "SOrgID": $('#SOrgID').val(),
            },
            success:function(result)
            { 
                $('#divdatatable').html(result.datatable);
                formatPaguTotalIndikatifUnitKerja(result.totalpaguindikatifunitkerja);
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });     
    });
    $("#divdatatable").on('click','.ubahstatus',function(ev) {
        ev.preventDefault();
        var RenjaRincID = $(this).attr("data-id");
        var Status = $(this).attr("data-status");
        $.ajax({
            type:'post',
            url: url_current_page +'/'+RenjaRincID,
            dataType: 'json',
            data: {                
                "_token": token,
                "_method": 'PUT',
                "Status":Status
            },
            success:function(result)
            { 
                $('#divdatatable').html(result.datatable); 
                formatPaguTotalIndikatifOPD(result.totalpaguindikatifopd);
                formatPaguTotalIndikatifUnitKerja(result.totalpaguindikatifunitkerja);
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    });
    $("#divdatatable").on('click','#btnTransfer',function(ev){
        ev.preventDefault();   
        let RenjaRincID = $(this).attr("data-id");        
        $.ajax({
            type:'post',
            url: url_current_page +'/transfer',
            dataType: 'json',
            data: {                
                "_token": token,
                "RenjaRincID": RenjaRincID,
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
    $("#divdatatable").on("click",".btnHistoriRenja", function(ev){   
        ev.preventDefault();   
        let _url = $(this).attr("data-url");
        
        $.ajax({
            type:'get',
            url: _url,
            dataType: 'json',            
            success:function(result)
            { 
                $('#modalrenjahistorionlypagu #modalnamauraian').html(result.data.Uraian);
                $('#modalrenjahistorionlypagu #modalprarenja').html(result.data.Jumlah1);
                $('#modalrenjahistorionlypagu #modalrakorbidang').html(result.data.Jumlah2);
                $('#modalrenjahistorionlypagu #modalforumopd').html(result.data.Jumlah3);
                $('#modalrenjahistorionlypagu #modalmusrenkab').html(result.data.Jumlah4);               
                $('#modalrenjahistorionlypagu #modalverifikasitapd').html(result.data.Jumlah5);                
                $('#modalrenjahistorionlypagu').modal('show');
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