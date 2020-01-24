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
                <th>N1</th>                     
                <th>N2</th>                     
                <th>N3</th>                     
                <th>N4</th>                     
                <th>N5</th> 
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
                <td>{{$item->N1}}</td>                        
                <td>{{$item->N2}}</td>                        
                <td>{{$item->N3}}</td>                        
                <td>{{$item->N4}}</td>                        
                <td>{{$item->N5}}</td>                        
                <td>                    
                    <ul class="icons-list">
                        <li class="text-primary-600">                            
                            <a class="btnEdit" href="{{route(Helper::getNameOfPage('edit1'),['uuid'=>$item->RenstraIndikatorSasaranID])}}" title="Ubah Data Indikator">
                                <i class='icon-pencil7'></i>
                            </a> 
                        </li>
                        <li class="text-danger-600">
                            <a class="btnDelete" href="javascript:;" title="Hapus Data Indikator" data-id="{{$item->RenstraIndikatorSasaranID}}" data-url="{{route(Helper::getNameOfPage('index'))}}">
                                <i class='icon-trash'></i>
                            </a> 
                        </li>
                    </ul>                    
                </td>
            </tr>
            <tr class="text-center info">
                <td colspan="11">
                    <span class="label label-warning label-rounded" style="text-transform: none">
                        <strong>RENSTRAINDIKATORSASARANID:</strong>
                        {{$item->RenstraIndikatorSasaranID}}
                    </span>
                    <span class="label label-warning label-rounded" style="text-transform: none">
                        <strong>RENSTRASASARANID:</strong>
                        {{$item->RenstraSasaranID}}
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