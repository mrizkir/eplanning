@extends('layouts.limitless.l_main')
@section('page_title')
    TRANSFER RKPD MURNI --> PEMBAHASAN
@endsection
@section('page_header')
    <i class="icon-office position-left"></i>
    <span class="text-semibold"> 
        TRANSFER RKPD MURNI --> PEMBAHASAN TAHUN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>     
@endsection
@section('page_info')
    @include('pages.limitless.rkpd.transferrkpdmurnitopembahasan1.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">RKPD</a></li>
    <li><a href="#">TRANSFER</a></li>
    <li><a href="{!!route('transferrkpdmurnitopembahasan1.index')!!}">TRANSFER RKPD MURNI --> PEMBAHASAN</a></li>
    <li class="active">DETAIL DATA</li>
@endsection
@section('page_content')
<div class="row">    
    <div class="col-md-12">
        <div class="panel panel-flat border-top-info border-bottom-info">
            <div class="panel-heading">
                <h5 class="panel-title"> 
                    <i class="icon-eye"></i>  DATA TRANSFER RKPD MURNI --> PEMBAHASAN
                </h5>
                <div class="heading-elements">                       
                    <a href="{!!route('transferrkpdmurnitopembahasan1.index')!!}" class="btn btn-default btn-icon heading-btn" title="keluar">
                        <i class="icon-close2"></i>
                    </a>            
                </div>
            </div>
            <div class="panel-body">
                <div class="row">                                      
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>KODE OPD/SKPD: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->kode_organisasi}}</p>
                                </div>                            
                            </div>                 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>NAMA OPD/SKPD: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->OrgNm}}</p>
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
                    <div class="col-md-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>URUSAN: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->Nm_Urusan}}</p>
                                </div>                            
                            </div> 
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TA: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{$data->TA}}</p>
                                </div>                            
                            </div>   
                            <div class="form-group">
                                <label class="col-md-4 control-label"><strong>TGL. UBAH: </strong></label>
                                <div class="col-md-8">
                                    <p class="form-control-static">{{Helper::tanggal('d/m/Y H:m',$data->updated_at)}}</p>
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
                <i class="icon-copy position-left"></i> 
                TRANSFER DATA KEGIATAN
            </h5>
            <div class="heading-elements">
                
            </div>
        </div>  
        <div class="panel-body">
            {!! Form::open(['action'=>'RKPD\TransferRKPDMurniTOPembahasan1@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}
                {{Form::hidden('OrgID', $data->OrgID,['id'=>'OrgID'])}}  
                <div class="form-group">            
                    <div class="col-md-2">                        
                        {{ Form::button('<b><i class="icon-arrow-up-right2 "></i></b> TRANSFER', ['type' => 'submit', 'class' => 'btn btn-danger btn-labeled btn-xs','name'=>'btnTransfer','value'=>1] ) }}                        
                        <span class="help-block">Transfer dengan menghapus data EntryLvl2</span>              
                    </div>
                    <div class="col-md-2">                        
                        {{ Form::button('<b><i class="icon-arrow-up-right2 "></i></b> TRANSFER', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs','name'=>'btnTransfer','value'=>2] ) }}                        
                        <span class="help-block">Transfer dengan menghapus data EntryLvl2 kecuali data baru yang di input pada EntryLvl2</span>              
                    </div>
                </div>  
            {!! Form::close()!!}
        </div>
    </div>
</div>
@endsection
