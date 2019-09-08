@extends('layouts.limitless.l_main')
@section('page_title')
    {{$page_title}}
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold">
        {{$page_title}} TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}  
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.pembahasanrkpd.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">WORKFLOW</a></li>
    <li class="active">{{$page_title}}</li>
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
                {!! Form::open(['url'=>route(Helper::getNameOfPage('search')),'method'=>'post','class'=>'form-horizontal','id'=>'frmsearch','name'=>'frmsearch'])!!}                                
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kriteria :</label> 
                        <div class="col-md-10">
                            {{Form::select('cmbKriteria', ['RKPDID'=>'RKPD ID','kode_kegiatan'=>'KODE KEGIATAN','KgtNm'=>'NAMA KEGIATAN','Uraian'=>'NAMA URAIAN'], isset($search['kriteria'])?$search['kriteria']:'kode_kegiatan',['class'=>'form-control'])}}
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
        @include('pages.limitless.rkpd.pembahasanrkpd.datatable')
    </div>      
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="datapagu" class="table"> 
                <thead class="bg-info-300">
                    <tr>
                        <th colspan="2" class="text-center">TOTAL PAGU INDIKATIF OPD / SKPD</th>
                        <th width="150"><strong>PAGU DANA:</strong></th>
                        <th id="paguanggaranopd">{{Helper::formatUang($paguanggaranopd)}}</th>
                        <th colspan="4" class="text-center">TOTAL PAGU INDIKATIF UNIT KERJA</th>
                    </tr>
                </thead>
                <tbody class="bg-grey-300" style="font-weight:bold">   
                    <tr>
                        <td class="text-right">JUMLAH PAGU MURNI [1]</td>
                        <td id="totalpagumurniopd" class="text-right">{{Helper::formatUang($totalpaguopd['murni'])}}</td>                     
                        <td colspan="2">&nbsp;</td>
                        <td class="text-right">JUMLAH PAGU MURNI [1]</td>
                        <td id="totalpagumurniunitkerja" class="text-right">{{Helper::formatUang($totalpaguunitkerja['murni'])}}</td>                     
                        <td colspan="2">&nbsp;</td>
                    </tr>               
                    <tr>
                        <td class="text-right">JUMLAH PAGU PEMBAHASAN [2]</td>
                        <td id="totalpagupembahasanopd" class="text-right">{{Helper::formatUang($totalpaguopd['pembahasanm'])}}</td> 
                        <td colspan="2">&nbsp;</td>
                        <td class="text-right">JUMLAH PAGU PEMBAHASAN [2]</td>
                        <td id="totalpagupembahasanunitkerja" class="text-right">{{Helper::formatUang($totalpaguunitkerja['pembahasanm'])}}</td> 
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="text-right">SELISIH [2-1]</td>
                        <td id="selisihopd" class="text-right">{{Helper::formatUang($totalpaguopd['selisihm'])}}</td> 
                        <td colspan="2">&nbsp;</td>
                        <td class="text-right">SELISIH [2-1]</td>
                        <td id="selisihunitkerja" class="text-right">{{Helper::formatUang($totalpaguunitkerja['selisihm'])}}</td> 
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </tbody>            
            </table>               
        </div>
    </div>    
</div>
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/switch.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {  
    $(".switch").bootstrapSwitch();
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
                $(".switch").bootstrapSwitch();

                $('#paguanggaranopd').html(result.paguanggaranopd);
                new AutoNumeric ('#paguanggaranopd',{
                    allowDecimalPadding: false,
                    emptyInputBehavior:'zero',
                    decimalCharacter: ",",
                    digitGroupSeparator: ".",
                    showWarnings:false
                }); 
                formatRKPDPaguPembahasanOPD(result.totalpaguopd);
                formatRKPDPaguPembahasanUnitKerja(result.totalpaguunitkerja);
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
                $(".switch").bootstrapSwitch();
                formatRKPDPaguPembahasanUnitKerja(result.totalpaguunitkerja);
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });     
    });
    $("#divdatatable").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data {{$page_title}} ini ?')) {
            let url_ = $(this).attr("data-url");
            let id = $(this).attr("data-id");
            let pid = $(this).attr("data-pid");
            $.ajax({            
                type:'post',
                url:url_+'/'+id,
                dataType: 'json',
                data: {
                    "_method": 'DELETE',
                    "_token": token,
                    "id": id,
                    "pid": pid
                },
                success:function(result){ 
                    if (result.success==1){
                        $('#divdatatable').html(result.datatable);       
                        $(".switch").bootstrapSwitch();                 
                    }else{
                        console.log("Gagal menghapus data {{$page_title}} dengan id "+id);
                    }                    
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }        
    });
});
</script>
@endsection