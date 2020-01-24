<div class="panel-heading">
    <div class="panel-title">
        <h5>DAFTAR RINCIAN KEGIATAN</h5>
    </div>
    <div class="heading-elements">    
        
    </div>
</div>
@if (count($datarinciankegiatan) > 0)
<div class="table-responsive"> 
    <table id="data" class="table table-striped table-hover" style="font-size:11px">
        <thead>
            <tr class="bg-teal-700">
                <th width="55">NO</th>     
                <th>NAMA URAIAN</th>                
                <th>SASARAN URAIAN</th>  
                <th>TARGET (%)</th> 
                <th class="text-right">NILAI USULAN</th>                
                <th width="120">PRIORITAS</th>   
                <th width="80">STATUS</th>                                       
                <th width="80">VERIFIKASI</th>  
            </tr>
        </thead>
        <tbody>                    
        @foreach ($datarinciankegiatan as $key=>$item)
            <tr>
                <td>
                    {{$item->No}}
                </td>
                <td>
                    {{ucwords($item->Uraian)}}
                   @if ($item->isSKPD)
                        <br />
                        <span class="label label-flat border-grey text-grey-600">                        
                            <a href="#">
                                <strong>Usulan dari: </strong>OPD / SKPD
                            </a> 
                        </span>
                    @elseif($item->isReses)
                        <br />
                        <span class="label label-flat border-grey text-grey-600">                        
                            <a href="#">
                                <strong>Usulan dari: </strong>POKIR [{{$item->isReses_Uraian}}]
                            </a>
                        </span>
                    @elseif(!empty($item->UsulanKecID))
                        <br />
                        <span class="label label-flat border-grey text-grey-600">                        
                            <a href="{{route('aspirasimusrenkecamatan.show',['uuid'=>$item->UsulanKecID])}}">
                                <strong>Usulan dari: MUSREN. KEC. {{$item->Nm_Kecamatan}}
                            </a>
                        </span>
                    @endif
                </td>                
                <td>{{Helper::formatAngka($item->Sasaran_Angka)}} {{ucwords($item->Sasaran_Uraian)}}</td>
                <td>{{$item->Target}}</td>               
                <td class="text-right">{{Helper::formatUang($item->Jumlah)}}</td>       
                <td>
                    <span class="label label-flat border-pink text-pink-600">
                        {{HelperKegiatan::getNamaPrioritas($item->Prioritas)}}
                    </span>
                </td>                
                <td>
                    @include('layouts.limitless.l_status_kegiatan')
                </td>
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
                        <strong>RENJARINCID:</strong>
                        {{$item->RenjaRincID}}
                    </span>
                    <span class="label label-warning label-rounded">
                        <strong>KET:</strong>
                        {{empty($item->Descr)?'-':$item->Descr}}
                    </span>
                </td>
            </tr>
        @endforeach                    
        </tbody>
        <tfoot>
            <tr class="bg-grey-300" style="font-weight:bold">
                <td colspan="4" class="text-right">TOTAL</td>
                <td class="text-right">{{Helper::formatUang($datarinciankegiatan->sum('Jumlah'))}}</td> 
                <td colspan="4"></td>
            </tr>
        </tfoot>
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