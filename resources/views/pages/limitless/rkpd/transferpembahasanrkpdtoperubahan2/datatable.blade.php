<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">
        <div class="panel-title">
            <div class="row">
                <div class="col-md-1">                    		
					{!!Form::select('numberRecordPerPage',['1'=>1,'5'=>5,'10'=>10,'15'=>15,'30'=>30,'50'=>50],$numberRecordPerPage,['id'=>'numberRecordPerPage','class'=>'form-control'])!!}                        
                </div>
            </div>
        </div>
    </div>
    @if (count($data) > 0)
    <div class="table-responsive"> 
        <table id="data" class="table table-striped table-hover">
            <thead>
                <tr class="bg-teal-700">
                    <th width="55">NO</th>
                    <th width="100">
                        <a class="column-sort text-white" id="col-kode_organisasi" data-order="{{$direction}}" href="#">
                            KODE SKPD / OPD
                        </a>                                             
                    </th> 
                    <th>
                        <a class="column-sort text-white" id="col-NmOrg" data-order="{{$direction}}" href="#">
                            NAMA SKPD / OPD
                        </a>                                             
                    </th> 
                    <th>
                        <a class="column-sort text-white" id="col-Nm_Urusan" data-order="{{$direction}}" href="#">
                            URUSAN
                        </a>                                             
                    </th>
                    <th width="100">JUMLAH RINCIAN PEMBAHASAN RKPD MURNI (ENTRLY LEVEL 2)</th>
                    <th width="100">JUMLAH RINCIAN RKPD PERUBAHAN (ENTRLY LEVEL 3)</th>
                    <th width="100">PAGU RINCIAN PEMBAHASAN RKPD MURNI (ENTRLY LEVEL 2)</th>
                    <th width="100">PAGU RINCIAN RKPD PERUBAHAN (ENTRLY LEVEL 3)</th>
                    <th width="70">TA</th>
                    <th width="100">AKSI</th>
                </tr>
            </thead>
            <tbody>                    
            @foreach ($data as $key=>$item)
                <tr>
                    <td>
                        {{ ($data->currentpage()-1) * $data->perpage() + $key + 1 }}    
                    </td>                  
                    <td>{{$item->kode_organisasi}}</td>
                    <td>{{$item->OrgNm}}</td>
                    <td>{{$item->Nm_Urusan}}</td>
                    <td>{{DB::table('v_rkpd_rinci')->where('OrgID',$item->OrgID)->where('EntryLvl',100)->count()}}</td>
                    <td>{{DB::table('v_rkpd_rinci')->where('OrgID',$item->OrgID)->where('EntryLvl',100)->count()}}</td>
                    <td>{{Helper::formatUang(DB::table('v_rkpd_rinci')->where('OrgID',$item->OrgID)->where('EntryLvl',100)->sum('NilaiUsulan3'))}}</td>
                    <td>{{Helper::formatUang(DB::table('v_rkpd_rinci')->where('OrgID',$item->OrgID)->where('EntryLvl',100)->sum('NilaiUsulan4'))}}</td>
                    <td>{{$item->TA}}</td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('transferpembahasanrkpdtoperubahan2.show',['id'=>$item->OrgID])}}" title="Detail Data Organisasi">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr class="text-center info">
                    <td colspan="10">
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>ORGID:</strong>
                            {{$item->OrgID}}
                        </span>                            
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>UrsID:</strong>
                            {{$item->UrsID}}
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
