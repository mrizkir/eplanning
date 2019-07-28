<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <div class="panel-title">
            <h6 class="panel-title">&nbsp;</h6>
        </div>
        <div class="heading-elements">
            {!! Form::open(['url'=>'#','method'=>'post','class'=>'heading-form','id'=>'frmheading','name'=>'frmheading'])!!}                 
                <div class="form-group">
                    <a href="{!!route('rpjmdvisi.create')!!}" class="btn btn-info btn-xs" title="Tambah RPJMD Visi">
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
                        VISI 
                    </th> 
                    <th>                        
                        PERDA                          
                    </th> 
                    <th width="150">                        
                        TAHUN KONDISI AWAL RPJMD                       
                    </th>
                    <th width="70">                        
                        N1                       
                    </th>
                    <th width="70">                        
                        N2                       
                    </th>
                    <th width="70">                        
                        N3                       
                    </th>
                    <th width="70">                        
                        N4                       
                    </th>
                    <th width="70">                        
                        N5                       
                    </th>
                    <th width="150">                        
                        TAHUN KONDISI AKHIR RPJMD                        
                    </th>
                    <th width="100">AKSI</th>
                </tr>
            </thead>
            <tbody>                    
            @foreach ($data as $key=>$item)
                <tr>
                    <td>
                        {{ $key + 1 }}    
                    </td>                  
                    <td>{{$item->Nm_RpjmdVisi}}</td>                    
                    <td>{{$item->Descr}}</td>
                    <td>{{$item->TA_Awal}}</td>
                    <td>{{$item->TA_Awal+1}}</td>
                    <td>{{$item->TA_Awal+2}}</td>
                    <td>{{$item->TA_Awal+3}}</td>
                    <td>{{$item->TA_Awal+4}}</td>
                    <td>{{$item->TA_Awal+5}}</td>
                    <td>{{$item->TA_Awal+6}}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('rpjmdvisi.show',['id'=>$item->RpjmdVisiID])}}" title="Detail Data RPJMD Visi">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('rpjmdvisi.edit',['id'=>$item->RpjmdVisiID])}}" title="Ubah Data RPJMD Visi">
                                    <i class='icon-pencil7'></i>
                                </a>  
                            </li>
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data RPJMD Visi" data-id="{{$item->RpjmdVisiID}}" data-url="{{route('rpjmdvisi.index')}}">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr class="text-center info">
                    <td colspan="11">
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>RPJMDVISIID:</strong>
                            {{$item->RpjmdVisiID}}
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
    @else
    <div class="panel-body">
        <div class="alert alert-info alert-styled-left alert-bordered">
            <span class="text-semibold">Info!</span>
            Belum ada data yang bisa ditampilkan.
        </div>
    </div>   
    @endif            
</div>