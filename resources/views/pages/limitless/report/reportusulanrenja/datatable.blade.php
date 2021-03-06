<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">    
    @if (count($daftar_opd) > 0)
    <div class="panel-body">
        Catatan: Jumlah kegiatan dihitung berdasarkan kegiatan yang sudah ada uraiannya.
    </div>
    <div class="table-responsive">
        <table id="data" class="table table-striped table-hover">
            <thead>
                <tr class="bg-teal-700">
                    <th width="55">NO</th>
                    <th width="100">
                        <a class="column-sort text-white" id="col-kode_organisasi" href="#">
                            KODE OPD 
                        </a>                                             
                    </th>
                    <th width="400" class="text-left">
                        <a class="column-sort text-white" id="col-OrgNm" href="#">
                            NAMA OPD
                        </a>                                             
                    </th>                           
                    <th width="100" class="text-left">
                        <a class="column-sort text-white" href="#">
                            JUMLAH PROGRAM
                        </a>                                             
                    </th>                           
                    <th width="100" class="text-left">
                        <a class="column-sort text-white" href="#">
                            JUMLAH KEGIATAN
                        </a>                                             
                    </th>                      
                    <th width="100" class="text-left">
                        <a class="column-sort text-white" href="#">
                            JUMLAH POKIR TERAKOMODIR
                        </a>                                             
                    </th>                           
                    <th width="100" class="text-left">
                        <a class="column-sort text-white" href="#">
                            JUMLAH USULAN MUSREN. KEC. TERAKOMODIR
                        </a>                                             
                    </th>      
                    <th width="100" class="text-left">
                        <a class="column-sort text-white" href="#">
                            TOTAL PAGU DIENTRI
                        </a>                                             
                    </th>
                    <th width="100" class="text-left">
                        <a class="column-sort text-white" href="#">
                            PAGU PENETAPAN
                        </a>                                             
                    </th>
                    <th width="120">AKSI</th>
                </tr>
            </thead>
            <tbody>   
            @php
                $total_pagu_penetapan=0;    
                $total_pagu_unit=0;  
                $status_kegiatan=$filters['status_kegiatan'];
            @endphp
            @foreach ($daftar_opd as $k=>$item)                
                @php
                $OrgID=$item->OrgID;
                $pagu_penetapan=$item->Jumlah1;                                
                switch ($page_active)
                {
                    case 'reportusulanprarenjaopd' :
                        $jumlah_program = \DB::table('trRenja')
                                            ->join('tmKgt','trRenja.KgtID','tmKgt.KgtID')
                                            ->where('OrgID',$OrgID)
                                            ->where('EntryLvl',0)
                                            ->where('trRenja.Status',$status_kegiatan)
                                            ->count(\DB::raw('DISTINCT("PrgID")'));

                        $renja = \DB::table('trRenja')
                                            ->join('trRenjaRinc','trRenjaRinc.RenjaID','trRenja.RenjaID')
                                            ->select(\DB::raw('
                                                                COUNT(DISTINCT("KgtID")) AS jumlah_kegiatan,
                                                                COALESCE(SUM("Jumlah1"),0) AS jumlah_pagu
                                                            '))
                                            ->where('OrgID',$OrgID)
                                            ->where('trRenja.Status',$status_kegiatan)
                                            ->where('trRenja.EntryLvl',0)
                                            ->get();
                        
                        $jumlah_kegiatan = $renja[0]->jumlah_kegiatan;
                        $jumlah_pagu = $renja[0]->jumlah_pagu;

                        $renja = \DB::table('trRenjaRinc')
                                            ->join('trRenja','trRenja.RenjaID','trRenjaRinc.RenjaID')
                                            ->select(\DB::raw('
                                                                COUNT("PokPirID") AS jumlah_pokir,
                                                                COUNT("UsulanKecID") AS jumlah_usulan_kec
                                                            '))
                                            ->where('OrgID',$OrgID)
                                            ->where('trRenjaRinc.Status',$status_kegiatan)
                                            ->where('trRenjaRinc.EntryLvl',0)
                                            ->get();

                        $jumlah_pokir = $renja[0]->jumlah_pokir;
                        $jumlah_usulan_kec = $renja[0]->jumlah_usulan_kec;

                        if  ($status_kegiatan == 1 || $status_kegiatan == 2)
                        {
                            \DB::table('trRekapPaguIndikatifOPD')
                                    ->where('OrgID',$OrgID)
                                    ->update([
                                                'jumlah_program1'=>$jumlah_program,
                                                'jumlah_kegiatan1'=>$jumlah_kegiatan,
                                                'prarenja1'=>$jumlah_pagu,
                                            ]);
                        }                        
                        
                    break;
                    case 'reportrakorbidang' :
                        $jumlah_program = \DB::table('trRenja')
                                            ->join('tmKgt','trRenja.KgtID','tmKgt.KgtID')
                                            ->where('OrgID',$OrgID)
                                            ->where('EntryLvl',1)
                                            ->where('trRenja.Status',$status_kegiatan)
                                            ->count(\DB::raw('DISTINCT("PrgID")'));

                        $renja = \DB::table('trRenja')
                                            ->join('trRenjaRinc','trRenjaRinc.RenjaID','trRenja.RenjaID')
                                            ->select(\DB::raw('
                                                                COUNT(DISTINCT("KgtID")) AS jumlah_kegiatan,
                                                                COALESCE(SUM("Jumlah2"),0) AS jumlah_pagu
                                                            '))
                                            ->where('OrgID',$OrgID)
                                            ->where('trRenja.Status',$status_kegiatan)
                                            ->where('trRenja.EntryLvl',1)
                                            ->get();
                        
                        $jumlah_kegiatan = $renja[0]->jumlah_kegiatan;
                        $jumlah_pagu = $renja[0]->jumlah_pagu;
                        
                        $renja = \DB::table('trRenjaRinc')
                                            ->join('trRenja','trRenja.RenjaID','trRenjaRinc.RenjaID')
                                            ->select(\DB::raw('
                                                                COUNT("PokPirID") AS jumlah_pokir,
                                                                COUNT("UsulanKecID") AS jumlah_usulan_kec
                                                            '))
                                            ->where('OrgID',$OrgID)
                                            ->where('trRenjaRinc.Status',$status_kegiatan)
                                            ->where('trRenjaRinc.EntryLvl',1)
                                            ->get();

                        $jumlah_pokir = $renja[0]->jumlah_pokir;
                        $jumlah_usulan_kec = $renja[0]->jumlah_usulan_kec;

                        if  ($status_kegiatan == 1 || $status_kegiatan == 2)
                        {
                            \DB::table('trRekapPaguIndikatifOPD')
                                    ->where('OrgID',$OrgID)
                                    ->update([
                                                'jumlah_program2'=>$jumlah_program,
                                                'jumlah_kegiatan2'=>$jumlah_kegiatan,
                                                'rakorbidang1'=>$jumlah_pagu,
                                            ]);
                        }
                        
                    break;
                    case 'reportforumopd' :
                        $jumlah_program = \DB::table('trRenja')
                                            ->join('tmKgt','trRenja.KgtID','tmKgt.KgtID')
                                            ->where('OrgID',$OrgID)
                                            ->where('EntryLvl',2)
                                            ->where('trRenja.Status',$status_kegiatan)
                                            ->count(\DB::raw('DISTINCT("PrgID")'));

                        
                        $renja = \DB::table('trRenja')
                                            ->join('trRenjaRinc','trRenjaRinc.RenjaID','trRenja.RenjaID')
                                            ->select(\DB::raw('
                                                                COUNT(DISTINCT("KgtID")) AS jumlah_kegiatan,
                                                                COALESCE(SUM("Jumlah3"),0) AS jumlah_pagu
                                                            '))
                                            ->where('OrgID',$OrgID)
                                            ->where('trRenja.Status',$status_kegiatan)
                                            ->where('trRenja.EntryLvl',2)
                                            ->get();
                        
                        $jumlah_kegiatan = $renja[0]->jumlah_kegiatan;
                        $jumlah_pagu = $renja[0]->jumlah_pagu;
                        
                        $renja = \DB::table('trRenjaRinc')
                                            ->join('trRenja','trRenja.RenjaID','trRenjaRinc.RenjaID')
                                            ->select(\DB::raw('
                                                                COUNT("PokPirID") AS jumlah_pokir,
                                                                COUNT("UsulanKecID") AS jumlah_usulan_kec
                                                            '))
                                            ->where('OrgID',$OrgID)
                                            ->where('trRenjaRinc.Status',$status_kegiatan)
                                            ->where('trRenjaRinc.EntryLvl',2)
                                            ->get();

                        $jumlah_pokir = $renja[0]->jumlah_pokir;
                        $jumlah_usulan_kec = $renja[0]->jumlah_usulan_kec;
                        
                        if  ($status_kegiatan == 1 || $status_kegiatan == 2)
                        {
                            \DB::table('trRekapPaguIndikatifOPD')
                                    ->where('OrgID',$OrgID)
                                    ->update([
                                                'jumlah_program2'=>$jumlah_program,
                                                'jumlah_kegiatan2'=>$jumlah_kegiatan,
                                                'rakorbidang1'=>$jumlah_pagu,
                                            ]);
                        }

                    break;
                    case 'reportmusrenkab' :
                        $jumlah_program = \DB::table('trRenja')
                                            ->join('tmKgt','trRenja.KgtID','tmKgt.KgtID')
                                            ->where('OrgID',$OrgID)
                                            ->where('EntryLvl',3)
                                            ->where('trRenja.Status',$status_kegiatan)
                                            ->count(\DB::raw('DISTINCT("PrgID")'));

                        $renja = \DB::table('trRenja')
                                            ->join('trRenjaRinc','trRenjaRinc.RenjaID','trRenja.RenjaID')
                                            ->select(\DB::raw('
                                                                COUNT(DISTINCT("KgtID")) AS jumlah_kegiatan,
                                                                COALESCE(SUM("Jumlah4"),0) AS jumlah_pagu
                                                            '))
                                            ->where('OrgID',$OrgID)
                                            ->where('trRenja.Status',$status_kegiatan)
                                            ->where('trRenja.EntryLvl',3)
                                            ->get();
                        
                        $jumlah_kegiatan = $renja[0]->jumlah_kegiatan;
                        $jumlah_pagu = $renja[0]->jumlah_pagu;                        
                        
                        $renja = \DB::table('trRenjaRinc')
                                            ->join('trRenja','trRenja.RenjaID','trRenjaRinc.RenjaID')
                                            ->select(\DB::raw('
                                                                COUNT("PokPirID") AS jumlah_pokir,
                                                                COUNT("UsulanKecID") AS jumlah_usulan_kec
                                                            '))
                                            ->where('OrgID',$OrgID)
                                            ->where('trRenjaRinc.Status',$status_kegiatan)
                                            ->where('trRenjaRinc.EntryLvl',3)
                                            ->get();

                        $jumlah_pokir = $renja[0]->jumlah_pokir;
                        $jumlah_usulan_kec = $renja[0]->jumlah_usulan_kec;

                        if  ($status_kegiatan == 1 || $status_kegiatan == 2)
                        {
                            \DB::table('trRekapPaguIndikatifOPD')
                                    ->where('OrgID',$OrgID)
                                    ->update([
                                                'jumlah_program2'=>$jumlah_program,
                                                'jumlah_kegiatan2'=>$jumlah_kegiatan,
                                                'rakorbidang1'=>$jumlah_pagu,
                                            ]);
                        }
                        
                    break;
                    case 'reportverifikasitapd' :
                        $jumlah_program = \DB::table('trRenja')
                                            ->join('tmKgt','trRenja.KgtID','tmKgt.KgtID')
                                            ->where('OrgID',$OrgID)
                                            ->where('EntryLvl',4)
                                            ->where('trRenja.Status',$status_kegiatan)
                                            ->count(\DB::raw('DISTINCT("PrgID")'));

                        $renja = \DB::table('trRenja')
                                            ->join('trRenjaRinc','trRenjaRinc.RenjaID','trRenja.RenjaID')
                                            ->select(\DB::raw('
                                                                COUNT(DISTINCT("KgtID")) AS jumlah_kegiatan,
                                                                COALESCE(SUM("Jumlah5"),0) AS jumlah_pagu
                                                            '))
                                            ->where('OrgID',$OrgID)
                                            ->where('trRenja.Status',$status_kegiatan)
                                            ->where('trRenja.EntryLvl',4)
                                            ->get();
                        
                        $jumlah_kegiatan = $renja[0]->jumlah_kegiatan;
                        $jumlah_pagu = $renja[0]->jumlah_pagu;
                        
                        $renja = \DB::table('trRenjaRinc')
                                            ->join('trRenja','trRenja.RenjaID','trRenjaRinc.RenjaID')
                                            ->select(\DB::raw('
                                                                COUNT("PokPirID") AS jumlah_pokir,
                                                                COUNT("UsulanKecID") AS jumlah_usulan_kec
                                                            '))
                                            ->where('OrgID',$OrgID)
                                            ->where('trRenjaRinc.Status',$status_kegiatan)
                                            ->where('trRenjaRinc.EntryLvl',4)
                                            ->get();

                        $jumlah_pokir = $renja[0]->jumlah_pokir;
                        $jumlah_usulan_kec = $renja[0]->jumlah_usulan_kec;
                        
                        if  ($status_kegiatan == 1 || $status_kegiatan == 2)
                        {
                            \DB::table('trRekapPaguIndikatifOPD')
                                    ->where('OrgID',$OrgID)
                                    ->update([
                                                'jumlah_program2'=>$jumlah_program,
                                                'jumlah_kegiatan2'=>$jumlah_kegiatan,
                                                'rakorbidang1'=>$jumlah_pagu,
                                            ]);
                        }

                    break;
                }
                $total_pagu_penetapan+=$pagu_penetapan;
                $total_pagu_unit+=$jumlah_pagu;
                if ($jumlah_pagu == $pagu_penetapan)
                {
                    $color='success';
                }
                elseif ($jumlah_pagu < $pagu_penetapan)
                {
                    $color='warning';
                }
                else 
                {
                    $color='danger';
                }
                @endphp
                <tr>
                    <td>
                        {{$k+1}}
                    </td>
                    <td>
                        {{$item->kode_organisasi}}                        
                    </td>
                    <td>
                        {{$item->OrgNm}}
                    </td>                
                    <td>
                        {{$jumlah_program}}
                    </td>                
                    <td>
                        {{$jumlah_kegiatan}}
                    </td>  
                    <td>
                        {{$jumlah_pokir}}
                    </td>             
                    <td>
                        {{$jumlah_usulan_kec}}
                    </td>                      
                    <td class="text-right">
                        <span class="label label-{{$color}} label-rounded" style="text-transform: none">                            
                            {{Helper::formatUang($jumlah_pagu)}}
                        </span>                        
                    </td>  
                    <td class="text-right">
                        <strong>{{Helper::formatUang($pagu_penetapan)}}</strong>
                    </td>                        
                    <td>
                        <ul class="icons-list">
                            <li class="dropdown text-teal-600">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-cog7"></i>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right" style="font-size:8px">
                                    <li>
                                        <a href="{!!route(Helper::getNameOfPage('printtoexcel'),['uuid'=>$item->OrgID])!!}" title="Cetak Laporan">                                        
                                            <i class="icon-printer"></i>                                        
                                            PRINT 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{!!route(Helper::getNameOfPage('printtoexceldetail'),['uuid'=>$item->OrgID])!!}" title="Cetak Laporan">                                        
                                            <i class="icon-printer"></i>                                        
                                            PRINT RINCIAN
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr class="text-center info">
                    <td colspan="10">                   
                        <span class="label label-warning label-rounded" style="text-transform: none">
                            <strong>ORGID:</strong>
                            {{$item->OrgID}}
                        </span>
                    </td>
                </tr>
            @endforeach                    
            </tbody>    
            <tfoot class="bg-info-300">
                <tr>
                    <td class="text-right" colspan="7">
                        <strong>TOTAL</strong>
                    </td>
                    <td class="text-right">
                        <strong>{{Helper::formatUang($total_pagu_unit)}}</strong>
                    </td class="text-right">
                    <td class="text-right">
                        <strong>{{Helper::formatUang($total_pagu_penetapan)}}</strong>
                    </td>
                    <td>&nbsp;</td>
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