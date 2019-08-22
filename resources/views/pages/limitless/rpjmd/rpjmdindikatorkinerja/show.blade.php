@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD INDIKASI RENCANA PROGRAM
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RPJMD INDIKASI RENCANA PROGRAM PERIODE {{HelperKegiatan::getRPJMDTahunMulai()}} - {{HelperKegiatan::getRPJMDTahunAkhir()+1}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdindikatorkinerja.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdindikatorkinerja.index')!!}">INDIKASI RENCANA PROGRAM</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA INDIKASI RENCANA PROGRAM
                </h5>
                <div class="heading-elements">   
                    <a href="{!!route('rpjmdindikatorkinerja.create')!!}" class="btn btn-info btn-icon heading-btn btnAdd" title="Tambah Indikasi">
                        <i class="icon-googleplus5"></i>
                    </a>
                    <a href="{{route('rpjmdindikatorkinerja.edit',['id'=>$data->IndikatorKinerjaID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data RpjmdIndikatorKinerja">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data RpjmdIndikatorKinerja" data-id="{{$data->IndikatorKinerjaID}}" data-url="{{route('rpjmdindikatorkinerja.index')}}" class="btn btn-danger btn-icon heading-btn btnDelete">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('rpjmdindikatorkinerja.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>INDIKATORKINERJAID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->IndikatorKinerjaID}}</p>
                                </div>                            
                            </div>                  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KEBIJAKAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Kebijakan}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KELOMPOK URUSAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Urusan}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>URUSAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Bidang}}</p>
                                </div>                            
                            </div> 
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">  
                             <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PROGRAM: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->PrgNm}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PERANGKAT DAERAH PENANGGUNG JAWAB: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->OrgNm}}</p>
                                </div>                            
                            </div>                         
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. UBAH: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$data->updated_at)}}</p>
                                </div>                            
                            </div>              
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. BUAT: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$data->created_at)}}</p>
                                </div>                            
                            </div>          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
        <div class="panel-heading">
            <div class="panel-title">
                <h5>INDIKATOR KINERJA PROGRAM (OUTCOME)</h5>
            </div>            
        </div>      
        <div class="table-responsive"> 
            <table id="data" class="table table-striped table-hover" style="font-size:11px">
                <caption>
                    <strong>NAMA INDIKATOR</strong> : {{$data->NamaIndikator}}
                </caption>
                <thead>
                    <tr class="bg-teal-700">
                        <th rowspan="2">KONDISI KINERJA <br>AWAL RPJMD ({{$data->TA-1}})</th>  
                        <th colspan="2">{{$data->TA}}</th>
                        <th colspan="2">{{$data->TA+1}}</th>
                        <th colspan="2">{{$data->TA+2}}</th>
                        <th colspan="2">{{$data->TA+3}}</th>
                        <th colspan="2">{{$data->TA+4}}</th>
                        <th colspan="2">KONDISI KINERJA <br>AKHIR RPJDM ({{$data->TA+5}})</th>                       
                    </tr>
                    <tr class="bg-teal-700">
                        <th>Target</th>
                        <th>Rp</th>
                        <th>Target</th>
                        <th>Rp</th>
                        <th>Target</th>
                        <th>Rp</th>
                        <th>Target</th>
                        <th>Rp</th>
                        <th>Target</th>
                        <th>Rp</th>
                        <th>Target</th>
                        <th>Rp</th>                        
                    </tr>
                </thead>
                <tbody>    
                    <tr>
                        <td>{{$data->KondisiAwal}}</td>
                        <td>{{Helper::formatAngka($data->TargetN1)}}</td>
                        <td>{{Helper::formatUang($data->PaguDanaN1)}}</td>
                        <td>{{Helper::formatAngka($data->TargetN2)}}</td>
                        <td>{{Helper::formatUang($data->PaguDanaN2)}}</td>
                        <td>{{Helper::formatAngka($data->TargetN3)}}</td>
                        <td>{{Helper::formatUang($data->PaguDanaN3)}}</td>
                        <td>{{Helper::formatAngka($data->TargetN4)}}</td>
                        <td>{{Helper::formatUang($data->PaguDanaN4)}}</td>
                        <td>{{Helper::formatAngka($data->TargetN5)}}</td>
                        <td>{{Helper::formatUang($data->PaguDanaN5)}}</td>
                        <td>{{Helper::formatAngka($data->KondisiAkhirTarget)}}</td>
                        <td>{{Helper::formatUang($data->KondisiAkhirPaguDana)}}</td>                        
                    </tr>
                </tbody>
            </table>       
        </div>  
    </div> 
</div>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $(".btnDelete").click(function(ev) {
        if (confirm('Apakah Anda ingin menghapus Data Indikasi Rencana Program ini ?')) {
            let url_ = $(this).attr("data-url");
            let id = $(this).attr("data-id");
            let token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({            
                type:'post',
                url:url_+'/'+id,
                dataType: 'json',
                data: {
                    "_method": 'DELETE',
                    "_token": token,
                    "id": id,
                },
                success:function(data){ 
                    window.location.replace(url_);                        
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