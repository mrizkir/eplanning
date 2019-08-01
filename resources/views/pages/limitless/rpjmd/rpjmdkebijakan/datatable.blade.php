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
                    <a href="{!!route('rpjmdkebijakan.create')!!}" class="btn btn-info btn-xs" title="Tambah RPJMD Misi">
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
                        KODE KEBIJAKAN  
                    </th> 
                    <th>
                        NAMA KEBIJAKAN  
                    </th> 
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
                    <td>{{$item->Kd_Kebijakan}}</td>
                    <td>{{$item->Nm_Kebijakan}}</td>
                    <td>{{$item->TA}}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('rpjmdkebijakan.show',['id'=>$item->PrioritasKebijakanKabID])}}" title="Detail Data RPJMD Kebijakan">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('rpjmdkebijakan.edit',['id'=>$item->PrioritasKebijakanKabID])}}" title="Ubah Data RPJMD Kebijakan">
                                    <i class='icon-pencil7'></i>
                                </a>  
                            </li>
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data RPJMD Kebijakan" data-id="{{$item->PrioritasKebijakanKabID}}" data-url="{{route('rpjmdkebijakan.index')}}">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr class="text-center info">
                    <td colspan="5">
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>PRIORITASKEBIJAKANKABID:</strong>
                            {{$item->PrioritasKebijakanKabID}}
                        </span> 
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>PRIORITASSTRATEGIKABID:</strong>
                            {{$item->PrioritasStrategiKabID}}
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
