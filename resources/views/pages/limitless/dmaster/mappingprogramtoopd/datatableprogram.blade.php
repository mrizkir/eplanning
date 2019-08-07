<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <div class="panel-title">
            <div class="row">
                <div class="col-md-1">                    		
                    {!!Form::select('numberRecordPerPageCreate',['1'=>1,'5'=>5,'10'=>10,'15'=>15,'30'=>30,'50'=>50],$numberRecordPerPage,['id'=>'numberRecordPerPageCreate','class'=>'form-control'])!!}                        
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
                    <th>
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
                    <th width="70">TA</th>
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
                    <td>{{$item->TA}}</td>
                    <td>
                        <div class="checkbox">
                            {{Form::checkbox("chkprgid[]", $item->PrgID,0,['class'=>'switch'])}}  
                        </div>
                    </td>
                </tr>
                <tr class="text-center info">
                    <td colspan="6">                     
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>PRGID:</strong>
                            {{$item->PrgID}}
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
    <div class="panel-body border-top-info text-center" id="paginationprogram">
        {{$data->links('layouts.limitless.l_pagination')}} 
    </div>
    @else
    <div class="panel-body">
        <div class="alert alert-info alert-styled-left alert-bordered">
            <span class="text-semibold">Info!</span>
            Belum ada data program yang bisa ditampilkan.
        </div>
    </div> 
    @endif 
</div>