<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <div class="panel-title">
            <h6 class="panel-title">&nbsp;</h6>
        </div>
        <div class="heading-elements">
            {!! Form::open(['action'=>'Report\RekapPaguIndikatifOPDController@store','method'=>'post','class'=>'heading-form','id'=>'frmheading','name'=>'frmheading'])!!}   
                <div class="form-group">
                    {{ Form::button('<b><i class="icon-database-refresh"></i></b>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs heading-btn'] ) }}
                </div>   
            {!! Form::close()!!}           
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
                    <th width="400" class="text-left">
                        <a class="column-sort text-white" id="col-OrgNm" data-order="{{$direction}}" href="#">
                            NAMA OPD
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-prarenja1" data-order="{{$direction}}" href="#">
                            PRA RENJA  
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-rakorbidang1" data-order="{{$direction}}" href="#">
                            RAKOR BIDANG  
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-forumopd1" data-order="{{$direction}}" href="#">
                            FORUM OPD  
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-musrenkab1" data-order="{{$direction}}" href="#">
                            MUSREN. TK. KABUPATEN
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-renjafinal1" data-order="{{$direction}}" href="#">
                            VERIFIKASI TAPD
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-rkpd1" data-order="{{$direction}}" href="#">
                            RKPD
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-Jumlah1" data-order="{{$direction}}" href="#">
                            PAGU DANA  
                        </a>                                             
                    </th>                    
                    <th width="400">
                        <a class="column-sort text-white" id="col-updated_at" data-order="{{$direction}}" href="#">
                            LAST UPDATE  
                        </a>                                             
                    </th>
                </tr>
                <tr>
                    <th colspan="3" class="text-right">
                        <strong>AKSI</strong>
                    </th>
                    <th class="text-right">
                        <a href="#" id="uidPrarenja" class="reload">
                            <i class="icon-database-refresh"></i>
                        </a>
                    </th>
                    <th class="text-right">
                        <a href="#" id="uidRakorBidang" class="reload">
                            <i class="icon-database-refresh"></i>
                        </a>
                    </th>
                    <th class="text-right">
                        <a href="#" id="uidForumOPD" class="reload">
                            <i class="icon-database-refresh"></i>
                        </a>
                    </th>
                    <th class="text-right">
                        <a href="#" id="uidMusrenKab" class="reload">
                            <i class="icon-database-refresh"></i>
                        </a>
                    </th>
                    <th class="text-right">
                        <a href="#" id="uidRenjaFinal" class="reload">
                            <i class="icon-database-refresh"></i>
                        </a>
                    </th>
                    <th class="text-right">
                        <a href="#" id="uidRKPD" class="reload">
                            <i class="icon-database-refresh"></i>
                        </a>
                    </th>
                    <th class="text-right" colspan="2">
                        &nbsp;
                    </th>                    
                </tr>
                <tr>
                    <th colspan="3" class="text-right">
                        <strong>TOTAL</strong>
                        @php
                            $prarenja1=$data->sum('prarenja1');
                            $rakorbidang1=$data->sum('rakorbidang1');
                            $forumopd1=$data->sum('forumopd1');
                            $musrenkab1=$data->sum('musrenkab1');
                            $renjafinal1=$data->sum('renjafinal1');
                            $rkpd1=$data->sum('rkpd1');
                            $Jumlah1=$data->sum('Jumlah1');                           
                        @endphp
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($prarenja1,$Jumlah1)}}">
                       <strong>
                           {{Helper::formatUang($prarenja1)}} <br>
                            ({{Helper::formatUang($Jumlah1-$prarenja1)}})
                    </strong>
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($rakorbidang1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($rakorbidang1)}}<br>
                            ({{Helper::formatUang($Jumlah1-$rakorbidang1)}})
                        </strong>
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($forumopd1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($forumopd1)}}<br>
                            ({{Helper::formatUang($Jumlah1-$forumopd1)}})
                        </strong>
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($musrenkab1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($musrenkab1)}}<br>
                            ({{Helper::formatUang($Jumlah1-$musrenkab1)}})
                        </strong>
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($renjafinal1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($renjafinal1)}}<br>
                            ({{Helper::formatUang($Jumlah1-$renjafinal1)}})
                        </strong>
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($rkpd1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($rkpd1)}}<br>
                            ({{Helper::formatUang($Jumlah1-$rkpd1)}})
                        </strong> 
                    </th>
                    <th class="text-right">
                        <strong>{{Helper::formatUang($Jumlah1)}}</strong>                        
                    </th>
                    <th class="text-right">
                        &nbsp;
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
                    <td class="{{HelperKegiatan::setStyleForRekapMode1($item->prarenja1,$item->Jumlah1)}}">
                        {{Helper::formatUang($item->prarenja1)}}
                    </td>
                    <td class="{{HelperKegiatan::setStyleForRekapMode1($item->rakorbidang1,$item->Jumlah1)}}">
                        {{Helper::formatUang($item->rakorbidang1)}}
                    </td>
                    <td class="{{HelperKegiatan::setStyleForRekapMode1($item->forumopd1,$item->Jumlah1)}}">
                        {{Helper::formatUang($item->forumopd1)}}
                    </td>
                    <td class="{{HelperKegiatan::setStyleForRekapMode1($item->musrenkab1,$item->Jumlah1)}}">
                        {{Helper::formatUang($item->musrenkab1)}}
                    </td>
                    <td class="{{HelperKegiatan::setStyleForRekapMode1($item->renjafinal1,$item->Jumlah1)}}">
                        {{Helper::formatUang($item->renjafinal1)}}
                    </td>
                    <td class="{{HelperKegiatan::setStyleForRekapMode1($item->rkpd1,$item->Jumlah1)}}">
                        {{Helper::formatUang($item->rkpd1)}}
                    </td>
                    <td>
                        {{Helper::formatUang($item->Jumlah1)}}
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
                    <th colspan="3" class="text-right">
                        <strong>TOTAL</strong>                        
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($prarenja1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($prarenja1)}} <br>
                            ({{Helper::formatUang($Jumlah1-$prarenja1)}})
                    </strong>
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($rakorbidang1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($rakorbidang1)}}<br>
                            ({{Helper::formatUang($Jumlah1-$rakorbidang1)}})
                        </strong>
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($forumopd1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($forumopd1)}}<br>
                            ({{Helper::formatUang($Jumlah1-$forumopd1)}})
                        </strong>
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($musrenkab1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($musrenkab1)}}<br>
                            ({{Helper::formatUang($Jumlah1-$musrenkab1)}})
                        </strong>
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($renjafinal1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($renjafinal1)}}<br>
                            ({{Helper::formatUang($Jumlah1-$renjafinal1)}})
                        </strong>
                    </th>
                    <th class="text-right {{HelperKegiatan::setStyleForRekapMode1($rkpd1,$Jumlah1)}}">
                        <strong>
                            {{Helper::formatUang($rkpd1)}}<br>
                            ({{Helper::formatUang($Jumlah1-$rkpd1)}})
                        </strong> 
                    </th>
                    <th class="text-right">
                        <strong>{{Helper::formatUang($Jumlah1)}}</strong>                        
                    </th>
                    <th class="text-right">
                        &nbsp;
                    </th>
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
