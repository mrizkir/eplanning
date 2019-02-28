<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <div class="panel-title" style="width:70px">
            {!!Form::select('numberRecordPerPage',['1'=>1,'5'=>5,'10'=>10,'15'=>15,'30'=>30,'50'=>50],$numberRecordPerPage,['id'=>'numberRecordPerPage','class'=>'form-control'])!!}            
        </div>
        <div class="heading-elements">
            <div class="heading-btn">
                <a href="{!!route('programkegiatan.create')!!}" class="btn btn-info btn-xs" title="Tambah PROGRAMKEGIATAN">
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
                        <a class="column-sort text-white" id="col-kode_kegiatan" data-order="{{$direction}}" href="#">
                            KODE KEGIATAN  
                        </a>                                             
                    </th> 
                    <th>
                        <a class="column-sort text-white" id="col-KgtNm" data-order="{{$direction}}" href="#">
                            NAMA KEGIATAN  
                        </a>                                             
                    </th> 
                    <th>
                        <a class="column-sort text-white" id="col-PrgNm" data-order="{{$direction}}" href="#">
                            PROGRAM  
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
                    <td>
                        @php
                            if ($item->Jns==false && $filter_prgid_selected=='none')
                            {
                                echo 'n.nn.'.$item->Kd_Prog.'.'.$item->Kd_Keg;
                            } 
                            elseif ($item->Jns==false && $filter_prgid_selected!='none') 
                            {
                                echo $filter_kode_program_selected.'.'.$item->Kd_Keg;
                            }
                            else {
                                echo $item->kode_kegiatan;
                            }   
                        @endphp                        
                    </td>
                    <td>{{$item->KgtNm}}</td>
                    <td>{{$item->PrgNm}}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('programkegiatan.show',['id'=>$item->KgtID])}}" title="Detail Data ProgramKegiatan">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('programkegiatan.edit',['id'=>$item->KgtID])}}" title="Ubah Data ProgramKegiatan">
                                    <i class='icon-pencil7'></i>
                                </a>  
                            </li>
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data ProgramKegiatan" data-id="{{$item->KgtID}}" data-url="{{route('programkegiatan.index')}}">
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
