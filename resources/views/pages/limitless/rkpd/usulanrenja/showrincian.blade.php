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
    @include('pages.limitless.rkpd.usulanrenja.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">WORKFLOW</a></li>
    <li><a href="{!!route(Helper::getNameOfPage('index'))!!}">{{$page_title}}</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  
                    DETAIL RINCIAN RENCANA KEGIATAN 
                    @if ($renja->Privilege==1))
                        <span class="label label-success label-rounded">SUDAH DI TRANSFER</span>
                    @endif
                </h5>
                <div class="heading-elements">   
                 @if ($renja->Privilege==0))
                     @if ($renja->isSKPD)
                        <a href="{{route(Helper::getNameOfPage('edit4'),['uuid'=>$renja->RenjaRincID])}}" title="Ubah Data {{$page_title}}" class="btn btn-primary btn-icon heading-btn btnEdit">
                            <i class='icon-pencil7'></i>
                        </a> 
                    @elseif($renja->isReses)
                        <a href="{{route(Helper::getNameOfPage('edit3'),['uuid'=>$renja->RenjaRincID])}}" title="Ubah Data {{$page_title}}" class="btn btn-primary btn-icon heading-btn btnEdit">
                            <i class='icon-pencil7'></i>
                        </a>
                    @elseif(!empty($renja->UsulanKecID))
                        <a href="{{route(Helper::getNameOfPage('edit2'),['uuid'=>$renja->RenjaRincID])}}" title="Ubah Data {{$page_title}}" class="btn btn-primary btn-icon heading-btn btnEdit">
                            <i class='icon-pencil7'></i>
                        </a>
                    @else
                        <a href="{{route(Helper::getNameOfPage('edit4'),['uuid'=>$renja->RenjaRincID])}}" title="Ubah Data {{$page_title}}" class="btn btn-primary btn-icon heading-btn btnEdit">
                            <i class='icon-pencil7'></i>
                        </a>
                    @endif                    
                    @endif{{-- akhir privilege --}}
                    <a href="{!!route(Helper::getNameOfPage('show'),['uuid'=>$renja->RenjaID])!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>  
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>RENJA RINCIAN ID: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$renja->RenjaRincID}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NO: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$renja->No}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>URAIAN : </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$renja->Uraian}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NILAI PAGU: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatUang($renja->Jumlah)}}</p>                               
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>SASARAN KEGIATAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($renja->Sasaran_Angka)}} {{$renja->Sasaran_Uraian}}</p>
                                </div>                            
                            </div>                                
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-horizontal"> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TARGET (%): </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::formatAngka($renja->Target)}}</p>
                                </div>                            
                            </div>                             
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>STATUS: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                        @include('layouts.limitless.l_status_kegiatan')
                                    </p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>PRIORITAS: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                        <span class="label label-flat border-pink text-pink-600">
                                            {{HelperKegiatan::getNamaPrioritas($item->Prioritas)}}
                                        </span>
                                    </p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. BUAT: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$renja->created_at)}}</p>
                                </div>                            
                            </div>  
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. UBAH: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$renja->updated_at)}}</p>
                                </div>                            
                            </div>                     
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-play4"></i>  
                    GESER RINCIAN INI KE RENJA DI DALAM OPD ATAU OPD LAIN TAHAPAN {{$page_title}}                   
                </h5>
                <div class="heading-elements">   
                     
                </div>
            </div>
            {!! Form::open(['url'=>route(Helper::getNameOfPage('geserrincian'),$renja->RenjaRincID),'method'=>'put','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                      

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
                    <div class="form-group">
                        {{Form::label('RenjaID','DAFTAR RENCANA KERJA (RENJA)',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::select('RenjaID', $daftar_renja, $renja->RenjaID,['class'=>'select','id'=>'RenjaID'])}}  
                        </div>
                    </div>
                    <div class="form-group">            
                        <div class="col-md-10 col-md-offset-2">                        
                            {{ Form::button('<b><i class="icon-play "></i></b> GESERKAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}                        
                        </div>
                    </div>  
                </div>   
            </div>
            {!! Form::close()!!}
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
    $('#OrgID.select').select2({
        placeholder: "PILIH OPD / SKPD",
        allowClear:true
    }); 
    $('#SOrgID.select').select2({
        placeholder: "PILIH UNIT KERJA",
        allowClear:true
    });  
    $('#RenjaID.select').select2({
        placeholder: "PILIH RENCANA KERJA (RENJA)",
        allowClear:true
    });
    $(document).on('change','#OrgID',function(ev) {
        ev.preventDefault();   
        $.ajax({
            type:'post',
            url: url_current_page +'/filtershowrincian',
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
            url: url_current_page +'/filtershowrincian',
            dataType: 'json',
            data: {                
                "_token": token,
                "SOrgID": $('#SOrgID').val(),
            },
            success:function(result)
            {
                var daftar_renja = result.daftar_renja;
                var listitems='<option></option>';
                $.each(daftar_renja,function(key,value){
                    listitems+='<option value="' + key + '">'+value+'</option>';                    
                });
                
                $('#RenjaID').html(listitems);      
            },
            error:function(xhr, status, error){
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });     
    });
    $('#frmdata').validate({
        ignore:[],
        rules: {
            RenjaID : {
                required: true
            },
        },
        messages : {
            RenjaID : {
                required: "Mohon untuk di pilih renja tujuan.",                
            }, 
        }
    });
});
</script>
@endsection