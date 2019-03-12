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
                <a href="{!!route('mappingprogramtoopd.create')!!}" class="btn btn-info btn-xs" title="Tambah Mapping Program">
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
                        <a class="column-sort text-white" id="col-OrgID" data-order="{{$direction}}" href="#">
                            KODE SKPD / OPD
                        </a>                                             
                    </th> 
                    <th width="250">
                        <a class="column-sort text-white" id="col-NmOrg" data-order="{{$direction}}" href="#">
                            NAMA SKPD / OPD
                        </a>                                             
                    </th> 
                    <th width="120">
                        <a class="column-sort text-white" id="col-Kode_Program" data-order="{{$direction}}" href="#">
                            KODE PROGRAM  
                        </a>                                             
                    </th>
                    <th>
                        <a class="column-sort text-white" id="col-PrgNm" data-order="{{$direction}}" href="#">
                            NAMA PROGRAM  
                        </a>                                             
                    </th> 
                    <th>
                        <a class="column-sort text-white" id="col-Nm_Urusan" data-order="{{$direction}}" href="#">
                            URUSAN  
                        </a>                                             
                    </th>
                    <th width="80">AKSI</th>
                </tr>
            </thead>
            <tbody>                    
            @foreach ($data as $key=>$item)
                <tr>
                    <td>
                        {{ ($data->currentpage()-1) * $data->perpage() + $key + 1 }}    
                    </td>                  
                    <td>{{$item->kode_organisasi_all_urusan}}</td>
                    <td>{{$item->OrgNm}}</td>
                    <td>
                        @php
                            if ($item->Jns==false)
                            {
                                echo $item->kode_program_all_urusan;
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
                                <a class="btnShow" href="{{route('mappingprogramtoopd.show',['id'=>$item->orgProgramID])}}" title="Detail Data Mapping Program">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>                            
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data Mapping Program" data-id="{{$item->orgProgramID}}" data-url="{{route('mappingprogramtoopd.index')}}">
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
