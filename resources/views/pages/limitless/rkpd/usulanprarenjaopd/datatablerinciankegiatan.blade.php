<div class="panel-heading">
    <div class="panel-title">
        <h5>DAFTAR RINCIAN KEGIATAN</h5>
    </div>
    <div class="heading-elements">
        
    </div>
</div>
@if (count($data) > 0)
<div class="table-responsive"> 
    <table id="data" class="table table-striped table-hover">
        <thead>
            <tr class="bg-teal-700">
                <th width="55">NO</th>     
                <th>NAMA URAIAN</th>                
                <th>SASARAN KEGIATAN</th>  
                <th>TARGET</th> 
                <th>NILAI USULAN</th>                
                <th>PRIORITAS</th>                                         
                <th width="120">AKSI</th>
            </tr>
        </thead>
        <tbody>                    
        @foreach ($data as $key=>$item)
            <tr>
                <td>
                    {{$item->No}}
                </td>
                <td>
                    {{ucwords($item->Uraian)}}<br />
                    <span class="label label-flat border-grey text-grey-600">
                        <a href="#"><strong>Usulan dari: </strong>
                            @if ($item->isSKPD)
                                OPD / SKPD
                            @elseif($item->isReses)
                                POKIR [{{$item->isReses_Uraian}}]
                            @else
                                MUSREN. KEC. {{$item->Nm_Kecamatan}}
                            @endif                            
                        </a> 
                    </span>
                </td>                
                <td>{{Helper::formatAngka($item->Sasaran_Angka1)}} {{ucwords($item->Sasaran_Uraian1)}}</td>
                <td>{{$item->Target1}}</td>               
                <td>{{Helper::formatUang($item->Jumlah1)}}</td>       
                <td>{{HelperKegiatan::getNamaPrioritas($item->Prioritas)}}</td>
                <td>
                    <ul class="icons-list">
                        <li class="text-danger-600">
                            <a class="btnDelete" href="javascript:;" title="Hapus Data Idikator" data-id="{{$item->RenjaRincID}}" data-url="{{route('usulanprarenjaopd.index')}}">
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
@else
<div class="panel-body">
    <div class="alert alert-info alert-styled-left alert-bordered">
        <span class="text-semibold">Info!</span>
        Belum ada data yang bisa ditampilkan.
    </div>
</div>   
@endif               