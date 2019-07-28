<div class="panel-heading">
    <div class="panel-title">
        <h5>DAFTAR INDIKATOR SASARAN</h5>
    </div>
    <div class="heading-elements">
        
    </div>
</div>
@if (count($dataindikatorsasaran) > 0)
<div class="table-responsive"> 
    <table id="data" class="table table-striped table-hover" style="font-size:11px">
        <thead>
            <tr class="bg-teal-700">
                <th width="55">NO</th>     
                <th>NAMA INDIKATOR</th>                
                <th width="100">SATUAN</th>
                <th width="150">
                    KONDISI KINERJA AWAL
                    ({{config('eplanning.rpjmd_tahun_mulai')-1}})
                </th>   
                <th>N1</th>                     
                <th>N2</th>                     
                <th>N3</th>                     
                <th>N4</th>                     
                <th>N5</th>                     
                <th width="150">
                    KONDISI AKHIR RPJMD 
                    ({{config('eplanning.rpjmd_tahun_akhir')+1}})
                </th> 
                <th width="100">AKSI</th>
            </tr>
        </thead>
        <tbody>                    
        @foreach ($dataindikatorsasaran as $key=>$item)
            <tr>
                <td>
                    {{ $key + 1 }}    
                </td>
                <td>{{$item->NamaIndikator}}</td>
                <td>{{$item->Satuan}}</td>
                <td>{{$item->KondisiAwal}}</td>                        
                <td>{{$item->N1}}</td>                        
                <td>{{$item->N2}}</td>                        
                <td>{{$item->N3}}</td>                        
                <td>{{$item->N4}}</td>                        
                <td>{{$item->N5}}</td>                        
                <td>{{$item->Operator}}{{$item->KondisiAkhir}}</td>                                    
                <td>                    
                    <ul class="icons-list">
                        <li class="text-primary-600">                            
                            <a class="btnEdit" href="{{route(Helper::getNameOfPage('edit1'),['id'=>$item->PrioritasIndikatorSasaranID])}}" title="Ubah Data Indikator">
                                <i class='icon-pencil7'></i>
                            </a> 
                        </li>
                        <li class="text-danger-600">
                            <a class="btnDelete" href="javascript:;" title="Hapus Data Indikator" data-id="{{$item->PrioritasIndikatorSasaranID}}" data-url="{{route(Helper::getNameOfPage('index'))}}">
                                <i class='icon-trash'></i>
                            </a> 
                        </li>
                    </ul>                    
                </td>
            </tr>
            <tr class="text-center info">
                <td colspan="11">
                    <span class="label label-warning label-rounded" style="text-transform: none">
                        <strong>PRIORITASINDIKATORSASARANID:</strong>
                        {{$item->PrioritasIndikatorSasaranID}}
                    </span>
                    <span class="label label-warning label-rounded" style="text-transform: none">
                        <strong>PRIORITASSASARANKABID:</strong>
                        {{$item->PrioritasSasaranKabID}}
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
@else
<div class="panel-body">
    <div class="alert alert-info alert-styled-left alert-bordered">
        <span class="text-semibold">Info!</span>
        Belum ada data yang bisa ditampilkan.
    </div>
</div>   
@endif               