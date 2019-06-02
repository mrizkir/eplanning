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
    <li class="active">TAMBAH DATA</li>
@endsection
@section('page_content')
<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <i class="icon-pencil7 position-left"></i> 
                TAMBAH DATA
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">                    
                    <li>               
                        <a href="{!!route('rpjmdindikatorkinerja.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        {!! Form::open(['action'=>'RPJMD\RPJMDIndikatorKinerjaController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                              
        <div class="panel-body">
            <div class="form-group">
                {{Form::label('PrioritasKebijakanKabID','KEBIJAKAN RPJMD',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="PrioritasKebijakanKabID" id="PrioritasKebijakanKabID" class="select">
                        <option></option>
                        @foreach ($daftar_kebijakan as $k=>$item)
                            <option value="{{$k}}"">{{$item}}</option>
                        @endforeach
                    </select>  
                </div>
            </div>
            <div class="form-group">
                {{Form::label('UrsID','URUSAN',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="UrsID" id="UrsID" class="select">
                        <option></option>
                        @foreach ($daftar_urusan as $k=>$item)
                            <option value="{{$k}}"">{{$item}}</option>
                        @endforeach
                    </select>  
                </div>
            </div>
            <div class="form-group">
                {{Form::label('PrgID','PROGRAM',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="PrgID" id="PrgID" class="select">
                        <option></option>
                    </select>  
                </div>
            </div>
            <div class="form-group">
                {{Form::label('NamaIndikator','NAMA INDIKATOR',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    {{Form::textarea('NamaIndikator','',['class'=>'form-control','placeholder'=>'NAMA INDIKATOR','rows' => 2, 'cols' => 40])}}
                </div>
            </div>
            <div class="form-group">
                {{Form::label('OrgID','NAMA OPD / SKPD 1',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="OrgID" id="OrgID" class="select">
                        <option></option>
                    </select>  
                </div>
            </div>
            <div class="form-group">
                {{Form::label('OrgID2','NAMA OPD / SKPD 2',['class'=>'control-label col-md-2'])}}
                <div class="col-md-10">
                    <select name="OrgID2" id="OrgID2" class="select">
                        <option></option>                        
                    </select>  
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{Form::label('TargetAwal','TARGET AWAL',['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('TargetAwal','',['class'=>'form-control','placeholder'=>'TARGET AWAL','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('PaguDanaN1','PAGU DANA TAHUN KE 1',['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('PaguDanaN1','',['class'=>'form-control','placeholder'=>'PAGU DANA TAHUN KE 1','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('PaguDanaN2','PAGU DANA TAHUN KE 2',['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('PaguDanaN2','',['class'=>'form-control','placeholder'=>'PAGU DANA TAHUN KE 2','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('PaguDanaN3','PAGU DANA TAHUN KE 3',['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('PaguDanaN3','',['class'=>'form-control','placeholder'=>'PAGU DANA TAHUN KE 3','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('PaguDanaN4','PAGU DANA TAHUN KE 4',['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('PaguDanaN4','',['class'=>'form-control','placeholder'=>'PAGU DANA TAHUN KE 4','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('PaguDanaN5','PAGU DANA TAHUN KE 5',['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('PaguDanaN5','',['class'=>'form-control','placeholder'=>'PAGU DANA TAHUN KE 5','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{Form::label('&nbsp;','&nbsp;',['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            &nbsp;
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('TargetN1','TARGET TAHUN KE 1',['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('TargetN1','',['class'=>'form-control','placeholder'=>'TARGET TAHUN KE 1','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('TargetN2','TARGET TAHUN KE 2',['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('TargetN2','',['class'=>'form-control','placeholder'=>'TARGET TAHUN KE 2','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('TargetN3','TARGET TAHUN KE 3',['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('TargetN3','',['class'=>'form-control','placeholder'=>'TARGET TAHUN KE 3','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('TargetN4','TARGET TAHUN KE 4',['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('TargetN4','',['class'=>'form-control','placeholder'=>'TARGET TAHUN KE 4','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('TargetN5','TARGET TAHUN KE 5',['class'=>'control-label col-md-4'])}}
                        <div class="col-md-8">
                            {{Form::text('TargetN5','',['class'=>'form-control','placeholder'=>'TARGET TAHUN KE 5','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                        <div class="col-md-10">
                            {{Form::textarea('Descr','',['class'=>'form-control','placeholder'=>'NAMA INDIKATOR','rows' => 2, 'cols' => 40])}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="form-group">            
                <div class="col-md-10 col-md-offset-2">                        
                    {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}
                </div>
            </div>            
        </div>
        {!! Form::close()!!}
    </div>
</div>   
@endsection
@section('page_asset_js')
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/jquery.validate.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/jquery-validation/additional-methods.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/select2.min.js')!!}"></script>
<script src="{!!asset('themes/limitless/assets/js/autoNumeric.min.js')!!}"></script>
@endsection
@section('page_custom_js')
<script type="text/javascript">
$(document).ready(function () {
    $('#PrioritasKebijakanKabID.select').select2({
        placeholder: "PILIH KEBIJAKAN RPJMD",
        allowClear:true
    });
    $('#UrsID.select').select2({
        placeholder: "PILIH URUSAN",
        allowClear:true
    });
    $('#PrgID.select').select2({
        placeholder: "PILIH PROGRAM",
        allowClear:true
    });
    $('#OrgID.select').select2({
        placeholder: "PILIH OPD / SKPD 1",
        allowClear:true
    });
    $('#OrgID2.select').select2({
        placeholder: "PILIH OPD / SKPD 2",
        allowClear:true
    });
    $(document).on('change','#UrsID',function(ev) {
        alert('test');
    });
});
</script>
@endsection