@extends('layouts.limitless.l_main')
@section('page_title')
    RINGKASAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}  
@endsection
@section('page_header')
    <i class="icon-display position-left"></i> 
    <span class="text-semibold"> 
        RINGKASAN TAHUN PERENCANAAN {{HelperKegiatan::getTahunPerencanaan()}}  
    </span>
@endsection
@section('page_breadcrumb')
    <li><a href="#">DASHBOARD</a></li>
    <li class="active">RINGKASAN PERENCANAAN</li>
@endsection
@section('page_content')
<div class="row">
    <div class="col-md-12" id="divfilter">
        <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"><i class="icon-bookmark2 position-left"></i> FILTER DATA</h5>
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
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="panel panel-body bg-orange-700 has-bg-image">
            <div class="media no-margin">
                <div class="media-body">
                    <h3 class="no-margin">{{$data['jumlahkegiatan']}}</h3>
                    <span class="text-uppercase text-size-mini">JUMLAH KEGIATAN</span>
                </div>

                <div class="media-right media-middle">
                    <i class="icon-bubbles4 icon-3x opacity-75"></i>
                </div>
            </div>
        </div> 
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="panel panel-body bg-orange-700 has-bg-image">
            <div class="media no-margin">
                <div class="media-body">
                    <h3 class="no-margin">{{Helper::formatUang($data['jumlahpagu'])}}</h3>
                    <span class="text-uppercase text-size-mini">JUMLAH PAGU</span>
                </div>

                <div class="media-right media-middle">
                    <i class="icon-bubbles4 icon-3x opacity-75"></i>
                </div>
            </div>
        </div> 
    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h6 class="panel-title">USULAN KEGIATAN MUSREN. KECAMATAN DIKELOMPOKAN PER DESA</h6>
            </div>
            @if (count($rekap_desa) > 0)
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead>
                        <th width="70">NO</th>
                        <th width="400">NAMA DESA</th>
                        <th>JUMLAH KEGIATAN</th>
                        <th>JUMLAH PAGU INDIKATIF</th>
                    </thead>
                    <tbody>
                    @foreach ($rekap_desa as $key=>$item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$item->Nm_Desa}}</td>
                            <td>{{$item->jumlahkegiatan}}</td>
                            <td>{{Helper::formatUang($item->jumlahpagu)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="panel-body">
                <div class="alert alert-info alert-styled-left alert-bordered">
                    <span class="text-semibold">Info!</span>
                    Belum ada data rekapitulasi yang bisa ditampilkan.
                </div>
            </div>   
            @endif       
        </div>
    </div>
</div>
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    //styling select
    $('#PmKecamatanID.select').select2({
        placeholder: "PILIH KECAMATAN",
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
                window.location.replace(url_current_page);
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