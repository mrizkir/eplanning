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
                <a href="{!!route('usulanprarenjaopd.create')!!}" class="btn btn-info btn-xs" title="Tambah USULANPRARENJAOPD">
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
                    <th width="55">NO</th>     
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
                        <a class="column-sort text-white" id="col-Sasaran_Angka1" data-order="{{$direction}}" href="#">
                            SASARAN  
                        </a>                                             
                    </th> 
                    <th width="120">                        
                        TARGET (%)                        
                    </th> 
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-Jumlah1" data-order="{{$direction}}" href="#">
                            NILAI  
                        </a>                                             
                    </th> 
                    <th width="80">
                        <a class="column-sort text-white" id="col-Status" data-order="{{$direction}}" href="#">
                            STATUS  
                        </a>                                             
                    </th> 
                    <th width="120">AKSI</th>
                </tr>
            </thead>
            <tbody>                    
            @foreach ($data as $key=>$item)
                <tr>
                    <td>
                        {{ ($data->currentpage()-1) * $data->perpage() + $key + 1 }}    
                    </td>
                    <td>{{$item->kode_kegiatan}}</td>
                    <td>{{$item->KgtNm}}</td>
                    <td>{{$item->Uraian}}</td>
                    <td>{{Helper::formatAngka($item->Sasaran_Angka1)}} {{$item->Sasaran_Uraian1}}</td>
                    <td>{{$item->Target1}}</td>
                    <td class="text-right">{{Helper::formatuang($item->Jumlah1)}}</td>
                    <td>{{$item->Status}}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('usulanprarenjaopd.show',['id'=>$item->usulanprarenjaopd_id])}}" title="Detail Data UsulanPraRenjaOPD">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('usulanprarenjaopd.edit',['id'=>$item->usulanprarenjaopd_id])}}" title="Ubah Data UsulanPraRenjaOPD">
                                    <i class='icon-pencil7'></i>
                                </a>  
                            </li>
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data UsulanPraRenjaOPD" data-id="{{$item->usulanprarenjaopd_id}}" data-url="{{route('usulanprarenjaopd.index')}}">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                        </ul>
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
