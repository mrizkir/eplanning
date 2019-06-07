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
                    <a href="{!!route('rpjmdindikatorkinerja.create')!!}" class="btn btn-info btn-xs" title="Tambah RPJMD Misi">
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
                    <th width="400">
                        <a class="column-sort text-white" id="col-NamaIndikator" data-order="{{$direction}}" href="#">
                            NAMA INDIKATOR  
                        </a>                                             
                    </th> 
                    <th width="150">                        
                        AWAL ({{config('eplanning.rpjmd_tahun_mulai')-1}})                
                    </th>
                    <th>
                        DATA INDIKATOR
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
                    <td>{{$item->NamaIndikator}}</td>
                    <td>{{$item->TargetAwal}}</td>
                    <td>
                        <table width="100%">
                            <tr>
                                <td width="100">
                                    TARGET {{$item->TA_N}}
                                </td>
                                <td width="120">: {{$item->TargetN1}}</td>
                                <td width="120">
                                    PAGU DANA {{$item->TA_N}}
                                </td>
                                <td width="120">: {{Helper::formatUang($item->PaguDanaN1)}}</td>
                            </tr>                            
                            <tr>
                                <td width="100">
                                    TARGET {{$item->TA_N+1}}
                                </td>
                                <td width="120">: {{$item->TargetN2}}</td>
                                <td width="120">
                                    PAGU DANA {{$item->TA_N+1}}
                                </td>
                                <td width="120">: {{Helper::formatUang($item->PaguDanaN1)}}</td>
                            </tr>
                            <tr>
                                <td width="100">
                                    TARGET {{$item->TA_N+2}}
                                </td>
                                <td width="120">: {{$item->TargetN2}}</td>
                                <td width="120">
                                    PAGU DANA {{$item->TA_N+2}}
                                </td>
                                <td width="120">: {{Helper::formatUang($item->PaguDanaN2)}}</td>
                            </tr>
                            <tr>
                                <td width="100">
                                    TARGET {{$item->TA_N+3}}
                                </td>
                                <td width="120">: {{$item->TargetN3}}</td>
                                <td width="120">
                                    PAGU DANA {{$item->TA_N+3}}
                                </td>
                                <td width="120">: {{Helper::formatUang($item->PaguDanaN3)}}</td>
                            </tr>
                            <tr>
                                <td width="100">
                                    TARGET {{$item->TA_N+4}}
                                </td>
                                <td width="120">: {{$item->TargetN4}}</td>
                                <td width="120">
                                    PAGU DANA {{$item->TA_N+4}}
                                </td>
                                <td width="120">: {{Helper::formatUang($item->PaguDanaN4)}}</td>
                            </tr>
                            <tr>
                                <td width="100">
                                    TARGET {{$item->TA_N+5}}
                                </td>
                                <td width="120">: {{$item->TargetN5}}</td>
                                <td width="120">
                                    PAGU DANA {{$item->TA_N+5}}
                                </td>
                                <td width="120">: {{Helper::formatUang($item->PaguDanaN5)}}</td>
                            </tr>
                        </table>
                    </td>
                    
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('rpjmdindikatorkinerja.show',['id'=>$item->IndikatorKinerjaID])}}" title="Detail Data Indikasi Rencana Program">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('rpjmdindikatorkinerja.edit',['id'=>$item->IndikatorKinerjaID])}}" title="Ubah Data Indikasi Rencana Program">
                                    <i class='icon-pencil7'></i>
                                </a>  
                            </li>
                            <li class="text-danger-600">
                                <a class="btnDelete" href="javascript:;" title="Hapus Data Indikasi Rencana Program" data-id="{{$item->IndikatorKinerjaID}}" data-url="{{route('rpjmdindikatorkinerja.index')}}">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                        </ul>
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
