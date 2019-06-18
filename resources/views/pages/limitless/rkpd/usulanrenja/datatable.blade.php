<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <div class="panel-title">
            <h6 class="panel-title">&nbsp;</h6>            
        </div>
        <div class="heading-elements">
            {!! Form::open(['url'=>'#','method'=>'post','class'=>'heading-form','id'=>'frmheading','name'=>'frmheading'])!!} 
                <div class="form-group">
                    <label class="checkbox-inline checkbox-right checkbox-switch checkbox-switch-sm">
                        DRAFT
                        <input id="chkDraft" name="chkDraft" type="checkbox" class="switch" data-on-text="ON" data-off-text="OFF" data-size="small" checked="checked">
                    </label>
                </div>
                <div class="form-group">
                    <label class="checkbox-inline checkbox-right checkbox-switch checkbox-switch-sm">
                        SETUJU
                        <input id="chkSetuju" name="chkSetuju" type="checkbox" class="switch" data-on-text="ON" data-off-text="OFF" data-size="small" checked="checked">
                    </label>
                </div>                                               
                <div class="form-group">
                    <label class="checkbox-inline checkbox-right checkbox-switch checkbox-switch-sm">
                        SETUJU DG. CATATAN
                        <input id="chkSetujuDgCatatan" name="chkSetujuDgCatatan" type="checkbox" class="switch" data-on-text="ON" data-off-text="OFF" data-size="small" checked="checked">
                    </label>
                </div>                
                <div class="form-group">
                    <label class="checkbox-inline checkbox-right checkbox-switch checkbox-switch-sm">
                        PENDING
                        <input id="chkPending" name="chkName" type="checkbox" class="switch" data-on-text="ON" data-off-text="OFF" data-size="small" checked="checked">
                    </label>
                </div>                
                <div class="form-group">
                    {!!Form::select('numberRecordPerPage',['1'=>1,'5'=>5,'10'=>10,'15'=>15,'30'=>30,'50'=>50],$numberRecordPerPage,['id'=>'numberRecordPerPage','class'=>'form-control','style'=>'width:70px'])!!}                        
                </div>    
                <div class="form-group">
                    <a href="{!!route(Helper::getNameOfPage('create'))!!}" class="btn btn-info btn-xs" title="Tambah Usulan Kegiatan">
                        <i class="icon-googleplus5"></i>
                    </a>
                </div>            
            {!! Form::close()!!}           
        </div>
    </div>
    @if (count($data) > 0)
    <div class="table-responsive"> 
        <table id="data" class="table table-striped table-hover">
            <thead>
                <tr class="bg-teal-700">
                    <th width="50">NO</th>     
                    <th width="150">
                        <a class="column-sort text-white" id="col-kode_kegiatan" data-order="{{$direction}}" href="#">
                            KODE KEGIATAN                                                                       
                        </a>
                    </th>                
                    <th width="400">
                        <a class="column-sort text-white" id="col-KgtNm" data-order="{{$direction}}" href="#">
                            NAMA KEGIATAN                                                                       
                        </a>
                    </th> 
                    <th width="300">
                        <a class="column-sort text-white" id="col-Uraian" data-order="{{$direction}}" href="#">
                            NAMA URAIAN                                                                       
                        </a>
                    </th>
                    <th width="200">
                        <a class="column-sort text-white" id="col-Sasaran_Angka" data-order="{{$direction}}" href="#">
                            SASARAN  
                        </a>                                             
                    </th> 
                    <th width="120">                        
                        TARGET (%)                        
                    </th> 
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-Jumlah" data-order="{{$direction}}" href="#">
                            NILAI  
                        </a>                                             
                    </th> 
                    <th width="120">                        
                        PRIO.                        
                    </th>
                    <th width="80">
                        <a class="column-sort text-white" id="col-status" data-order="{{$direction}}" href="#">
                            STATUS  
                        </a>                                             
                    </th> 
                    <th>VER.</th>
                    <th width="200">AKSI</th>
                </tr>
            </thead>
            <tbody>                    
            @foreach ($data as $key=>$item)
                <tr>
                    <td>
                        {{ ($data->currentpage()-1) * $data->perpage() + $key + 1 }}    
                    </td>
                    <td>{{$item->kode_kegiatan}}</td>
                    <td>
                        {{ucwords($item->KgtNm)}}
                        @if ($item->Status_Indikator==0)
                            <br>
                            <span class="label label-flat border-warning text-warning-600">
                                INDIKATOR TIDAK ADA
                            </span>
                        @endif
                    </td>
                    @if ($item->RenjaRincID=='')
                    <td colspan="7">
                        <span class="label label-flat label-block border-info text-info-600">
                            PROSES INPUT BELUM SELESAI
                        </span>
                        <a href="{{route(Helper::getNameOfPage('create1'),['uuid'=>$item->RenjaID])}}">
                            Lanjutkan Input 
                        </a>
                    </td>
                    <td>
                        <ul class="icons-list">                            
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data {{$page_title}}" data-id="{{$item->RenjaID}}" data-url="{{route(Helper::getNameOfPage('index'))}}" data-pid="renja">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                        </ul>
                    </td>
                    @else
                    <td>
                        {{ucwords($item->Uraian)}}
                        @if ($item->isSKPD)
                            <br />
                            <span class="label label-flat border-grey text-grey-600">                        
                                <a href="#">
                                    <strong>Usulan dari: </strong>OPD / SKPD
                                </a> 
                            </span>
                        @elseif($item->isReses)
                            <br />
                            <span class="label label-flat border-grey text-grey-600">                        
                                <a href="#">
                                    <strong>Usulan dari: </strong>POKIR [{{$item->isReses_Uraian}}]
                                </a>
                            </span>
                        @elseif(!empty($item->UsulanKecID))
                            <br />
                            <span class="label label-flat border-grey text-grey-600">                        
                                <a href="{{route('aspirasimusrenkecamatan.show',['id'=>$item->UsulanKecID])}}">
                                    <strong>Usulan dari: MUSREN. KEC. {{$item->Nm_Kecamatan}}
                                </a>
                            </span>
                        @endif
                    </td>
                    <td>{{Helper::formatAngka($item->Sasaran_Angka)}} {{$item->Sasaran_Uraian}}</td>
                    <td>{{$item->Target}}</td>
                    <td class="text-right">{{Helper::formatuang($item->Jumlah)}}</td>
                    <td>
                        <span class="label label-flat border-pink text-pink-600">
                            {{HelperKegiatan::getNamaPrioritas($item->Prioritas)}}
                        </span>
                    </td>                    
                    <td>
                        @include('layouts.limitless.l_status_kegiatan')    
                    </td>
                    <td>                    
                        @if ($item->Privilege==0)
                        <span class="label label-flat border-grey text-grey-600 label-icon">
                            <i class="icon-cross2"></i>
                        </span>
                        @else
                            <span class="label label-flat border-success text-success-600 label-icon">
                                <i class="icon-checkmark"></i>
                            </span>                            
                        @endif                    
                    </td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route(Helper::getNameOfPage('show'),['id'=>$item->RenjaID])}}" title="Detail Data {{$page_title}}">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            @if ($item->Privilege==0 && $item->Locked==false)
                            <li class="text-primary-600">
                                @if ($item->isSKPD)
                                    <a class="btnEdit" href="{{route(Helper::getNameOfPage('edit4'),['id'=>$item->RenjaRincID])}}" title="Ubah Data {{$page_title}}">
                                        <i class='icon-pencil7'></i>
                                    </a> 
                                @elseif($item->isReses)
                                    <a class="btnEdit" href="{{route(Helper::getNameOfPage('edit3'),['id'=>$item->RenjaRincID])}}" title="Ubah Data {{$page_title}}">
                                        <i class='icon-pencil7'></i>
                                    </a>
                                @elseif(!empty($item->UsulanKecID))
                                    <a class="btnEdit" href="{{route(Helper::getNameOfPage('edit2'),['id'=>$item->RenjaRincID])}}" title="Ubah Data {{$page_title}}">
                                        <i class='icon-pencil7'></i>
                                    </a>
                                @else
                                    <a class="btnEdit" href="{{route(Helper::getNameOfPage('edit4'),['id'=>$item->RenjaRincID])}}" title="Ubah Data {{$page_title}}">
                                        <i class='icon-pencil7'></i>
                                    </a>
                                @endif
                            </li>                            
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data Usulan {{$page_title}}" data-id="{{$item->RenjaRincID}}" data-url="{{route(Helper::getNameOfPage('index'))}}" data-pid="rincian">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                            @endif
                        </ul>
                    </td>
                    @endif                    
                </tr>
                <tr class="text-center info">
                    <td colspan="10">
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>RENJAID:</strong>
                            {{$item->RenjaID}}
                        </span>
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>RENJARINCID:</strong>
                            {{$item->RenjaRincID}}
                        </span>
                        <span class="label label-warning label-rounded">
                            <strong>KET:</strong>
                            {{empty($item->Descr)?'-':$item->Descr}}
                        </span>
                    </td>
                    <td class='text-right'>
                        {!!$item->Locked==false?'<i class="icon-unlocked2"></i>':'<i class="icon-lock2"></i>'!!}
                    </td>
                </tr>
            @endforeach                    
            </tbody>
        </table>               
    </div>
    <div class="panel-body border-top-info text-center" id="paginations">
        {{$data->links('layouts.limitless.l_pagination')}}               
    </div>
    @else
    <div class="panel-body">
        <div class="alert alert-info alert-styled-left alert-bordered">
            <span class="text-semibold">Info!</span>
            Belum ada data yang bisa ditampilkan. Mohon pilih terlebih dahulu OPD dan Unit Kerja
        </div>
    </div>   
    @endif            
</div>