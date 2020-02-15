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
    @include('pages.limitless.report.reportusulanrenja.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">WORKFLOW</a></li>
    <li class="active">{{$page_title}}</li>
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12" id="divdatatable">
        @include('pages.limitless.report.reportusulanrenja.datatable')
    </div>    
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="datastatus" class="table"> 
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
                        <td class="text-right">STATUS SETUJU <br>DENGAN CATATAN [2]</td>
                        <td id="totalstatusopd2" class="text-right">
                            {{Helper::formatUang($totalpaguindikatifopd[2])}}                        
                        </td>
                        <td width="100">TOTAL PAGU INDIKATIF [1+2]</td> 
                        <td id="totalstatusopd12">
                            {{Helper::formatUang($totalpaguindikatifopd[1]+$totalpaguindikatifopd[2])}}
                        </td>
                        <td class="text-right">STATUS SETUJU <br>DENGAN CATATAN [2]</td>
                        <td id="totalstatusunitkerja2" class="text-right">
                            {{Helper::formatUang($totalpaguindikatifunitkerja[2])}}                        
                        </td>
                        <td width="100">TOTAL PAGU INDIKATIF [1+2]</td> 
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
</div>
@endsection
@section('page_custom_html')
    @include('layouts.limitless.l_modal_histori_renja_only_pagu')
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/switch.min.js')!!}"></script>
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
                $(".switch").bootstrapSwitch();  
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
                $(".switch").bootstrapSwitch();  
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
                $(".switch").bootstrapSwitch();   
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