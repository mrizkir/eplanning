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
            {!! Form::close()!!}
            <a href="{!!route('subkegiatan.create')!!}" class="btn btn-info btn-xs heading-btn" title="Tambah Kelompok Urusan">
                <i class="icon-googleplus5"></i>
            </a>
        </div>        
    </div>
    @if (count($data) > 0)
    <div class="table-responsive"> 
        <table id="data" class="table table-striped table-hover">
            <thead>
                <tr class="bg-teal-700">
                    <th width="55">NO</th>
                    <th width="100">
                        KODE SUB KEGIATAN  
                    </th> 
                    <th>                        
                        NAMA SUB KEGIATAN  
                    </th> 
                    <th width="350">
                        NAMA KEGIATAN  
                    </th> 
                    <th width="150">
                        JUMLAH RENJA
                    </th> 
                    <th width="150">
                        JUMLAH RKPD
                    </th> 
                    <th width="70">
                        TAHUN
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
                    <td>{{$item->kode_subkegiatan}}</td>
                    <td>{{$item->SubKgtNm}}</td>
                    <td>{{$item->KgtNm}}</td>
                    <td>{{DB::table('trRenja90')->where('SubKgtID',$item->KgtID)->count()}}</td>
                    <td>{{DB::table('trRKPD90')->where('SubKgtID',$item->KgtID)->count()}}</td>
                    <td>{{$item->TA}}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('subkegiatan.show',['uuid'=>$item->SubKgtID])}}" title="Detail Data Sub Kegiatan">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('subkegiatan.edit',['uuid'=>$item->SubKgtID])}}" title="Ubah Data Sub Kegiatan">
                                    <i class='icon-pencil7'></i>
                                </a>  
                            </li>
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data Sub Kegiatan" data-id="{{$item->SubKgtID}}" data-url="{{route('subkegiatan.index')}}">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr class="text-center info">
                    <td colspan="10">
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>SUBKGTID:</strong>
                            {{$item->SubKgtID}}
                        </span>
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>KGTID:</strong>
                            {{$item->KgtID}}
                        </span>                                                 
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>PRGNM:</strong>
                            {{$item->PrgNm}}
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
