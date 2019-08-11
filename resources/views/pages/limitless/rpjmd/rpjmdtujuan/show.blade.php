@extends('layouts.limitless.l_main')
@section('page_title')
    RPJMD TUJUAN
@endsection
@section('page_header')
    <i class="icon-price-tag position-left"></i>
    <span class="text-semibold"> 
        RPJMD TUJUAN TAHUN {{HelperKegiatan::getRPJMDTahunMulai()}} - {{HelperKegiatan::getRPJMDTahunAkhir()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.rpjmd.rpjmdtujuan.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">PERENCANAAN</a></li>
    <li><a href="#">RPJMD</a></li>
    <li><a href="{!!route('rpjmdtujuan.index')!!}">TUJUAN</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA RPJMD TUJUAN
                </h5>
                <div class="heading-elements">   
                    <a href="{{route('rpjmdtujuan.edit',['id'=>$data->PrioritasTujuanKabID])}}" class="btn btn-primary btn-icon heading-btn btnEdit" title="Ubah Data RPJMD Tujuan">
                        <i class="icon-pencil7"></i>
                    </a>
                    <a href="javascript:;" title="Hapus Data RPJMD Tujuan" data-id="{{$data->PrioritasTujuanKabID}}" data-url="{{route('rpjmdtujuan.index')}}" class="btn btn-danger btn-icon heading-btn btnDeleteTujuan">
                        <i class='icon-trash'></i>
                    </a>
                    <a href="{!!route('rpjmdtujuan.create')!!}" class="btn btn-primary btn-info heading-btn btnEdit" title="Tambah RPJMD Tujuan">
                        <i class="icon-googleplus5"></i>
                    </a>
                    <a href="{!!route('rpjmdtujuan.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PrioritasTujuanKabID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->PrioritasTujuanKabID}}</p>
                                </div>                            
                            </div>                        
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE TUJUAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Kd_Tujuan}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA TUJUAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Tujuan}}</p>
                                </div>                            
                            </div>  
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE MISI : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Kd_PrioritasKab}}</p>
                                </div>                            
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA MISI: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_PrioritasKab}}</p>
                                </div>                            
                            </div>     
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. BUAT / TGL. UBAH: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$data->created_at)}} / {{Helper::tanggal('d/m/Y H:m',$data->updated_at)}}</p>
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
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                TAMBAH INDIKATOR TUJUAN
            </h5>            
        </div>
        <div class="panel-body">            
            {!! Form::open(['action'=>'RPJMD\RPJMDTujuanController@store1','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                              
                {{Form::hidden('PrioritasTujuanKabID',$data->PrioritasTujuanKabID)}}                                
                <div class="form-group">
                    {{Form::label('NamaIndikator','NAMA INDIKATOR',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('NamaIndikator','',['class'=>'form-control','placeholder'=>'NAMA INDIKATOR'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Satuan','SATUAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Satuan','',['class'=>'form-control','placeholder'=>'SATUAN'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('KondisiAwal','KONDISI KINERJA AWAL',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('KondisiAwal','',['class'=>'form-control','placeholder'=>'KONDISI KINERJA AWAL'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('KondisiAkhir','KONDISI AKHIR RPJMD',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('KondisiAkhir','',['class'=>'form-control','placeholder'=>'KONDISI AKHIR RPJMD'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr','',['class'=>'form-control','placeholder'=>'KETERANGAN','rows' => 2, 'cols' => 40])}}
                    </div>
                </div>
                <div class="col-md-10 col-md-offset-2">                        
                    {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}                                            
                </div>                
            {!! Form::close()!!}                      
        </div>
    </div>
    <div class="panel panel-flat border-top-lg border-top-info border-bottom-info" id="divdatatableindikatortujuan">
        @include('pages.limitless.rpjmd.rpjmdtujuan.datatableindikatortujuan')
    </div>
</div>   
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    AutoNumeric.multiple(['#KondisiAwal','#KondisiAkhir'], {
                            allowDecimalPadding: false,                            
                            numericPos:true,
                            decimalPlaces : 2,
                            digitGroupSeparator : '',
                            showWarnings:false,
                            unformatOnSubmit: true,
                            modifyValueOnWheel:false
                        });
    $(document).on('click',".btnDeleteTujuan", function(ev) {
        if (confirm('Apakah Anda ingin menghapus Data RPJMD Tujuan ini ?')) {
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
    $("#divdatatableindikatortujuan").on("click",".btnDelete", function(){
        if (confirm('Apakah Anda ingin menghapus Data Indikator Tujuan ini ?')) {
            let url_ = $(this).attr("data-url");
            let id = $(this).attr("data-id");
            $.ajax({            
                type:'post',
                url:url_+'/'+id,
                dataType: 'json',
                data: {
                    "_method": 'DELETE',
                    "_token": token,
                    "id": id,
                    'indikatortujuan':true
                },
                success:function(result){ 
                    if (result.success==1){
                        $('#divdatatableindikatortujuan').html(result.datatable);                        
                    }else{
                        console.log("Gagal menghapus data indikator tujuan dengan id "+id);
                    }                    
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        }        
    });
    $('#frmdata').validate({
        ignore:[],
        rules: {
            NamaIndikator : {
                required: true
            },  
            Satuan : {
                required: true
            },
            KondisiAwal : {
                required: true
            },         
            KondisiAkhir : {
                required: true,
            }           
        },
        messages : {
            NamaIndikator : {
                required: "Mohon untuk di isi nama indikator."              
            },        
            Satuan : {
                required: "Mohon untuk di isi satuan."                
            },
            KondisiAwal : {
                required: "Mohon untuk di isi kondisi kinerja awal"                    
            },
            KondisiAkhir : {
                required: "Mohon untuk di isi kondisi akhir RPJMD"                            
            }            
        }      
    });
});
</script>
@endsection