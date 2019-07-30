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
                    <a href="{!!route('rpjmdsasaran.create')!!}" class="btn btn-info btn-xs" title="Tambah RPJMD TUJUAN">
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
                    <th width="120">
                        <a class="column-sort text-white" id="col-Kd_Sasaran" data-order="{{$direction}}" href="#">
                            KODE SASARAN 
                        </a>                                             
                    </th> 
                    <th>
                        <a class="column-sort text-white" id="col-Nm_Sasaran" data-order="{{$direction}}" href="#">
                            NAMA SASARAN  
                        </a>                                             
                    </th> 
                    <th width="100">JUMLAH INDIKATOR</th>
                    <th width="100">TA</th>
                    <th width="100">AKSI</th>
                </tr>
            </thead>
            <tbody>                    
            @foreach ($data as $key=>$item)
                <tr>
                    <td>
                        {{ ($data->currentpage()-1) * $data->perpage() + $key + 1 }}    
                    </td>                  
                    <td>{{$item->Kd_Sasaran}}</td>
                    <td>{{$item->Nm_Sasaran}}</td>
                    <td>{{DB::table('tmPrioritasIndikatorSasaran')->where('PrioritasSasaranKabID',$item->PrioritasSasaranKabID)->count()}}</td>
                    <td>{{$item->TA}}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('rpjmdsasaran.show',['id'=>$item->PrioritasSasaranKabID])}}" title="Detail Data RPJMD Sasaran">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('rpjmdsasaran.edit',['id'=>$item->PrioritasSasaranKabID])}}" title="Ubah Data RPJMD Sasaran">
                                    <i class='icon-pencil7'></i>
                                </a>  
                            </li>
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data RPJMD Sasaran" data-id="{{$item->PrioritasSasaranKabID}}" data-url="{{route('rpjmdsasaran.index')}}">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr class="text-center info">
                    <td colspan="6">
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>PRIORITASSASARANKABID:</strong>
                            {{$item->PrioritasSasaranKabID}}
                        </span>
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>PRIORITASTUJUANKABID:</strong>
                            {{$item->PrioritasTujuanKabID}}
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
