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
                </tr>
            </thead>
            <tbody>                    
            @foreach ($daftar_opd as $k=>$item)
                    @php
                        $OrgID=$item->OrgID;
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
                        @php
                        switch ($page_active)
                        {
                            case 'reportusulanprarenjaopd' :
                                $jumlah_program = \DB::table('trRenja')
                                                    ->join('tmKgt','trRenja.KgtID','tmKgt.KgtID')
                                                    ->where('OrgID',$OrgID)
                                                    ->where('EntryLvl',0)
                                                    ->count(\DB::raw('DISTINCT("PrgID")'));

                                \DB::table('trRekapPaguIndikatifOPD')
                                        ->where('OrgID',$OrgID)
                                        ->update('jumlah_program1',$jumlah_program1);
                            break;
                        }
                        @endphp
                        {{$jumlah_program}}
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