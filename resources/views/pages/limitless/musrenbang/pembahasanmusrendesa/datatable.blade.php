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
            <ul class="icons-list">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-printer"></i> 
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a href="{!!route('pembahasanmusrenkecamatan.printtoexcel')!!}" title="Print to Excel" id="btnprintexcel">
                                <i class="icon-file-excel"></i> Export to Excel
                            </a>     
                        </li>                            
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    @if (count($data) > 0)
    <div class="table-responsive"> 
        <table id="data" class="table table-striped table-hover">
            <thead>
                <tr class="bg-teal-700">
                    <th width="55">
                        <a class="column-sort text-white" id="col-No_usulan" data-order="{{$direction}}" href="#">
                            KODE  
                        </a>                                             
                    </th>                     
                    <th>
                        <a class="column-sort text-white" id="col-NamaKegiatan" data-order="{{$direction}}" href="#">
                            NAMA KEGIATAN  
                        </a>                                             
                    </th> 
                    <th width="250">                        
                        OUTPUT                        
                    </th> 
                    <th width="120">
                        <a class="column-sort text-white" id="col-NilaiUsulan" data-order="{{$direction}}" href="#">
                            NILAI
                        </a>                                             
                    </th> 
                    <th width="150">                        
                        VOLUME                        
                    </th> 
                    <th width="100">                        
                        PRIORITAS                     
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
                        @if (empty($item->UsulanKecID))
                            <div class="checkbox checkbox-switch">
                                {{Form::checkbox('Privilege[]',$item->UsulanDesaID,$item->Privilege==1?$item->Privilege:'',['class'=>'switch','data-on-text'=>'ACC','data-off-text'=>'DUM'])}}                                     
                            </div>
                        @else
                            <span class="label label-success label-flat border-success text-success-600">
                                TRANSFERED
                            </span>
                        @endif                        
                    </td>
                </tr>
                <tr class="text-center info">
                    <td colspan="10">
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>USULANDESAID:</strong>
                            {{$item->UsulanDesaID}}
                        </span>
                        @if (!empty($item->UsulanKecID))
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>USULANKECID:</strong>
                            {{$item->UsulanKecID}}
                        </span>
                        @endif
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