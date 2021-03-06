<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <div class="panel-title">
            <h6 class="panel-title">&nbsp;</h6>
        </div>
        <div class="heading-elements">
            {!! Form::open(['url'=>'#','method'=>'post','class'=>'heading-form','id'=>'frmheading','name'=>'frmheading'])!!}                  
                <div class="form-group">
                    <select id="filterurusan" class="select">
                        <option></option>
                        @foreach ($daftar_urusan as $k=>$item)
                            <option value="{{$k}}"{{$k==$filter_ursid_selected?'selected':''}}>{{$item}}</option>
                        @endforeach
                    </select> 
                </div>
                <div class="form-group">
                    {!!Form::select('numberRecordPerPage',['1'=>1,'5'=>5,'10'=>10,'15'=>15,'30'=>30,'50'=>50],$numberRecordPerPage,['id'=>'numberRecordPerPage','class'=>'form-control','style'=>'width:70px'])!!}                        
                </div>
                <div class="form-group">
                    <a href="{!!route('program.create')!!}" class="btn btn-info btn-xs" title="Tambah PROGRAM">
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
                    <th width="100">JUMLAH KEGIATAN</th>
                    <th width="100">                       
                        RPJMD MULAI TAHUN                         
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
                    <td>{{$item->kode_program}}</td>
                    <td>{{$item->PrgNm}}</td>
                    <td>
                        @php
                            if (!$item->Jns)
                            {
                                echo "SELURUH URUSAN";
                            } 
                            else {
                                echo $item->Nm_Bidang;
                            }   
                        @endphp
                    </td>
                    <td>{{DB::table('tmKgt')->where('PrgID',$item->PrgID)->count()}}</td>
                    <td>{{$item->TA}}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('program.show',['uuid'=>$item->PrgID])}}" title="Detail Data Program">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('program.edit',['uuid'=>$item->PrgID])}}" title="Ubah Data Program">
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
                <tr class="text-center info">
                    <td colspan="10">
                         <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>PRGID:</strong>
                            {{$item->PrgID}}
                        </span>  
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>URSID:</strong>
                            {{$item->UrsID}}
                        </span>                       
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>KELOMPOK:</strong>
                            {{$item->Nm_Urusan}}
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
