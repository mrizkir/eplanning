<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <div class="panel-title">
            <div class="row">
                <div class="col-md-1">
                    {!!Form::select('numberRecordPerPage',['1'=>1,'5'=>5,'10'=>10,'15'=>15,'30'=>30,'50'=>50],$numberRecordPerPage,['id'=>'numberRecordPerPage','class'=>'form-control'])!!}                        
                </div>
                <div class="col-md-7">    
                    <select id="filterurusan" class="select">
                        <option></option>
                        @foreach ($daftar_urusan as $k=>$item)
                            <option value="{{$k}}"{{$k==$filter_ursid_selected?'selected':''}}>{{$item}}</option>
                        @endforeach
                    </select>                      
                </div>
            </div>            
        </div>
        <div class="heading-elements">
            <div class="heading-btn">
                <a href="{!!route('program.create')!!}" class="btn btn-info btn-xs" title="Tambah PROGRAM">
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
                        <a class="column-sort text-white" id="col-Kode_Program" data-order="{{$direction}}" href="#">
                            KODE PROGRAM  
                        </a>                                             
                    </th> 
                    <th width="100">
                        <a class="column-sort text-white" id="col-PrgNm" data-order="{{$direction}}" href="#">
                            NAMA PROGRAM  
                        </a>                                             
                    </th> 
                    <th width="100">
                        <a class="column-sort text-white" id="col-Nm_Urusan" data-order="{{$direction}}" href="#">
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
                    <td>
                        @php
                            if ($item->Jns==false && $filter_ursid_selected=='none')
                            {
                                echo 'n.nn.'.$item->Kd_Prog;
                            } 
                            elseif ($item->Jns==false && $filter_ursid_selected!='none') 
                            {
                                echo $filter_kode_urusan_selected.'.'.$item->Kd_Prog;
                            }
                            else {
                                echo $item->kode_program;
                            }   
                        @endphp
                    </td>
                    <td>{{$item->PrgNm}}</td>
                    <td>
                        @php
                            if (!$item->Jns)
                            {
                                echo "SELURUH URUSAN";
                            } 
                            else {
                                echo $item->Nm_Urusan;
                            }   
                        @endphp
                    </td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('program.show',['id'=>$item->PrgID])}}" title="Detail Data Program">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('program.edit',['id'=>$item->PrgID])}}" title="Ubah Data Program">
                                    <i class='icon-pencil7'></i>
                                </a>  
                            </li>
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data Program" data-id="{{$item->PrgID}}" data-url="{{route('program.index')}}">
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
