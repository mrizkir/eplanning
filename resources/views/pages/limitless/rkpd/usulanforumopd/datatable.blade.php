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
            <div class="heading-btn">
                <a href="{!!route('usulanforumopd.create')!!}" class="btn btn-info btn-xs" title="Tambah Usulan Kegiatan">
                    <i class="icon-googleplus5"></i>
                </a>
            </div>            
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
                    <th width="80">
                        <a class="column-sort text-white" id="col-status" data-order="{{$direction}}" href="#">
                            STATUS  
                        </a>                                             
                    </th> 
                    <th width="150">AKSI</th>
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
                        @if (empty($item->RenjaIndikatorID))
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
                        <a href="{{route('usulanforumopd.create1',['uuid'=>$item->RenjaID])}}">
                            Lanjutkan Input 
                        </a>
                    </td>
                    <td>
                        <ul class="icons-list">                            
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data Usulan Forum OPD" data-id="{{$item->RenjaID}}" data-url="{{route('usulanforumopd.index')}}" data-pid="renja">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                        </ul>
                    </td>
                    @else
                    <td>
                        {{ucwords($item->Uraian)}}<br />
                        <span class="label label-flat border-grey text-grey-600">                        
                            @if ($item->isSKPD)
                                <a href="#">
                                    <strong>Usulan dari: </strong>OPD / SKPD
                                </a> 
                            @elseif($item->isReses)
                                <a href="#">
                                    <strong>Usulan dari: </strong>POKIR [{{$item->isReses_Uraian}}]
                                </a>
                            @else
                                <a href="{{route('aspirasimusrenkecamatan.show',['id'=>$item->UsulanKecID])}}">
                                    <strong>Usulan dari: MUSREN. KEC. {{$item->Nm_Kecamatan}}
                                </a>
                            @endif
                        </span>
                    </td>
                    <td>{{Helper::formatAngka($item->Sasaran_Angka3)}} {{$item->Sasaran_Uraian3}}</td>
                    <td>{{$item->Target3}}</td>
                    <td class="text-right">{{Helper::formatuang($item->Jumlah3)}}</td>
                    <td>
                        <span class="label label-flat border-success text-success-600">
                            {{HelperKegiatan::getNamaPrioritas($item->Prioritas)}}
                        </span>
                    </td>
                    <td>{{$item->status}}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('usulanforumopd.show',['id'=>$item->RenjaID])}}" title="Detail Data Usulan Forum OPD">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                @if ($item->isSKPD)
                                    <a class="btnEdit" href="{{route('usulanforumopd.edit4',['id'=>$item->RenjaRincID])}}" title="Ubah Data Usulan Forum OPD">
                                        <i class='icon-pencil7'></i>
                                    </a> 
                                @elseif($item->isReses)
                                    <a class="btnEdit" href="{{route('usulanforumopd.edit3',['id'=>$item->RenjaRincID])}}" title="Ubah Data Usulan Forum OPD">
                                        <i class='icon-pencil7'></i>
                                    </a>
                                @else
                                    <a class="btnEdit" href="{{route('usulanforumopd.edit2',['id'=>$item->RenjaRincID])}}" title="Ubah Data Usulan Forum OPD">
                                        <i class='icon-pencil7'></i>
                                    </a>
                                @endif
                            </li>
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data Usulan Forum OPD" data-id="{{$item->RenjaRincID}}" data-url="{{route('usulanforumopd.index')}}" data-pid="rincian">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                        </ul>
                    </td>
                    @endif                    
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