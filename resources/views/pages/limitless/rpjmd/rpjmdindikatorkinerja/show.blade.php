@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD INDIKASI RENCANA PROGRAM
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RPJMD INDIKASI RENCANA PROGRAM TAHUN {{config('eplanning.rpjmd_tahun_mulai')}} - {{config('eplanning.rpjmd_tahun_akhir')}}  
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
                                <label class="col-md-4 control-label"><strong>IndikatorKinerjaID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->IndikatorKinerjaID}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA INDIKATOR: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->NamaIndikator}}</p>
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
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PROGRAM: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->PrgNm}}</p>
                                </div>                            
                            </div>                           
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>OPD / SKPD 1: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->OrgNm}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>OPD / SKPD 2: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->OrgNm2}}</p>
                                </div>                            
                            </div>  
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TARGET AWAL: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->TargetAwal}}</p>
                                </div>                            
                            </div>    
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PAGU DANA / TARGET N1: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatUang($data->PaguDanaN1)}} / {{$data->TargetN1}}</p>
                                </div>                            
                            </div>    
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PAGU DANA / TARGET N1: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatUang($data->PaguDanaN1)}} / {{$data->TargetN1}}</p>
                                </div>                            
                            </div>    
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PAGU DANA / TARGET N2: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatUang($data->PaguDanaN2)}} / {{$data->TargetN2}}</p>
                                </div>                            
                            </div>    
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PAGU DANA / TARGET N3: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatUang($data->PaguDanaN3)}} / {{$data->TargetN3}}</p>
                                </div>                            
                            </div>    
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PAGU DANA / TARGET N4: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatUang($data->PaguDanaN4)}} / {{$data->TargetN4}}</p>
                                </div>                            
                            </div>    
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PAGU DANA / TARGET N5: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatUang($data->PaguDanaN5)}} / {{$data->TargetN5}}</p>
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