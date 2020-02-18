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
                            JUMLAH KEGIATAN MUSREN
                        </a>                                             
                    </th>                                          
                    <th width="120">AKSI</th>
                </tr>
            </thead>
            <tbody>                    
            @foreach ($daftar_opd as $k=>$item)                
                @php
                $OrgID=$item->OrgID;
                $jumlah_musren = \DB::table('trUsulanKec')
                                ->where('OrgID',$OrgID)
                                ->count('UsulanKecID');
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
                        {{$jumlah_musren}}
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
                                        <a href="{!!route('reportkegiatanmusrenkecopd.printtoexcel',['uuid'=>$item->OrgID])!!}" title="Cetak Laporan">                                        
                                            <i class="icon-printer"></i>                                        
                                            PRINT 
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