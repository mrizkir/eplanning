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
        <table id="data" class="table table-hover" style="font-size:10.5px">
            <thead>
                <tr class="bg-teal-700">
                    <th rowspan="2">
                        KONDISI KINERJA<br> AWAL RPJMD ({{HelperKegiatan::getRPJMDTahunAwal()}})
                    </th>
                    <th colspan="2">
                        TAHUN 1
                    </th>
                    <th colspan="2">
                        TAHUN 2
                    </th>
                    <th colspan="2">
                        TAHUN 3
                    </th>
                    <th colspan="2">
                        TAHUN 4
                    </th>
                    <th colspan="2">
                        TAHUN 5
                    </th>
                    <th colspan="2">KONDISI KINERJA <br>AKHIR RPJDM ({{HelperKegiatan::getRPJMDTahunAkhir()}})</th>
                    <th width="TA" rowspan="2">TA</th>
                    <th width="120" rowspan="2">AKSI</th>
                </tr>
                <tr class="bg-teal-700">
                    <th>Target</th>
                    <th>Rp</th>
                    <th width="50">Target</th>
                    <th>Rp</th>
                    <th width="50">Target</th>
                    <th>Rp</th>
                    <th width="50">Target</th>
                    <th>Rp</th>
                    <th width="50">Target</th>
                    <th>Rp</th>
                    <th width="50">Target</th>
                    <th>Rp</th>                        
                </tr>
            </thead>
            <tbody>                    
            @foreach ($data as $key=>$item)                
                <tr>
                    <td colspan="15">
                        <table width="100%">
                            <tr class="spaceunder">
                                <td width="120"><strong>KELOMPOK URUSAN:</strong></td>
                                <td>{{$item->Nm_Urusan}}  </td>
                            </tr>
                            <tr class="spaceunder">
                                <td><strong>URUSAN:</strong></td>
                                <td>{{$item->Nm_Bidang}}  </td>
                            </tr>
                            <tr class="text-info spaceunder">
                                <td><strong>OPD / SKPD:</strong></td>
                                <td>
                                    {{$item->OrgNm}}
                                </td>
                            </tr>
                            <tr class="text-primary spaceunder">
                                <td><strong>PROGRAM:</strong></td>
                                <td>
                                    {{$item->PrgNm}}
                                </td>
                            </tr>                            
                            <tr class="spaceunder">
                                <td><strong>NAMA INDIKATOR:</strong></td>
                                <td>{{$item->NamaIndikator}}  </td>
                            </tr>
                            <tr class="spaceunder">
                                <td><strong>SATUAN:</strong></td>
                                <td>{{$item->Satuan}}  </td>
                            </tr>
                        </table>           
                    </td>
                </tr>
                <tr>
                    <td>
                        {{$item->KondisiAwal}}
                    </td>                  
                    <td>{{Helper::formatAngka($item->TargetN1)}}</td>
                    <td>{{Helper::formatUang($item->PaguDanaN1)}}</td>
                    <td>{{Helper::formatAngka($item->TargetN2)}}</td>
                    <td>{{Helper::formatUang($item->PaguDanaN2)}}</td>
                    <td>{{Helper::formatAngka($item->TargetN3)}}</td>
                    <td>{{Helper::formatUang($item->PaguDanaN3)}}</td>
                    <td>{{Helper::formatAngka($item->TargetN4)}}</td>
                    <td>{{Helper::formatUang($item->PaguDanaN4)}}</td>
                    <td>{{Helper::formatAngka($item->TargetN5)}}</td>
                    <td>{{Helper::formatUang($item->PaguDanaN5)}}</td>
                    <td>{{Helper::formatAngka($item->KondisiAkhirTarget)}}</td>
                    <td>{{Helper::formatUang($item->KondisiAkhirPaguDana)}}</td>   
                    <td>
                        {{$item->TA}}
                    </td>       
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('rpjmdindikatorkinerja.show',['uuid'=>$item->IndikatorKinerjaID])}}" title="Detail Data Indikasi Rencana Program">
                                    <i class='icon-eye'></i>
                                </a>  
                            </li>
                            <li class="text-primary-600">
                                <a class="btnEdit" href="{{route('rpjmdindikatorkinerja.edit',['uuid'=>$item->IndikatorKinerjaID])}}" title="Ubah Data Indikasi Rencana Program">
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
                <tr class="text-center info">
                    <td colspan="15">
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>NO:</strong>
                            {{ ($data->currentpage()-1) * $data->perpage() + $key + 1 }}
                        </span>
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>INDIKATORKINERJAID:</strong>
                            {{$item->IndikatorKinerjaID}}
                        </span>
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>PRGID:</strong>
                            {{$item->PrgID}}
                        </span>
                        <span class="label label-warning label-rounded">
                            <strong>KET:</strong>
                            {{empty($item->Descr)?'-':$item->Descr}}
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