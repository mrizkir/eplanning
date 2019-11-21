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
                    <a href="{!!route('longlist.create')!!}" class="btn btn-info btn-xs" title="Tambah Long List">
                        <i class="icon-googleplus5"></i>
                    </a>
                </div> 
                <ul class="icons-list">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-printer"></i> 
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a href="{!!route('longlist.printtoexcel')!!}" title="Print to Excel" id="btnprintexcel">
                                    <i class="icon-file-excel"></i> Export to Excel
                                </a>     
                            </li>                            
                        </ul>
                    </li>
                </ul>
            {!! Form::close()!!}
        </div>       
    </div>
    @if (count($data) > 0)
    @php
        $roles=\Auth::user()->getRoleNames();
        $hidenilai_usulan=$roles[0]!='dewan';
    @endphp
    <div class="table-responsive"> 
        <table id="data" class="table table-striped table-hover">
            <thead>
                <tr class="bg-teal-700">
                    <th width="55">NO</th>                                                           
                    <th>
                        <a class="column-sort text-white" id="col-KgtNm" data-order="{{$direction}}" href="#">
                            NAMA KEGIATAN  
                        </a>                                             
                    </th>     
                    <th>
                        SASARAN
                    </th>     
                    <th>
                        LOKASI
                    </th> 
                    <th width="300">
                        OPD / SKPD
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
                    <td>{{$item->KgtNm}}</td>
                    <td>{{Helper::formatAngka($item->Sasaran_Angka)}} {{$item->Sasaran_Uraian}}</td>
                    <td>{{$item->Lokasi}}</td>
                    <td>{{$item->OrgNm}}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('longlist.show',['id'=>$item->LongListID])}}" title="Detail Data Long List">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            @if ($item->Privilege==0 || $role=='superadmin' || $role=='bapelitbang')
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('longlist.edit',['id'=>$item->LongListID])}}" title="Ubah Data Long List">
                                    <i class='icon-pencil7'></i>
                                </a>  
                            </li>
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data Long List" data-id="{{$item->LongListID}}" data-url="{{route('longlist.index')}}">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                            @endif 
                        </ul>
                    </td>
                </tr>
                <tr class="text-center info">
                    <td colspan="11">
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>LONGLISTID:</strong>
                            {{$item->LongListID}}
                        </span>                         
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>KET:</strong>
                            {{empty($item->Descr)?'-':$item->Descr}}
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
