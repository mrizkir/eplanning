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
                            <a href="{!!route('reportprogramperubahanopd.printtoexcel')!!}" title="Print to Excel" id="btnprintexcel">
                                <i class="icon-file-excel"></i> Export to Excel
                            </a>     
                        </li>                            
                    </ul>
                </li>
            </ul>     
        </div>
    </div>
    @php
        $rkpdreport=new \App\Models\Report\ReportProgramRKPDPerubahanModel ([],false);
        $struktur=$rkpdreport->generateStructure('OrgID',$filters['OrgID'],4);                
        $total_all_nilai_usulan=0;      
        $total_all_nilai_setelah=0;  
    @endphp
    @if (count($struktur) > 0)  
    <div class="table-responsive"> 
        <table id="data" class="table table-xxs table-bordered" style="font-size:11px;padding:0px">
            <thead>
                <tr class="bg-teal-700">
                    <th colspan="4" rowspan="2">                   
                        KODE            
                    </th>                
                    <th rowspan="2">                    
                        BIDANG URUSAN PEMERINTAH DAERAH                                                                                           
                    </th>
                    <th width="80" rowspan="2">                    
                        JUMLAH KEGIATAN <br>
                        KESELURUHAN                                                                                         
                    </th>     
                    <th width="80" rowspan="2">                    
                        JUMLAH KEGIATAN <br>PERUBAHAN                                                                                          
                    </th>                     
                    <th width="200" class="text-center" colspan="3">                    
                        INDIKASI TA {{HelperKegiatan::getTahunPerencanaan()}}
                    </th>
                </tr>   
                <tr class="bg-teal-700">
                    <th class="text-right">SEBELUM</th>
                    <th class="text-right">SESUDAH</th>
                    <th class="text-right">SELISIH</th>
                </tr>
            </thead>
            <tbody>                
            @php
                $total_pagu_m=0;
                $total_pagu_p=0;
                $total_jumlah_kegiatan=0;
                $total_jumlah_kegiatan_p=0;
            @endphp       
            @foreach ($struktur as $key=>$v){{-- startlooping daftar program --}}
            @php
                $PrgID=$v->PrgID;                 
                $daftar_kegiatan = \DB::table('v_rkpd')
                                        ->select(\DB::raw('SUM("NilaiUsulan1") AS jumlah_nilaiusulanm,SUM("NilaiUsulan2") AS jumlah_nilaiusulanp,COUNT("RKPDID") AS jumlah_kegiatan'))
                                        ->where('PrgID',$PrgID)                                              
                                        ->where('OrgID',$filters['OrgID'])
                                        ->where('TA',HelperKegiatan::getTahunPerencanaan())                                        
                                        ->first();                
                                        
                $p = \DB::table('v_rkpd')
                                        ->select(\DB::raw('COUNT("RKPDID") AS jumlah_kegiatan'))
                                        ->where('PrgID',$PrgID)                                              
                                        ->where('OrgID',$filters['OrgID'])
                                        ->whereRaw('"NilaiUsulan1"!="NilaiUsulan2"')
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
                    $jumlah_nilaiusulanm= $daftar_kegiatan->jumlah_nilaiusulanm;
                    $jumlah_nilaiusulanp= $daftar_kegiatan->jumlah_nilaiusulanp;
                    $jumlah_kegiatan= $daftar_kegiatan->jumlah_kegiatan;     
                    $jumlah_kegiatan_p= $p->jumlah_kegiatan;     
                    $total_pagu_m+=$jumlah_nilaiusulanm;
                    $total_pagu_p+=$jumlah_nilaiusulanp;
                    $total_jumlah_kegiatan+=$jumlah_kegiatan;                     
                    $total_jumlah_kegiatan_p+=$jumlah_kegiatan_p;                     
                @endphp
                <td>{{$jumlah_kegiatan}}</td>
                <td>{{$jumlah_kegiatan_p}}</td>
                <td class="text-right">{{Helper::formatUang($jumlah_nilaiusulanm)}}</td>                             
                <td class="text-right">{{Helper::formatUang($jumlah_nilaiusulanp)}}</td>                             
                <td class="text-right">{{Helper::formatUang($jumlah_nilaiusulanp-$jumlah_nilaiusulanm)}}</td>                             
            </tr>
            @endforeach                              
            {{-- end looping daftar program --}}
            </tbody>
            <tfoot>
                <tr class="bg-grey-300" style="font-weight:bold">
                    <td colspan="5" class="text-right">
                        TOTAL                        
                    </td>                    
                    <td>{{$total_jumlah_kegiatan}}</td>
                    <td>{{$total_jumlah_kegiatan_p}}</td>
                    <td class="text-right">{{Helper::formatUang($total_pagu_m)}}</td>                     
                    <td class="text-right">{{Helper::formatUang($total_pagu_p)}}</td>                     
                    <td class="text-right">{{Helper::formatUang($total_pagu_p-$total_pagu_m)}}</td>                     
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
