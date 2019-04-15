<div class="panel-heading">
    <div class="panel-title">
        <h5>DAFTAR INDIKATOR KEGIATAN</h5>
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
                <th>NAMA INDIKATOR</th>                
                <th>TARGET ANGKA</th>
                <th>TARGET URAIAN</th>                        
                <th>TAHUN KE</th>                        
                <th width="120">AKSI</th>
            </tr>
        </thead>
        <tbody>                    
        @foreach ($data as $key=>$item)
            <tr>
                <td>
                    {{ $key + 1 }}    
                </td>
                <td>{{$item->NamaIndikator}}</td>
                <td>{{$item->Target_Angka}}</td>
                <td>{{$item->Target_Uraian}}</td>                        
                <td>{{$item->Tahun}} ({{$item->TA}})</td>                        
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