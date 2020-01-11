<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <div class="panel-title">
            <h6 class="panel-title">&nbsp;</h6>
        </div>
        <div class="heading-elements">
            {!! Form::open(['action'=>'Report\RekapPaguRKPDOPDController@store','method'=>'post','class'=>'heading-form','id'=>'frmheading','name'=>'frmheading'])!!}   
                <div class="form-group">
                    {{ Form::button('<b><i class="icon-database-refresh"></i></b>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs heading-btn'] ) }}
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
                            <a href="{!!route('rekappagurkpdopd.printtoexcel')!!}" title="Print to Excel" id="btnprintexcel">
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
        <table id="data" class="table table-striped table-hover" style="font-size:12px">
            <thead>
                <tr class="bg-teal-700 text-right">
                    <th width="55">NO</th>
                    <th width="100" class="text-center">
                        <a class="column-sort text-white" id="col-kode_organisasi" data-order="{{$direction}}" href="#">
                            KODE OPD 
                        </a>                                             
                    </th>
                    <th class="text-left">
                        <a class="column-sort text-white" id="col-OrgNm" data-order="{{$direction}}" href="#">
                            NAMA OPD
                        </a>                                             
                    </th>                    
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-rkpd1" data-order="{{$direction}}" href="#">
                            RKPD MURNI
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-rkpd2" data-order="{{$direction}}" href="#">
                            RKPD PERUBAHAN
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-Jumlah1" data-order="{{$direction}}" href="#">
                            PAGU DANA MURNI
                        </a>                                             
                    </th>                    
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-Jumlah2" data-order="{{$direction}}" href="#">
                            PAGU DANA PERUBAHAN 
                        </a>                                             
                    </th>                    
                    <th width="150">
                        LAST UPDATE  
                    </th>
                </tr>
                <tr>
                    <th colspan="3" class="text-right">
                        <strong>AKSI</strong>
                    </th>
                    <th class="text-right">
                        <a href="#" id="uidRKPD1" class="reload">
                            <i class="icon-database-refresh"></i>
                        </a>
                    </th>
                    <th class="text-right">
                        <a href="#" id="uidRKPD2" class="reload">
                            <i class="icon-database-refresh"></i>
                        </a>
                    </th>
                    <th class="text-right" colspan="3">
                        &nbsp;
                    </th>                    
                </tr>
                <tr>
                    <th colspan="3" class="text-right">
                        <strong>TOTAL</strong>
                        @php
                            
                            $rkpd1=$data->sum('rkpd1');
                            $Jumlah1=$data->sum('Jumlah1');                           
                            $rkpd2=$data->sum('rkpd2');
                            $Jumlah2=$data->sum('Jumlah2');                           
                        @endphp
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($rkpd1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($rkpd1)}}<br>
                            ({{Helper::formatUang($Jumlah1-$rkpd1)}})
                        </strong> 
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($rkpd2,$Jumlah2)}}">
                        <strong>
                            {{Helper::formatUang($rkpd2)}}<br>
                            ({{Helper::formatUang($Jumlah2-$rkpd2)}})
                        </strong> 
                    </th>
                    <th class="text-right">
                        <strong>{{Helper::formatUang($Jumlah1)}}</strong>                        
                    </th>
                    <th class="text-right">
                        <strong>{{Helper::formatUang($Jumlah2)}}</strong>                        
                    </th>
                    
                </tr>
            </thead>
            <tbody>       
            {!! Form::open(['url'=>'#','method'=>'post','class'=>'heading-form','id'=>'frmdata','name'=>'frmdata'])!!}                       
            @foreach ($data as $key=>$item)               
                <tr class="text-right">
                    <td class="text-center">
                        {{ $key + 1 }}                          
                    </td>                  
                    <td class="text-left">{{$item->Kode_Organisasi}}</td>
                    <td class="text-left">
                        {{$item->OrgNm}}
                    </td>
                    <td class="{{HelperKegiatan::setStyleForRekapMode1($item->rkpd1,$item->Jumlah1)}}">
                        {{Helper::formatUang($item->rkpd1)}}
                    </td>
                    <td class="{{HelperKegiatan::setStyleForRekapMode1($item->rkpd2,$item->Jumlah2)}}">
                        {{Helper::formatUang($item->rkpd2)}}
                    </td>
                    <td>
                        {{Helper::formatUang($item->Jumlah1)}}
                    </td>
                    <td>
                        {{Helper::formatUang($item->Jumlah2)}}
                    </td>
                    <td class="text-center">
                        {{Helper::tanggal('d/m/Y H:m',$item->updated_at)}}    
                    </td>
                </tr>
            @endforeach                    
            {!! Form::close()!!}  
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">
                        <strong>TOTAL</strong>                        
                    </td>
                    <td class="text-right {{HelperKegiatan::setStyleForRekapMode1($rkpd1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($rkpd1)}}<br>
                            ({{Helper::formatUang($Jumlah1-$rkpd1)}})
                        </strong> 
                    </td>
                    <td class="text-right {{HelperKegiatan::setStyleForRekapMode1($rkpd1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($rkpd2)}}<br>
                            ({{Helper::formatUang($Jumlah2-$rkpd2)}})
                        </strong> 
                    </td>
                    <td class="text-right">
                        <strong>{{Helper::formatUang($Jumlah1)}}</strong>                        
                    </td>
                    <td class="text-right">
                        <strong>{{Helper::formatUang($Jumlah2)}}</strong>                        
                    </td>
                    <td class="text-right">
                        &nbsp;
                    </td>
                </tr>
            </tfoot>
        </table>               
    </div>
    @else
    <div class="panel-body">
        <div class="alert alert-info alert-styled-left alert-bordered">
            <span class="text-semibold">Info!</span>
            Belum ada data yang bisa ditampilkan, silahkan klik tombol inisialisasi data
        </div>
    </div>   
    @endif            
</div>
