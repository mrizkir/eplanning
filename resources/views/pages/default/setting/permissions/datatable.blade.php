<div class="box box-green">
    <div class="box-header with-border">
        <div class="box-tools-left">
            {!!Form::select('numberRecordPerPage',['1'=>1,'5'=>5,'10'=>10,'15'=>15,'30'=>30,'50'=>50],$numberRecordPerPage,['id'=>'numberRecordPerPage','class'=>'form-control'])!!}            
        </div>
        <div class="box-tools">            
            <a href="{!!route('permissions.create')!!}" class="btn btn-primary">
                <i class="fa fa-plus-circle"></i>
            </a>
        </div>
    </div>
    @if (count($data) > 0)
    <div class="box-body table-responsive no-padding">                
        <table id="data" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th width="55">NO</th>
                    <th width="100">
                        <a class="column-sort" id="col-id" data-order="{{$direction}}" href="#">
                            ID  
                        </a>                                             
                    </th> 
                    <th>
                        <a class="column-sort" id="col-name" data-order="{{$direction}}" href="#">
                            NAME
                    </th>   
                    <th width="150">
                        <a class="column-sort" id="col-guard" data-order="{{$direction}}" href="#">
                            GUARD                            
                        </a>                        
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
                    <td>{{$item->id}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->guard_name}}</td>
                    <td class="text-nowrap">                        
                        <a class="btn btnShow" href="{{route('permissions.show',['uuid'=>$item->id])}}" title="Detail Data Permission">
                            <i class='fa fa-eye text-blue'></i>
                        </a>      
                        <a class="btn btnDelete" href="javascript:;" title="Hapus Data Permission" data-id="{{$item->id}}" data-url="{{route('permissions.index')}}">
                            <i class='fa fa-trash text-red'></i>
                        </a>
                    </td>
                </tr>
            @endforeach                    
            </tbody>
        </table>               
    </div>
    <div class="box-footer">
        {{$data->links('layouts.default.l_pagination')}}               
    </div>
    @else
    <div class="box-body">
        <div class="alert alert-info">
            <h4>
                <i class="icon fa fa-info"></i>
                Info!
            </h4>            
            Belum ada data yang bisa ditampilkan.
        </div>   
    </div>
    @endif            
</div>
