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
                            <a href="{!!route('reportprogrammurniopd.printtoexcel')!!}" title="Print to Excel" id="btnprintexcel">
                                <i class="icon-file-excel"></i> Export to Excel
                            </a>     
                        </li>                            
                    </ul>
                </li>
            </ul>     
        </div>
    </div>
    @php
        $n1 = \HelperKegiatan::getTahunPerencanaan()+1;
        $daftar_program=\DB::table('v_organisasi_program')
                            ->select(\DB::raw('"PrgID","Kd_Urusan","Kd_Bidang","OrgCd","kode_program","Kd_Prog","PrgNm","Jns"'))
                            ->where('OrgID',$filters['OrgID'])
                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                            ->orderByRaw('kode_program ASC NULLS FIRST')
                            ->orderBy('Kd_Prog','ASC')
                            ->get();

        
    @endphp
    @if (count($daftar_program) > 0)  
    <div class="table-responsive"> 
        <table id="data" class="table table-xxs table-bordered" style="font-size:11px;padding:0px">
            <thead>
                <tr class="bg-teal-700">
                    <th colspan="4">                   
                        KODE            
                    </th>                
                    <th>                    
                        BIDANG URUSAN PEMERINTAH DAERAH                                                                                           
                    </th>
                    <th width="80">                    
                        JUMLAH KEGIATAN                                                                                           
                    </th>                     
                    <th width="200" class="text-right">                    
                        INDIKASI TA {{HelperKegiatan::getTahunPerencanaan()}}
                    </th>
                </tr>                
            </thead>
            <tbody>                
            @php
                $total_pagu_m=0;
                $total_jumlah_kegiatan_m=0;
            @endphp       
            @foreach ($daftar_program as $key=>$v){{-- startlooping daftar program --}}
            @php
                $PrgID=$v->PrgID;                 
                $daftar_kegiatan = \DB::table('v_rkpd')
                                        ->select(\DB::raw('SUM("NilaiUsulan1") AS jumlah_nilaiusulan,COUNT("RKPDID") AS jumlah_kegiatan'))
                                        ->where('PrgID',$PrgID)                                              
                                        ->where('OrgID',$filters['OrgID'])
                                        ->where('TA',HelperKegiatan::getTahunPerencanaan())                                        
                                        ->first();               
            @endphp           
            <tr>
                <td>{{$v->Kd_Urusan}}</td>
                <td>{{$v->Kd_Bidang}}</td>
                <td>{{$v->OrgCd}}</td>
                <td>{{$v->Kd_Prog}}</td>
                <td>{{$v->PrgNm}}</td>
                @php
                    $totalpagueachprogramNilaiUsulan= $daftar_kegiatan->jumlah_nilaiusulan;
                    $jumlah_kegiatan= $daftar_kegiatan->jumlah_kegiatan;     
                    $total_pagu_m+=$totalpagueachprogramNilaiUsulan;
                    $total_jumlah_kegiatan_m+=$jumlah_kegiatan;                     
                @endphp
                <td>{{$jumlah_kegiatan}}</td>
                <td class="text-right">{{Helper::formatUang($totalpagueachprogramNilaiUsulan)}}</td>                             
            </tr>
            @endforeach                              
            {{-- end looping daftar program --}}
            </tbody>
            <tfoot>
                <tr class="bg-grey-300" style="font-weight:bold">
                    <td colspan="5" class="text-right">
                        TOTAL                        
                    </td>
                    
                    <td>{{$total_jumlah_kegiatan_m}}</td>
                    <td class="text-right">{{Helper::formatUang($total_pagu_m)}}</td>                     
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
