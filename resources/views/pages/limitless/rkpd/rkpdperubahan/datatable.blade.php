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
                <ul class="icons-list">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-menu7"></i> 
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a href="{!!route('rkpdperubahan.printtoexcel')!!}" title="Print to Excel" id="btnprintexcel">
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
                        <th width="50">NO</th>     
                        <th width="150">
                            <a class="column-sort text-white" id="col-kode_kegiatan" data-order="{{$direction}}" href="#">
                                KODE KEGIATAN                                                                       
                            </a>
                        </th>                
                        <th width="400">
                            <a class="column-sort text-white" id="col-KgtNm" data-order="{{$direction}}" href="#">
                                NAMA KEGIATAN                                                                       
                            </a>
                        </th> 
                        <th width="300">
                            <a class="column-sort text-white" id="col-NamaIndikator" data-order="{{$direction}}" href="#">
                                INDIKATOR                                                                       
                            </a>
                        </th>
                        <th width="200">                            
                            SASARAN                                                                           
                        </th> 
                        <th width="120">                        
                            TARGET (%)                        
                        </th> 
                        <th width="150" class="text-right">
                            <a class="column-sort text-white" id="col-NilaiUsulan1" data-order="{{$direction}}" href="#">
                                PAGU INDIKATIF  
                            </a>                                             
                        </th>        
                        <th width="150">STATUS</th>                            
                        <th width="150">AKSI</th>
                    </tr>
                </thead>
                <tbody>                    
                @foreach ($data as $key=>$item)
                    <tr>
                        <td>
                            {{ ($data->currentpage()-1) * $data->perpage() + $key + 1 }}    
                        </td>
                        <td>
                            {{$item->kode_kegiatan}}                        
                        </td>
                        <td>
                            {{ucwords($item->KgtNm)}}                            
                        </td>                        
                        <td>
                            {{ucwords($item->NamaIndikator)}}                           
                        </td>
                        <td>{{Helper::formatAngka($item->Sasaran_Angka1)}} {{$item->Sasaran_Uraian1}}</td>
                        <td>{{$item->Target1}}</td>
                        <td class="text-right">{{Helper::formatuang($item->NilaiUsulan1)}}</td>                        
                        <td>
                            @include('layouts.limitless.l_status_kegiatan')                        
                        </td>
                        <td>
                            <ul class="icons-list">              
                                <li class="dropdown text-teal-600">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-cog7"></i>
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right" style="font-size:8px">
                                        @if ($item->Privilege==1)
                                        <li class="dropdown-header">Ubah Status</li>
                                        @if ($item->Status==0)
                                        <li class="active">
                                            <a href="#">                                        
                                                <i class="icon-checkmark"></i>                                        
                                                DRAFT
                                            </a>
                                        </li>
                                        @else
                                        <li>
                                            <a href="#" class="ubahstatus" data-status="0" data-id="{{$item->RKPDID}}">                                        
                                                <i class="icon-cross"></i>                                        
                                                DRAFT
                                            </a>
                                        </li>
                                        @endif    
                                
                                        @if ($item->Status==1)
                                        <li class="active">
                                            <a href="#">                                        
                                                <i class="icon-checkmark"></i>                                        
                                                SETUJU
                                            </a>
                                        </li>
                                        @else
                                        <li>
                                            <a href="#" class="ubahstatus" data-status="1" data-id="{{$item->RKPDID}}">                                        
                                                <i class="icon-cross"></i>                                        
                                                SETUJU
                                            </a>
                                        </li>
                                        @endif
                                
                                        @if ($item->Status==2)
                                        <li class="active">
                                            <a href="#">                                        
                                                <i class="icon-checkmark"></i>                                        
                                                SETUJU CATATAN
                                            </a>
                                        </li>
                                        @else
                                        <li>
                                            <a href="#" class="ubahstatus" data-status="2" data-id="{{$item->RKPDID}}">                                        
                                                <i class="icon-cross"></i>                                        
                                                SETUJU CATATAN
                                            </a>
                                        </li>
                                        @endif
                                
                                        @if ($item->Status==3)
                                        <li class="active">
                                            <a href="#">                                        
                                                <i class="icon-checkmark"></i>                                        
                                                PENDING
                                            </a>
                                        </li>
                                        @else
                                        <li>
                                            <a href="#" class="ubahstatus" data-status="3" data-id="{{$item->RKPDID}}">                                        
                                                <i class="icon-cross"></i>                                        
                                                PENDING
                                            </a>
                                        </li>
                                        @endif                                         
                                        <li class="dropdown-header">AKSI</li>        
                                        @if ($item->Privilege==1)              
                                        <li class="text-primary-600">        
                                            <a class="btnEdit" href="{{route('rkpdperubahan.edit',['id'=>$item->RKPDID])}}" title="Ubah Data Kegiatan">
                                                <i class='icon-pencil7'></i> UBAH RINCIAN
                                            </a>             
                                        </li> 
                                        @endif        
                                        <li>                                    
                                            @if ($item->Status==1 || $item->Status==2)
                                                <a href="#" title="TRANSFER KEG. KE PERUBAHAN " id="btnTransfer" data-id="{{$item->RKPDID}}" title="PERUBAHAN">
                                                    <i class="icon-play4"></i> TRANSFER KE PERUBAHAN
                                                </a>
                                            @else
                                                <a href="#" onclick="event.preventDefault()" title="PERUBAHAN">
                                                    <i class="icon-stop"></i> TRANSFER KE PERUBAHAN
                                                </a>
                                            @endif
                                        </li>             
                                        @endif 
                                        <li class="text-primary-600">        
                                            <a class="btnEdit" href="{{route('rkpdperubahan.show',['id'=>$item->RKPDID])}}" title="Detail Data Kegiatan">
                                                <i class='icon-eye'></i> DETAIL RINCIAN
                                            </a>             
                                        </li> 
                                        <li>
                                            <a href="#"  title="PERUBAHAN" class="btnHistoriRenja" data-url="{{route('historirenja.onlypagu',['uuid'=>$item->RKPDID])}}" >                                        
                                                <i class="icon-history"></i>                                        
                                                HISTORY
                                            </a>
                                        </li>                              
                                    </ul>
                                </li>
                            </ul>
                        </td>              
                    </tr>
                    <tr class="text-center info">
                        <td colspan="10">
                            <span class="label label-warning label-rounded" style="text-transform: none">
                                <strong>RKPDID:</strong>
                                {{$item->RKPDID}}
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
                Belum ada data yang bisa ditampilkan. Mohon pilih terlebih dahulu OPD dan Unit Kerja
            </div>
        </div>   
        @endif            
    </div>