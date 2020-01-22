<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <div class="panel-title">
            <h6 class="panel-title">&nbsp;</h6>
        </div>
        <div class="heading-elements">
            {!! Form::open(['url'=>'#','method'=>'post','class'=>'heading-form','id'=>'frmheading','name'=>'frmheading'])!!} 
                <div class="form-group">
                    {!!Form::select('numberRecordPerPage',['1'=>1,'5'=>5,'10'=>10,'15'=>15,'30'=>30,'50'=>50],$numberRecordPerPage,['id'=>'numberRecordPerPage','class'=>'form-control','style'=>'width:70px'])!!}                        
                </div> 
                <div class="form-group">
                    <a href="{!!route('mappingprogramtoopd.create')!!}" class="btn btn-info btn-xs" title="Tambah Mapping Program">
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
                    <th width="55">NO</th>
                    <th width="150">
                        KODE SKPD / OPD
                    </th> 
                    <th width="250">
                        NAMA SKPD / OPD
                    </th> 
                    <th width="120">
                        KODE PROGRAM  
                    </th>
                    <th>
                        NAMA PROGRAM  
                    </th> 
                    <th>
                        URUSAN  
                    </th>
                    <th width="70">TA</th>
                    <th width="80">AKSI</th>
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
                    <td>
                        @php
                            if ($item->Jns==false)
                            {
                                echo $item->kode_program;
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
                <tr class="text-center info">
                    <td colspan="10">
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>ORGIDRPJMD:</strong>
                            {{$item->OrgIDRPJMD}}
                        </span>                            
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
