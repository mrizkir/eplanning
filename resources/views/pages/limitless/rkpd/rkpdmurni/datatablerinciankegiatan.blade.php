<div class="panel-heading">
    <div class="panel-title">
        <h5>DAFTAR RINCIAN KEGIATAN</h5>
    </div>
    <div class="heading-elements">        
        @if (Request::route()->getName()=='usulanrakorbidang.show')
        <div class="heading-btn">
            
        </div>  
        @endif  
    </div>
</div>
@if (count($datarinciankegiatan) > 0)
<div class="table-responsive"> 
    <table id="data" class="table table-striped table-hover">
        <thead>
            <tr class="bg-teal-700">
                <th width="55">NO</th>     
                <th>NAMA URAIAN</th>                
                <th>SASARAN URAIAN</th>  
                <th>TARGET (%)</th> 
                <th class="text-right">PAGU INDIKATIF</th>                                      
                <th width="70">STATUS</th> 
                <th width="70">VER.</th>
            </tr>
        </thead>
        <tbody>                    
        @foreach ($datarinciankegiatan as $key=>$item)
            <tr>
                <td>
                    {{$item->No}}
                </td>
                <td>
                    {{ucwords($item->Uraian)}}<br />
                    <span class="label label-flat border-grey text-grey-600">                        
                        @if ($item->isSKPD)
                            <a href="#">
                                <strong>Usulan dari: </strong>OPD / SKPD
                            </a> 
                        @elseif($item->isReses)
                            <a href="#">
                                <strong>Usulan dari: </strong>POKIR [{{$item->isReses_Uraian}}]
                            </a>
                        @else
                            <a href="{{route('aspirasimusrenkecamatan.show',['uuid'=>$item->UsulanKecID])}}">
                                <strong>Usulan dari: MUSREN. KEC. {{$item->Nm_Kecamatan}}
                            </a>
                        @endif
                    </span>
                </td>                
                <td>{{Helper::formatAngka($item->Sasaran_Angka1)}} {{ucwords($item->Sasaran_Uraian1)}}</td>
                <td>{{$item->Target1}}</td>               
                <td class="text-right">{{Helper::formatUang($item->NilaiUsulan1)}}</td>                       
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
                <td colspan="7">
                    <span class="label label-warning label-rounded" style="text-transform: none">
                        <strong>RKPDID:</strong>
                        {{$item->RKPDID}}
                    </span>
                    <span class="label label-warning label-rounded" style="text-transform: none">
                        <strong>RKPDRINCID:</strong>
                        {{$item->RKPDRincID}}
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
                <td class="text-right">{{Helper::formatUang($datarinciankegiatan->sum('NilaiUsulan1'))}}</td> 
                <td colspan="3"></td>
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