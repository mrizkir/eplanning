@if (count($data) > 0)
<div class="table-responsive"> 
    <table id="data" border="1">
        <thead>
            <tr>
                <th colspan="5">                   
                    KODE            
                </th>                
                <th width="400">                    
                    NAMA KEGIATAN                                                                                           
                </th> 
                <th width="300">                    
                    NAMA URAIAN                                                                                           
                </th>
                <th width="200">                    
                    SASARAN 
                </th> 
                <th width="120">                        
                    TARGET (%)                        
                </th> 
                <th width="150" class="text-right">                    
                    NILAI M / <br>NILAI P                    
                </th>                     
                
            </tr>
            <tr>
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
                    <span class="text-success">{{Helper::formatuang($item->Jumlah)}}</span><br>
                    <span class="text-danger">{{Helper::formatuang($item->Jumlah2)}}</span>
                </td>                                    
            </tr>           
        @endforeach                    
        </tbody>
    </table>               
</div>
@else       
<div class="alert alert-info alert-styled-left alert-bordered">
    <span class="text-semibold">Info!</span>
    Belum ada data yang bisa ditampilkan. Mohon pilih terlebih dahulu OPD dan Unit Kerja
</div> 
@endif            
