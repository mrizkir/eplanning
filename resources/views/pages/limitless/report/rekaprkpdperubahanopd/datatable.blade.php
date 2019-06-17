@if (count($data) > 0)
<div class="table-responsive"> 
    <table id="data" class="table table-bordered">
        <thead>
            <tr>
                <th colspan="5" rowspan="2">                   
                    KODE            
                </th>                
                <th width="400" rowspan="2">                    
                    NAMA KEGIATAN                                                                                           
                </th> 
                <th width="300" rowspan="2">                    
                    NAMA URAIAN                                                                                           
                </th>
                <th width="200" rowspan="2">                    
                    SASARAN 
                </th> 
                <th width="120" rowspan="2">                        
                    TARGET (%)                        
                </th> 
                <th width="150" colspan="3">                    
                    PAGU DANA                  
                </th>  
            </tr>
            <tr>
                <th>SEBELUM</th>
                <th>SESUDAH</th>
                <th>SELISIH</th>
            </tr>
        </thead>
        <tbody>                    
        @foreach ($data as $key=>$item)
            <tr>                        
                <td>{{$item->Kd_Urusan}}</td>
                <td>{{$item->Kd_Bidang}}</td>
                <td>{{$item->OrgCd}}</td>
                <td>{{$item->Kd_Prog}}</td>
                <td>{{$item->Kd_Keg}}</td>
                <td>
                    {{ucwords($item->KgtNm)}}                           
                </td>                       
                <td>
                    {{ucwords($item->Uraian)}}
                    @if ($item->isSKPD)
                        <br>
                        <span class="label label-flat border-grey text-grey-600">                        
                            <a href="#">
                                <strong>Usulan dari: </strong>OPD / SKPD
                            </a> 
                        </span>
                    @elseif($item->isReses)
                        <br>
                        <span class="label label-flat border-grey text-grey-600">                        
                            <a href="#">
                                <strong>Usulan dari: </strong>POKIR [{{$item->isReses_Uraian}}]
                            </a>
                        </span>
                    @elseif(!empty($item->UsulanKecID))
                        <br>
                        <span class="label label-flat border-grey text-grey-600">                        
                            <a href="{{route('aspirasimusrenkecamatan.show',['id'=>$item->UsulanKecID])}}">
                                <strong>Usulan dari: MUSREN. KEC. {{$item->Nm_Kecamatan}}
                            </a>
                        </span>
                    @endif
                </td>
                <td>{{Helper::formatAngka($item->Sasaran_Angka)}} {{$item->Sasaran_Uraian}}</td>
                <td>{{$item->Target}}</td>
                <td class="text-right">
                    <span class="">{{Helper::formatuang($item->Jumlah)}}</span>                    
                </td>       
                <td class="text-right">
                    <span class="text-info">{{Helper::formatuang($item->Jumlah2)}}</span>
                </td>                             
                <td class="text-right">
                    <span class="">{{Helper::formatuang($item->Jumlah2-$item->Jumlah)}}</span>
                </td>                             
            </tr>           
        @endforeach                    
        </tbody>
        <tfoot>
            <tr class="bg-grey-300" style="font-weight:bold">
                <td colspan="9" class="text-right">
                    TOTAL
                    @php
                        $jumlah_m=$data->sum('Jumlah');
                        $jumlah_p=$data->sum('Jumlah2');
                    @endphp
                </td>
                <td class="text-right">{{Helper::formatUang($jumlah_m)}}</td> 
                <td class="text-right">{{Helper::formatUang($jumlah_p)}}</td>
                <td class="text-right">{{Helper::formatUang($jumlah_p-$jumlah_m)}}</td>
            </tr>
        </tfoot>
    </table>               
</div>
@else       
<div class="alert alert-info alert-styled-left alert-bordered">
    <span class="text-semibold">Info!</span>
    Belum ada data yang bisa ditampilkan. Mohon pilih terlebih dahulu OPD dan Unit Kerja
</div> 
@endif            
