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
        <table id="data" class="table table-striped table-hover" style="font-size:10px">
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
                        <a class="column-sort text-white" id="col-Jumlah1" data-order="{{$direction}}" href="#">
                            PRA RENJA  
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-Jumlah1" data-order="{{$direction}}" href="#">
                            RAKOR BIDANG  
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-Jumlah1" data-order="{{$direction}}" href="#">
                            FORUM OPD  
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-Jumlah1" data-order="{{$direction}}" href="#">
                            MUSREN. TK. KABUPATEN
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-Jumlah1" data-order="{{$direction}}" href="#">
                            VERIFIKASI TAPD
                        </a>                                             
                    </th>
                    <th width="150" class="text-right">
                        <a class="column-sort text-white" id="col-Jumlah1" data-order="{{$direction}}" href="#">
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
                    </th>
                    <th class="text-right">
                       <strong>{{Helper::formatUang($data->sum('prarenja1'))}}</strong>
                    </th>
                    <th class="text-right">
                        <strong>{{Helper::formatUang($data->sum('rakorbidang1'))}}</strong>
                    </th>
                    <th class="text-right">
                        <strong>{{Helper::formatUang($data->sum('forumopd1'))}}</strong>
                    </th>
                    <th class="text-right">
                        <strong>{{Helper::formatUang($data->sum('musrenkab1'))}}</strong>
                    </th>
                    <th class="text-right">
                        <strong>{{Helper::formatUang($data->sum('renjafinal1'))}}</strong>
                    </th>
                    <th class="text-right">
                        <strong>{{Helper::formatUang($data->sum('rkpd1'))}}</strong> 
                    </th>
                    <th class="text-right">
                        <strong>{{Helper::formatUang($data->sum('Jumlah1'))}}</strong>                        
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
                        {{Form::hidden($item->OrgID,$item->OrgID)}}  
                    </td>                  
                    <td class="text-left">{{$item->Kode_Organisasi}}</td>
                    <td class="text-left">
                        {{$item->OrgNm}}
                    </td>
                    <td>
                        {{Helper::formatUang($item->prarenja1)}}
                    </td>
                    <td>
                        {{Helper::formatUang($item->rakorbidang1)}}
                    </td>
                    <td>
                        {{Helper::formatUang($item->forumopd1)}}
                    </td>
                    <td>
                        {{Helper::formatUang($item->musrenkab1)}}
                    </td>
                    <td>
                        {{Helper::formatUang($item->renjafinal1)}}
                    </td>
                    <td>
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
