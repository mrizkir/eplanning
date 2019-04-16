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
                <th>NILAI USULAN</th>                
                <th>PRIORITAS</th> 
                <th>RESES ?</th>   
                <th>MUSREN. KEC. ?</th>                                           
                <th width="120">AKSI</th>
            </tr>
        </thead>
        <tbody>                    
        @foreach ($data as $key=>$item)
            <tr>
                <td>
                    <td>{{$item->No}}</td>
                </td>
                <td>{{$item->Uraian}}</td>                
                <td>{{$item->Sasaran_Angka1}} {{$item->Sasaran_Uraian1}}</td>
                <td>{{$item->Target1}}</td>               
                <td>{{$item->Jumlah1}}</td>       
                <td>{{$item->Prioritas}}</td>
                <td>{{$item->isReses}}</td>
                <td>{{$item->isMusren}}</td>
                <td>
                    <ul class="icons-list">
                        <li class="text-danger-600">
                            <a class="btnDelete" href="javascript:;" title="Hapus Data Idikator" data-id="{{$item->RenjaIndikatorID}}" data-url="{{route('usulanprarenjaopd.index')}}">
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