<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">&nbsp;</h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-printer"></i> 
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a href="{!!route('reportpembahasanrkpdpopd.printtoexcel')!!}" title="Print to Excel" id="btnprintexcel">
                                <i class="icon-file-excel"></i> Export to Excel
                            </a>     
                        </li>                            
                    </ul>
                </li>
            </ul>     
        </div>
    </div>
    @php
        $daftar_program=\DB::table('v_organisasi_program')
                            ->select(\DB::raw('"PrgID","Kd_Urusan","Kd_Bidang","OrgCd","kode_program","Kd_Prog","PrgNm","Jns"'))
                            ->where('OrgIDRPJMD',$filters['OrgIDRPJMD'])
                            ->orderByRaw('kode_program ASC NULLS FIRST')
                            ->orderBy('Kd_Prog','ASC')
                            ->get();

        
    @endphp
    @if (count($daftar_program) > 0)  
    <div class="table-responsive"> 
        <table id="data" class="table table-xxs table-bordered" style="font-size:11px;padding:0px">
            <thead>
                <tr class="bg-teal-700">
                    <th colspan="5" rowspan="2">                   
                        KODE            
                    </th>                
                    <th width="400" rowspan="2">                    
                        BIDANG URUSAN PEMERINTAH DAERAH DAN PROGRAM/KEGIATAN                                                                                           
                    </th>                    
                    <th width="200" rowspan="2">                    
                        SASARAN 
                    </th> 
                    <th width="200" rowspan="2">                    
                        LOKASI 
                    </th>                     
                    <th width="120" rowspan="2">                        
                        SUMBER DANA                        
                    </th> 
                    <th width="200" rowspan="2">                    
                        TARGET (%) 
                    </th>  
                    <th width="150" colspan="3">                    
                        INDIKASI TA(n)                 
                    </th>  
                    <th width="120" rowspan="2">                        
                        KET.                        
                    </th> 
                </tr>
                <tr class="bg-teal-700">
                    <th>SEBELUM</th>
                    <th>SESUDAH</th>
                    <th>SELISIH</th>
                </tr>
            </thead>
            <tbody>                
            @php
                $total_pagu_m=0;
                $total_pagu_p=0;
            @endphp       
            @foreach ($daftar_program as $key=>$v){{-- startlooping daftar program --}}
            @php
                $PrgID=$v->PrgID;                 
                $daftar_kegiatan = \DB::table('v_rkpd')
                                        ->select(\DB::raw('"RKPDID","Kd_Urusan","Kd_Bidang","OrgCd","Kd_Prog","Kd_Keg","kode_kegiatan","KgtNm","Sasaran_Angka3","Sasaran_Angka4","Sasaran_Uraian3","Sasaran_Angka4","Target3","Target4","NilaiUsulan3","NilaiUsulan4","Sasaran_AngkaSetelah","Sasaran_UraianSetelah","NilaiSetelah","Nm_SumberDana","Descr"'))
                                        ->where('PrgID',$PrgID)      
                                        ->where('OrgID',$filters['OrgID'])
                                        ->where('TA',HelperKegiatan::getTahunPerencanaan())
                                        ->where('EntryLvl',4)
                                        ->orderBy('kode_kegiatan','ASC')       
                                        ->get();                
            @endphp
            @if (isset($daftar_kegiatan[0]))  
            <tr class="bg-warning-300">
                <td>{{$v->Kd_Urusan}}</td>
                <td>{{$v->Kd_Bidang}}</td>
                <td>{{$v->OrgCd}}</td>
                <td>{{$v->Kd_Prog}}</td>
                <td></td>
                <td>{{$v->PrgNm}}</td>
                <td colspan="4"></td>
                @php
                    $totalpagueachprogramM= $daftar_kegiatan->sum('NilaiUsulan3');      
                    $totalpagueachprogramP= $daftar_kegiatan->sum('NilaiUsulan4');   
                @endphp
                <td>{{Helper::formatUang($totalpagueachprogramM)}}</td>
                <td>{{Helper::formatUang($totalpagueachprogramP)}}</td>
                <td>{{Helper::formatUang($totalpagueachprogramP-$totalpagueachprogramM)}}</td>
                <td></td>
            </tr>
            @foreach ($daftar_kegiatan as $key=>$item){{-- startlooping daftar kegiatan --}}
                <tr>                        
                    <td>{{$item->Kd_Urusan}}</td>
                    <td>{{$item->Kd_Bidang}}</td>
                    <td>{{$item->OrgCd}}</td>
                    <td>{{$item->Kd_Prog}}</td>
                    <td>{{$item->Kd_Keg}}</td>
                    <td rowspan="2">
                        {{ucwords($item->KgtNm)}}                           
                    </td> 
                    <td rowspan="2">{{Helper::formatAngka($item->Sasaran_Angka4)}} {{$item->Sasaran_Angka4}}</td>                      
                    <td rowspan="2">
                        KAB. BINTAN
                    </td>
                    <td rowspan="2">{{$item->Nm_SumberDana}}</td>
                    <td rowspan="2">{{$item->Target4}}</td>
                    <td class="text-right" rowspan="2">
                        <span class="">{{Helper::formatuang($item->NilaiUsulan3)}}</span>                    
                    </td>       
                    <td class="text-right" rowspan="2">
                        <span class="text-info">{{Helper::formatuang($item->NilaiUsulan4)}}</span>
                    </td>                             
                    <td class="text-right" rowspan="2">
                        <span class="">{{Helper::formatuang($item->NilaiUsulan4-$item->NilaiUsulan3)}}</span>
                    </td>                             
                    <td rowspan="2">{{$item->Descr}}</td>
                </tr>           
                <tr class="text-center">
                    <td colspan="5" class="bg-info-300">
                        RKPDID: {{$item->RKPDID}}
                    </td>
                </tr>
                @php
                    $total_pagu_m+=$item->NilaiUsulan3;
                    $total_pagu_p+=$item->NilaiUsulan4;;
                @endphp
            @endforeach      
            {{-- end looping daftar kegiatan --}}
            @endif
            {{-- endif dari daftar kegiatan --}}
            @endforeach      
            {{-- end looping daftar program --}}
            </tbody>
            <tfoot>
                <tr class="bg-grey-300" style="font-weight:bold">
                    <td colspan="10" class="text-right">
                        TOTAL                        
                    </td>
                    <td class="text-right">{{Helper::formatUang($total_pagu_m)}}</td> 
                    <td class="text-right">{{Helper::formatUang($total_pagu_p)}}</td>
                    <td class="text-right">{{Helper::formatUang($total_pagu_p-$total_pagu_m)}}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>               
    </div>
    @else  
    <div class="panel-body">					     
        <div class="alert alert-info alert-styled-left alert-bordered">
            <span class="text-semibold">Info!</span>
            Belum ada data yang bisa ditampilkan. Mohon pilih terlebih dahulu OPD dan Unit Kerja
        </div> 
    </div>
    @endif           
</div>
