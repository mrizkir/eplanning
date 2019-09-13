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
                            <a href="{!!route('reportpembahasanrkpdmurniopd.printtoexcel')!!}" title="Print to Excel" id="btnprintexcel">
                                <i class="icon-file-excel"></i> Export to Excel
                            </a>     
                        </li>                            
                    </ul>
                </li>
            </ul>     
        </div>
    </div>
    @php
        $rkpdreport=new \App\Models\Report\ReportRKPDPembahasanMurniModel ([],false);
        $struktur=$rkpdreport->generateStructure('OrgID',$filters['OrgID']);              
    @endphp
    @if (count($struktur) > 0)  
    <div class="table-responsive"> 
        <table id="data" class="reporttable">
            <thead>
                <tr>
                    <th colspan="5" rowspan="2">                   
                        KODE            
                    </th>                
                    <th width="400" rowspan="2" colspan="2">                    
                        BIDANG URUSAN PEMERINTAH DAERAH DAN PROGRAM/KEGIATAN                                                                                           
                    </th>                    
                    <th width="200" rowspan="2">                    
                        INDIKATOR KINERJA PROGRAM/KEGIATAN
                    </th> 
                    <th width="200" colspan="4">                    
                        RENCANA TAHUN {{HelperKegiatan::getTahunPerencanaan()}}
                    </th>                                
                    <th width="150" rowspan="2">                    
                        CATATAN PENTING
                    </th>                      
                    <th width="200" colspan="2">                    
                        PERKIRAAN MAJU RENCANA TAHUN {{HelperKegiatan::getTahunPerencanaan()+1}}
                    </th>  
                </tr>
                <tr>
                    <th>LOKASI</th>
                    <th width="200">TARGET CAPAIAN KINERJA</th>
                    <th>KEBUTUHAN DANA/PAGU INDIKATIF</th>
                    <th>SUMBER DANA</th>
                    <th width="200">TARGET CAPAIAN KINERJA</th>
                    <th>KEBUTUHAN DANA/PAGU INDIKATIF</th>
                </tr>
            </thead>
            <tbody>                
            @foreach ($struktur as $Kd_Urusan=>$v1){{-- startlooping struktur --}}
                <tr class="urusan">
                    <td>{{$Kd_Urusan}}</td>
                    <td colspan="4">&nbsp;</td>
                    <td colspan="5">{{$v1['Nm_Urusan']}}</td>
                </tr>
                @if ($Kd_Urusan == 0)
                    @php
                        $program=$v1['program'];
                    @endphp
                    @foreach ($program as $v3){{-- startlooping program --}}
                        @php
                            $daftar_kegiatan = \DB::table('trRKPD')
                                                    ->select(\DB::raw('"trRKPD"."KgtID","tmKgt"."Kd_Keg","tmKgt"."KgtNm"'))
                                                    ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                                                    ->where('PrgID',$v3['PrgID'])
                                                    ->where('EntryLvl',2)                                               
                                                    ->where('OrgID',$filters['OrgID'])
                                                    ->groupBy('trRKPD.KgtID')
                                                    ->groupBy('tmKgt.Kd_Keg')
                                                    ->groupBy('tmKgt.KgtNm')
                                                    ->orderByRaw('"tmKgt"."Kd_Keg"::int ASC')
                                                    ->get();
                        @endphp
                        @if (count($daftar_kegiatan)  > 0)
                            @php
                                $Kd_Prog = $v3['Kd_Prog'];
                            @endphp
                            <tr class="program">
                                <td>0</td>
                                <td>00</td>
                                <td>{{$Kd_Prog}}</td>                                
                                <td colspan="2">&nbsp;</td>
                                <td colspan="5">{{$v3['PrgNm']}}</td>
                            </tr>
                            @foreach ($daftar_kegiatan as $v4){{-- startlooping daftar kegiatan --}}
                                @php
                                    $rkpd = \DB::table('v_rkpd')
                                                    ->where('KgtID',$v4->KgtID)
                                                    ->where('EntryLvl',2)
                                                    ->where('OrgID',$filters['OrgID'])
                                                    ->first();   
                                @endphp
                                <tr>
                                    <td>0</td>
                                    <td>00</td>
                                    <td>{{$Kd_Prog}}</td>            
                                    <td>{{$v4->Kd_Keg}}</td>     
                                    <td>&nbsp;</td>       
                                    <td colspan="2">{{$v4->KgtNm}}</td>            
                                    <td>{{$rkpd->NamaIndikator}}</td>
                                    <td>Kab. Bintan</td>
                                    <td>{{trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_Angka2) . ' '.$rkpd->Sasaran_Uraian2))}}</td>
                                    <td>{{Helper::formatUang($rkpd->NilaiUsulan2)}}</td>
                                    <td>{{$rkpd->Nm_SumberDana}}</td>
                                    <td>{{$rkpd->Descr}}</td>
                                    <td>{{trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_AngkaSetelah).' '.$rkpd->Sasaran_UraianSetelah))}}</td>
                                    <td>{{Helper::formatUang($rkpd->NilaiSetelah)}}</td>
                                </tr>
                                @php
                                    $rincian_kegiatan = \DB::table('v_rkpd_rinci')
                                                    ->select(\DB::raw('
                                                                    "Uraian",
                                                                    "Sasaran_Angka2",
                                                                    "Sasaran_Uraian2",
                                                                    "Target2",
                                                                    "NilaiUsulan2",
                                                                    "Nm_SumberDana",
                                                                    "NilaiUsulan2",
                                                                    "Lokasi",
                                                                    "Descr"
                                                                ')
                                                    )                                                
                                                    ->where('EntryLvl',2)
                                                    ->where('KgtID',$v4->KgtID)
                                                    ->where('PrgID',$v3['PrgID'])
                                                    ->where('OrgID',$filters['OrgID'])
                                                    ->orderByRaw('"No"::int ASC')
                                                    ->get();

                                    $no=1;
                                @endphp
                                @foreach ($rincian_kegiatan as $v5){{-- startlooping rincian kegiatan --}}
                                    <tr class="rincian">
                                        <td>0</td>
                                        <td>00</td>
                                        <td>{{$Kd_Prog}}</td>            
                                        <td>{{$v4->Kd_Keg}}</td>     
                                        <td>{{$no}}</td>     
                                        <td colspan="2">{{$v5->Uraian}}</td>            
                                        <td>{{$rkpd->NamaIndikator}}</td>
                                        <td>Kab. Bintan</td>
                                        <td>{{trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($v5->Sasaran_Angka2) . ' '.$v5->Sasaran_Uraian2))}}</td>
                                        <td>{{Helper::formatUang($v5->NilaiUsulan2)}}</td>
                                        <td>{{$v5->Nm_SumberDana}}</td>
                                        <td>{{$v5->Descr}}</td>
                                        <td>{{trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_AngkaSetelah).' '.$rkpd->Sasaran_UraianSetelah))}}</td>
                                        <td>{{Helper::formatUang($rkpd->NilaiSetelah)}}</td>
                                    </tr>
                                    @php
                                        $no+=1;
                                    @endphp
                                @endforeach{{-- end looping rincian kegiatan --}}      
                            @endforeach{{-- end looping daftar kegiatan --}}      
                        @endif        
                    @endforeach{{-- end looping daftar program --}}                        
                @else
                    @php
                        $bidang_pemerintahan=$v1['bidang_pemerintahan'];                    
                    @endphp
                    @foreach ($bidang_pemerintahan as $Kd_Bidang=>$v2){{-- startlooping bidang pemerintah --}}
                        <tr class="urusan">
                            <td>{{$Kd_Urusan}}</td>
                            <td>{{$Kd_Bidang}}</td>
                            <td colspan="3">&nbsp;</td>
                            <td colspan="5">{{$v2['Nm_Bidang']}}</td>
                        </tr>
                        @php
                            $program=$v2['program'];
                        @endphp
                        @foreach ($program as $v3){{-- startlooping program --}}
                            @php
                                $daftar_kegiatan = \DB::table('trRKPD')
                                                        ->select(\DB::raw('"trRKPD"."KgtID","tmKgt"."Kd_Keg","tmKgt"."KgtNm"'))
                                                        ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                                                        ->where('PrgID',$v3['PrgID'])
                                                        ->where('EntryLvl',2)                                               
                                                        ->where('OrgID',$filters['OrgID'])
                                                        ->groupBy('trRKPD.KgtID')
                                                        ->groupBy('tmKgt.Kd_Keg')
                                                        ->groupBy('tmKgt.KgtNm')
                                                        ->orderByRaw('"tmKgt"."Kd_Keg"::int ASC')
                                                        ->get();
                            @endphp
                            @if (count($daftar_kegiatan)  > 0)
                                @php
                                    $Kd_Prog = $v3['Kd_Prog'];
                                @endphp
                                <tr class="program">
                                    <td>{{$Kd_Urusan}}</td>
                                    <td>{{$Kd_Bidang}}</td>
                                    <td>{{$Kd_Prog}}</td>                                
                                    <td colspan="2">&nbsp;</td>
                                    <td colspan="5">{{$v3['PrgNm']}}</td>
                                </tr>
                                @foreach ($daftar_kegiatan as $v4){{-- startlooping daftar kegiatan --}}
                                    @php
                                        $rkpd = \DB::table('v_rkpd')
                                                        ->where('KgtID',$v4->KgtID)
                                                        ->where('EntryLvl',2)
                                                        ->where('OrgID',$filters['OrgID'])
                                                        ->first();   
                                    @endphp
                                    <tr>
                                        <td>{{$Kd_Urusan}}</td>
                                        <td>{{$Kd_Bidang}}</td>
                                        <td>{{$Kd_Prog}}</td>            
                                        <td>{{$v4->Kd_Keg}}</td>     
                                        <td>&nbsp;</td>       
                                        <td colspan="2">{{$v4->KgtNm}}</td>            
                                        <td>{{$rkpd->NamaIndikator}}</td>
                                        <td>Kab. Bintan</td>
                                        <td>{{trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_Angka2) . ' '.$rkpd->Sasaran_Uraian2))}}</td>
                                        <td>0</td>
                                        <td>{{$rkpd->Nm_SumberDana}}</td>
                                        <td>{{$rkpd->Descr}}</td>
                                        <td>{{trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_AngkaSetelah).' '.$rkpd->Sasaran_UraianSetelah))}}</td>
                                        <td>0</td>
                                    </tr>
                                    @php
                                        $rincian_kegiatan = \DB::table('v_rkpd_rinci')
                                                        ->select(\DB::raw('
                                                                        "Uraian",
                                                                        "Sasaran_Angka2",
                                                                        "Sasaran_Uraian2",
                                                                        "Target2",
                                                                        "NilaiUsulan2",
                                                                        "Nm_SumberDana",
                                                                        "NilaiUsulan2",
                                                                        "Lokasi",
                                                                        "Descr"
                                                                    ')
                                                        )                                                
                                                        ->where('EntryLvl',2)
                                                        ->where('KgtID',$v4->KgtID)
                                                        ->where('PrgID',$v3['PrgID'])
                                                        ->where('OrgID',$filters['OrgID'])
                                                        ->orderByRaw('"No"::int ASC')
                                                        ->get();

                                        $no=1;                                        
                                    @endphp
                                    @foreach ($rincian_kegiatan as $v5){{-- startlooping rincian kegiatan --}}
                                        <tr class="rincian">
                                            <td>{{$Kd_Urusan}}</td>
                                            <td>{{$Kd_Bidang}}</td>
                                            <td>{{$Kd_Prog}}</td>            
                                            <td>{{$v4->Kd_Keg}}</td>     
                                            <td>{{$no}}</td>     
                                            <td colspan="2">{{$v5->Uraian}}</td>            
                                            <td>{{$rkpd->NamaIndikator}}</td>
                                            <td>Kab. Bintan</td>
                                            <td>{{trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($v5->Sasaran_Angka2) . ' '.$v5->Sasaran_Uraian2))}}</td>
                                            <td>{{Helper::formatUang($v5->NilaiUsulan2)}}</td>
                                            <td>{{$v5->Nm_SumberDana}}</td>
                                            <td>{{$v5->Descr}}</td>
                                            <td>{{trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_AngkaSetelah).' '.$rkpd->Sasaran_UraianSetelah))}}</td>
                                            <td>{{Helper::formatUang($rkpd->NilaiSetelah)}}</td>
                                        </tr>
                                        @php
                                            $no+=1;
                                        @endphp
                                    @endforeach{{-- end looping rincian kegiatan --}}      
                                @endforeach{{-- end looping daftar kegiatan --}}      
                            @endif 
                        @endforeach{{-- end looping program --}}                        
                    @endforeach{{-- end looping bidang pemerintah --}}                        
                @endif
            @endforeach{{-- end looping struktur  --}}                        
            
            </tbody>
            <tfoot>
                
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
