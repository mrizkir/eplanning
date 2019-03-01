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
                <a href="{!!route('organisasi.create')!!}" class="btn btn-info btn-xs" title="Tambah ORGANISASI">
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
                    <th>
                        <a class="column-sort text-white" id="col-kode_organisasi" data-order="{{$direction}}" href="#">
                            KODE SKPD / OPD
                        </a>                                             
                    </th> 
                    <th>
                        <a class="column-sort text-white" id="col-kode_organisasi" data-order="{{$direction}}" href="#">
                            NAMA SKPD / OPD
                        </a>                                             
                    </th> 
                    <th>
                        <a class="column-sort text-white" id="col-kode_organisasi" data-order="{{$direction}}" href="#">
                            URUSAN
                        </a>                                             
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
                    <td>{{$item->kode_organisasi}}</td>
                    <td>{{$item->OrgNm}}</td>
                    <td>{{$item->Nm_Urusan}}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('organisasi.show',['id'=>$item->OrgID])}}" title="Detail Data Organisasi">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('organisasi.edit',['id'=>$item->OrgID])}}" title="Ubah Data Organisasi">
                                    <i class='icon-pencil7'></i>
                                </a>  
                            </li>
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data Organisasi" data-id="{{$item->OrgID}}" data-url="{{route('organisasi.index')}}">
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
            Belum ada data yang bisa ditampilkan.
        </div>
    </div>   
    @endif            
</div>
