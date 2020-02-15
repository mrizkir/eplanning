<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">    
    @if (count($daftar_opd) > 0)
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
                            TOTAL PAGU
                        </a>                                             
                    </th>                           
                </tr>
            </thead>
            <tbody>                    
            @foreach ($daftar_opd as $k=>$item)                
                @php
                $OrgID=$item->OrgID;
                switch ($page_active)
                {
                    case 'reportusulanprarenjaopd' :
                        $jumlah_program = \DB::table('trRenja')
                                            ->join('tmKgt','trRenja.KgtID','tmKgt.KgtID')
                                            ->where('OrgID',$OrgID)
                                            ->where('EntryLvl',0)
                                            ->count(\DB::raw('DISTINCT("PrgID")'));

                        $renja = \DB::table('trRenja')
                                            ->select(\DB::raw('
                                                                COUNT("KgtID") AS jumlah_kegiatan,
                                                                COALESCE(SUM("NilaiUsulan1"),0) AS jumlah_pagu
                                                            '))
                                            ->where('OrgID',$OrgID)
                                            ->where('EntryLvl',0)
                                            ->get();
                        
                        $jumlah_kegiatan = $renja[0]->jumlah_kegiatan;
                        $jumlah_pagu = $renja[0]->jumlah_pagu;
                        
                        \DB::table('trRekapPaguIndikatifOPD')
                                ->where('OrgID',$OrgID)
                                ->update([
                                            'jumlah_program1'=>$jumlah_program,
                                            'jumlah_kegiatan1'=>$jumlah_kegiatan,
                                            'prarenja1'=>$jumlah_pagu,
                                        ]);
                    break;
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
                        {{Helper::formatUang($jumlah_pagu)}}
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
            Belum ada data yang bisa ditampilkan. Mohon pilih terlebih dahulu OPD dan Unit Kerja
        </div>
    </div>   
    @endif            
</div>