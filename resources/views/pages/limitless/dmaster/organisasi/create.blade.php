@extends('layouts.limitless.l_main')
@section('page_title')
    ORGANISASI
@endsection
@section('page_header')
    <i class="icon-office position-left"></i>
    <span class="text-semibold"> 
        ORGANISASI TAHUN {{HelperKegiatan::getTahunPerencanaan()}}
    </span>
@endsection
@section('page_info')
    @include('pages.limitless.dmaster.organisasi.info')
@endsection
@section('page_breadcrumb')
    <li><a href="#">MASTERS</a></li>
    <li><a href="#">DATA</a></li>
    <li><a href="{!!route('organisasi.index')!!}">ORGANISASI</a></li>
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
                        <a href="{!!route('organisasi.index')!!}" data-action="closeredirect" title="keluar"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(['action'=>'DMaster\OrganisasiController@store','method'=>'post','class'=>'form-horizontal','id'=>'frmdata','name'=>'frmdata'])!!}                                              
                <div class="form-group">
                    {{Form::label('UrsID','URUSAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        <select name="UrsID" id="UrsID" class="select">
                            <option></option>
                            @foreach ($daftar_urusan as $k=>$item)
                                <option value="{{$k}}">{{$item}}</option>
                            @endforeach
                        </select>                        
                    </div>
                </div>         
                <div class="form-group">
                    {{Form::label('OrgCd','KODE SKPD / OPD',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('OrgCd','',['class'=>'form-control','placeholder'=>'KODE SKPD / OPD','maxlength'=>4])}}
                        {{Form::hidden('kode_organisasi','none',['id'=>'kode_organisasi'])}}
                    </div>
                </div>  
                <div class="form-group">
                    {{Form::label('OrgNm','NAMA SKPD / OPD',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('OrgNm','',['class'=>'form-control','placeholder'=>'NAMA SKPD / OPD'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('OrgAlias','SINGKATAN SKPD / OPD',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('OrgAlias','',['class'=>'form-control','placeholder'=>'NAMA SINGKATAN SKPD / OPD'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('NIPKepalaSKPD','NIP KEPALA SKPD / OPD',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('NIPKepalaSKPD','',['class'=>'form-control','placeholder'=>'NIP KEPALA SKPD / OPD'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('NamaKepalaSKPD','NAMA KEPALA SKPD / OPD',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('NamaKepalaSKPD','',['class'=>'form-control','placeholder'=>'NAMA KEPALA SKPD / OPD'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Alamat','ALAMAT',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::text('Alamat','',['class'=>'form-control','placeholder'=>'ALAMAT SKPD / OPD'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('Descr','KETERANGAN',['class'=>'control-label col-md-2'])}}
                    <div class="col-md-10">
                        {{Form::textarea('Descr','',['class'=>'form-control','placeholder'=>'KETERANGAN','rows' => 2, 'cols' => 40])}}
                    </div>
                </div>                
                <div class="form-group">            
                    <div class="col-md-10 col-md-offset-2">                        
                        {{ Form::button('<b><i class="icon-floppy-disk "></i></b> SIMPAN', ['type' => 'submit', 'class' => 'btn btn-info btn-labeled btn-xs'] ) }}
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
    $('.select').select2({
        placeholder: "PILIH URUSAN",
        allowClear:true
    });
    $('#frmdata').validate({
        ignore:[],
        rules: {
            UrsID : {
                required : true,
            },
            OrgCd : {
                required: true,  
                number: true,
                maxlength: 4              
            },
            OrgNm : {
                required: true,
                minlength: 5      
            },
            OrgAlias : {
                required: true,
            },
            NIPKepalaSKPD : {
                required: true,
                minlength: 5      
            },
            NamaKepalaSKPD : {
                required: true,
                minlength: 5      
            },
            Alamat : {
                required: true,
                minlength: 5      
            }
        },
        messages : {
            UrsID : {
                required: "Mohon dipilih Urusan !"
            },
            OrgCd : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                number: "Mohon input dengan tipe data bilangan bulat",
                maxlength: "Nilai untuk Kode Urusan maksimal 4 digit"
            },
            OrgNm : {
                required: "Mohon untuk di isi karena ini diperlukan.",  
                minlength : "Mohon di isi minimal 5 karakter atau lebih."          
            },
            OrgAlias : {
                required: "Mohon untuk di isi karena ini diperlukan. misalya DINAS SOSIAL nama singkatannya DINSOS",  
            },
            NIPKepalaSKPD : {
                required: "Mohon untuk di isi karena ini diperlukan.",  
                minlength : "Mohon di isi minimal 5 karakter atau lebih."          
            },
            NamaKepalaSKPD : {
                required: "Mohon untuk di isi karena ini diperlukan.",  
                minlength : "Mohon di isi minimal 5 karakter atau lebih."          
            },
            Alamat : {
                required: "Mohon untuk di isi karena ini diperlukan.",
                minlength: "Mohon di isi minimal 5 karakter atau lebih."
            }
        }           
    });  
    $("#frmdata :input").not('[name=UrsID]').prop("disabled", true); 
    $(document).on('change','#UrsID',function(ev) {
        ev.preventDefault();  
        UrsID=$(this).val();
        if (UrsID == 'none')
        {
            $("#frmdata :input").not('[name=UrsID]').prop("disabled", true);
            $("#kode_organisasi").val('none');  
        }
        else
        {
            $("#frmdata *").prop("disabled", false);
        }
    });
});
</script>
@endsection