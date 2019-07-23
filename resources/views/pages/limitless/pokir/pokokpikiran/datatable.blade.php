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
                    <a href="{!!route('pokokpikiran.create')!!}" class="btn btn-info btn-xs" title="Tambah Pokok Pikiran">
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
                                <a href="{!!route('pokokpikiran.printtoexcel')!!}" title="Print to Excel" id="btnprintexcel">
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
                        <a class="column-sort text-white" width="250" id="col-OrgNm" data-order="{{$direction}}" href="#">
                            NAMA OPD  
                        </a>                                             
                    </th> 
                    <th>
                        <a class="column-sort text-white" id="col-NmPk" data-order="{{$direction}}" href="#">
                            PEMILIK  
                        </a>                                             
                    </th> 
                    <th>
                        <a class="column-sort text-white" id="col-NamaUsulanKegiatan" data-order="{{$direction}}" href="#">
                            NAMA KEGIATAN  
                        </a>                                             
                    </th> 
                    @if ($hidenilai_usulan)
                    <th>
                        <a class="column-sort text-white" id="col-NilaiUsulan" data-order="{{$direction}}" href="#">
                            NILAI USULAN  
                        </a>                                             
                    </th>
                    @endif                                        
                    <th width="200">
                        <a class="column-sort text-white" id="col-Lokasi" data-order="{{$direction}}" href="#">
                            LOKASI  
                        </a>                                             
                    </th> 
                    <th width="100">
                        <a class="column-sort text-white" id="col-Prioritas" data-order="{{$direction}}" href="#">
                            PRIO.  
                        </a>                                             
                    </th> 
                    <th width="70">
                        <a class="column-sort text-white" id="col-Prioritas" data-order="{{$direction}}" href="#">
                            VER.  
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
                    <td>{{$item->OrgNm}}</td>
                    <td>{{$item->NmPk}}</td>
                    <td>{{$item->NamaUsulanKegiatan}}</td>
                    @if ($hidenilai_usulan)
                    <td>{{Helper::formatUang($item->NilaiUsulan)}}</td>
                    @endif
                    <td>{{$item->Lokasi}}</td>                                        
                    <td>
                        <span class="label label-flat border-pink text-pink-600">
                            {{HelperKegiatan::getNamaPrioritas($item->Prioritas)}}
                        </span>
                    </td> 
                    <td>                    
                        @if ($item->Privilege==0)
                        <span class="label label-flat border-grey text-grey-600 label-icon">
                            <i class="icon-cross2"></i>
                        </span>
                        @else
                            <span class="label label-flat border-success text-success-600 label-icon">
                                <i class="icon-checkmark"></i>
                            </span>                            
                        @endif                    
                    </td>              
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('pokokpikiran.show',['id'=>$item->PokPirID])}}" title="Detail Data Pokok Pikiran">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('pokokpikiran.edit',['id'=>$item->PokPirID])}}" title="Ubah Data Pokok Pikiran">
                                    <i class='icon-pencil7'></i>
                                </a>  
                            </li>
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data Pokok Pikiran" data-id="{{$item->PokPirID}}" data-url="{{route('pokokpikiran.index')}}">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr class="text-center info">
                    <td colspan="11">
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>POKPIRID:</strong>
                            {{$item->PokPirID}}
                        </span> 
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>KECAMATAN:</strong>
                            {{$item->Nm_Kecamatan}}
                        </span>                        
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>DESA:</strong>
                            {{$item->Nm_Desa}}
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
