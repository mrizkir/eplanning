<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <div class="panel-title" style="width:70px">
            {!!Form::select('numberRecordPerPage',['1'=>1,'5'=>5,'10'=>10,'15'=>15,'30'=>30,'50'=>50],$numberRecordPerPage,['id'=>'numberRecordPerPage','class'=>'form-control'])!!}            
        </div>
        <div class="heading-elements">
            <div class="heading-btn">
                <a href="{!!route('provinsi.create')!!}" class="btn btn-info btn-xs" title="Tambah PROVINSI">
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
                    <th width="100">
                        <a class="column-sort text-white" id="col-Kd_Prov" data-order="{{$direction}}" href="#">
                            KODE PROVINSI  
                        </a>                                             
                    </th> 
                    <th width="400">
                        <a class="column-sort text-white" id="col-Nm_Prov" data-order="{{$direction}}" href="#">
                            NAMA PROVINSI  
                        </a>                                             
                    </th>                     
                    <th width="120">
                        KETERANGAN                                            
                    </th> 
                    <th width="70">
                        TA                                            
                    </th> 
                    <th width="100">AKSI</th>
                </tr>
            </thead>
            <tbody>                    
            @foreach ($data as $key=>$item)
                <tr>
                    <td>
                        {{ ($data->currentpage()-1) * $data->perpage() + $key + 1 }}    
                    </td>                  
                    <td>{{$item->Kd_Prov}}</td>                    
                    <td>{{$item->Nm_Prov}}</td>
                    <td>{{$item->Descr}}</td>
                    <td>{{$item->TA}}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('provinsi.show',['id'=>$item->PMProvID])}}" title="Detail Data Provinsi">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('provinsi.edit',['id'=>$item->PMProvID])}}" title="Ubah Data Provinsi">
                                    <i class='icon-pencil7'></i>
                                </a>  
                            </li>
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data Provinsi" data-id="{{$item->PMProvID}}" data-url="{{route('provinsi.index')}}">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr class="text-center info">
                    <td colspan="10">                   
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>PMPROVINSIID:</strong>
                            {{$item->PMProvID}}
                        </span>  
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>CREATED:</strong>
                            {{Helper::tanggal('d/m/Y H:m',$item->created_at)}}
                        </span>
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>UPDATED:</strong>
                            {{Helper::tanggal('d/m/Y H:m',$item->updated_at)}}
                        </span>
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
            Belum ada data yang bisa ditampilkan.
        </div>
    </div>   
    @endif            
</div>