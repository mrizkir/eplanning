<div class="panel-heading">
    <div class="panel-title">
        <h5>DAFTAR INDIKATOR KEGIATAN</h5>
    </div>
    <div class="heading-elements">
        @if ($renja->Privilege==0)
        <div class="heading-btn">

        </div>  
        @endif  
    </div>
</div>
@if (count($dataindikatorkinerja) > 0)
<div class="table-responsive"> 
    <table id="data" class="table table-striped table-hover" style="font-size:11px">
        <thead>
            <tr class="bg-teal-700">
                <th width="55">NO</th>     
                <th>NAMA INDIKATOR</th>                
                <th>TARGET ANGKA</th>
                <th>TARGET URAIAN</th>                        
                <th>TAHUN KE</th> 
                <th>VERIFIKASI</th>      
            </tr>
        </thead>
        <tbody>                    
        @foreach ($dataindikatorkinerja as $key=>$item)
            <tr>
                <td>
                    {{ $key + 1 }}    
                </td>
                <td>{{$item->NamaIndikator}}</td>
                <td>{{$item->Target_Angka}}</td>
                <td>{{$item->Target_Uraian}}</td>                        
                <td>{{$item->TA}}</td>  
                <td>
                    @if ($item->Privilege==0)
                    <span class="label label-flat border-grey text-grey-600 label-icon">
                        <i class="icon-cross2"></i>
                    </span>
                    @else
                    <span class="label label-flat border-success text-success-600 label-icon">
                        <i class="icon-checkmark"></i>
                    </span>                            
                    @endif   
                </td>                                     
            </tr>
            <tr class="text-center info">
                <td colspan="10">
                    <span class="label label-warning label-rounded" style="text-transform: none">
                        <strong>RENJAINDIKATORID:</strong>
                        {{$item->RenjaIndikatorID}}
                    </span>
                    <span class="label label-warning label-rounded">
                        <strong>KET:</strong>
                        {{empty($item->Descr)?'-':$item->Descr}}
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