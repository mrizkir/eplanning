<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <div class="panel-title">
            <div class="row">
                <div class="col-md-1">                    		
					{!!Form::select('numberRecordPerPage',['1'=>1,'5'=>5,'10'=>10,'15'=>15,'30'=>30,'50'=>50],$numberRecordPerPage,['id'=>'numberRecordPerPage','class'=>'form-control'])!!}                        
                </div>
            </div>
        </div>
        <div class="heading-elements">
            
        </div>
    </div>
    @if (count($data) > 0)
    <table id="data" class="table table-striped table-hover table-responsive">
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
                    <a class="column-sort text-white" id="col-Sasaran_Angka3" data-order="{{$direction}}" href="#">
                        SASARAN  
                    </a>                                             
                </th> 
                <th width="120">                        
                    TARGET (%)                        
                </th> 
                <th width="150" class="text-right">
                    <a class="column-sort text-white" id="col-Jumlah3" data-order="{{$direction}}" href="#">
                        NILAI  
                    </a>                                             
                </th> 
                <th width="120">                        
                    PRIORITAS                          
                </th>          
                <th width="70">TRANSFER</th>                            
                <th width="150">AKSI</th>
            </tr>
        </thead>
        <tbody>                    
        @foreach ($data as $key=>$item)
            <tr>
                <td>
                    {{ ($data->currentpage()-1) * $data->perpage() + $key + 1 }}    
                </td>
                <td>
                    {{$item->kode_kegiatan}}                        
                </td>
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
                <td colspan="6">
                    <span class="label label-flat label-block border-info text-info-600">
                        PROSES INPUT FORUM OPD / SKPD BELUM SELESAI
                    </span>
                    <a href="{{route('pembahasanforumopd.create1',['uuid'=>$item->RenjaID])}}">
                        Lanjutkan Input 
                    </a>
                </td>
                <td>
                    <ul class="icons-list">                            
                        <li class="text-danger-600">
                            <a class="btnDelete" href="javascript:;" title="Hapus Data Usulan Forum OPD / SKPD" data-id="{{$item->RenjaID}}" data-url="{{route('pembahasanforumopd.index')}}">
                                <i class='icon-trash'></i>
                            </a> 
                        </li>
                    </ul>
                </td>
                @else
                <td>
                    {{ucwords($item->Uraian)}}<br />
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
                <td>{{Helper::formatAngka($item->Sasaran_Angka3)}} {{$item->Sasaran_Uraian3}}</td>
                <td>{{$item->Target3}}</td>
                <td class="text-right">{{Helper::formatuang($item->Jumlah3)}}</td>
                <td>
                    <span class="label label-flat border-success text-success-600">
                        {{HelperKegiatan::getNamaPrioritas($item->Prioritas)}}
                    </span>
                </td>
                <td>
                    @include('layouts.limitless.l_status_kegiatan')                        
                </td>
                <td>
                    <ul class="icons-list">
                        @include('layouts.limitless.l_ubah_status')
                        @if ($item->Privilege==0)              
                        <li class="text-primary-600">
                            @if ($item->isSKPD)
                                <a class="btnEdit" href="{{route('usulanforumopd.edit4',['id'=>$item->RenjaRincID])}}" title="Ubah Data Usulan Pra Renja">
                                    <i class='icon-pencil7'></i>
                                </a> 
                            @elseif($item->isReses)
                                <a class="btnEdit" href="{{route('usulanforumopd.edit3',['id'=>$item->RenjaRincID])}}" title="Ubah Data Usulan Pra Renja">
                                    <i class='icon-pencil7'></i>
                                </a>
                            @else
                                <a class="btnEdit" href="{{route('usulanforumopd.edit2',['id'=>$item->RenjaRincID])}}" title="Ubah Data Usulan Pra Renja">
                                    <i class='icon-pencil7'></i>
                                </a>
                            @endif  
                        </li> 
                        @endif
                    </ul>
                </td>
                @endif                    
            </tr>
            <tr class="text-center info">
                <td colspan="10">
                    <span class="label label-warning label-rounded">
                        <strong>RenjaID:</strong>
                        {{$item->RenjaID}}
                    </span>
                    <span class="label label-warning label-rounded">
                        <strong>RenjaRincID:</strong>
                        {{$item->RenjaRincID}}
                    </span>
                    <span class="label label-warning label-rounded">
                        <strong>KET:</strong>
                        {{empty($item->Descr)?'-':$item->Descr}}
                    </span>
                </td>
            </tr>
        @endforeach                    
        </tbody>
    </table>               
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