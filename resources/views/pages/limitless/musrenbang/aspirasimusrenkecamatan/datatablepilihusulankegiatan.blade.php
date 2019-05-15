<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <div class="panel-title">
            <div class="row">
                <div class="col-md-1">                    		
                    {!!Form::select('numberRecordPerPage',['1'=>1,'5'=>5,'10'=>10,'15'=>15,'30'=>30,'50'=>50],$numberRecordPerPage,['id'=>'numberRecordPerPage','class'=>'form-control'])!!}                        
                </div>
            </div>
        </div>
        <div class="heading-elements">
            <div class="heading-btn">
                <a href="{!!route('aspirasimusrenkecamatan.create')!!}" class="btn btn-info btn-xs" title="Tambah Usulan Kegiatan">
                    <i class="icon-googleplus5"></i>
                </a>
                <a href="{!!route('aspirasimusrenkecamatan.index')!!}" class="btn btn-default btn-xs" title="keluar">
                    <i class="icon-close2"></i>
                </a>  
            </div>            
        </div>
    </div>
    @if (count($data) > 0)
    <div class="table-responsive"> 
        <table id="data" class="table table-striped table-hover">
            <thead>
                <tr class="bg-teal-700">
                    <th width="55">
                        <a class="column-sort2 text-white" id="col-No_usulan" data-order="{{$direction}}" href="#">
                            KODE  
                        </a>                                             
                    </th>                     
                    <th>
                        <a class="column-sort2 text-white" id="col-NamaKegiatan" data-order="{{$direction}}" href="#">
                            NAMA KEGIATAN  
                        </a>                                             
                    </th> 
                    <th width="200">                        
                        OUTPUT                        
                    </th> 
                    <th width="120">
                        <a class="column-sort2 text-white" id="col-NilaiUsulan" data-order="{{$direction}}" href="#">
                            NILAI
                        </a>                                             
                    </th> 
                    <th width="150">                        
                        VOLUME                        
                    </th> 
                    <th width="100">                 
                        <a class="column-sort2 text-white" id="col-Prioritas" data-order="{{$direction}}" href="#">
                            PRIORITAS                     
                        </a>
                    </th>                   
                    <th width="100">AKSI</th>
                </tr>
            </thead>
            <tbody>                    
            @foreach ($data as $key=>$item)
                <tr>                  
                    <td>{{$item->No_usulan}}</td>
                    <td>
                        {{$item->NamaKegiatan}}<br />
                        <span class="label label-flat border-primary text-primary-600">{{$item->Jeniskeg == 1 ? 'FISIK' : 'NON-FISIK'}}</span>
                    </td>
                    <td>{{$item->Output}}</td>
                    <td>{{Helper::formatUang($item->NilaiUsulan)}}</td>
                    <td>{{$item->Target_Angka}} {{$item->Target_Uraian}}</td>
                    <td>
                        <span class="label label-flat border-success text-success-600">
                            {{HelperKegiatan::getNamaPrioritas($item->Prioritas)}}
                        </span>                        
                    </td>
                    <td>
                        <div class="checkbox checkbox-switch">
                            {{Form::checkbox('UsulanDesaID[]',$item->UsulanDesaID,'',['class'=>'switch','data-on-text'=>'YA','data-off-text'=>'TIDAK'])}}                                     
                        </div>
                    </td>
                </tr>
                <tr class="text-center info">
                    <td colspan="10">
                        <span class="label label-warning label-rounded">
                            <strong>UsulanDesaID:</strong>
                            {{$item->UsulanDesaID}}
                        </span>
                        <span class="label label-warning label-rounded">
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