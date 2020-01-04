<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="table-responsive"> 
         <table id="data" class="table table-striped table-hover">
            <thead>
                <tr class="bg-teal-700">
                    <th width="55">NO</th>
                    <th>
                       JENIS DATA                                       
                    </th>                                        
                    <th width="100">AKSI</th>
                </tr>
            </thead>
            <tbody>                    
                <tr>
                    <td>1</td>
                    <td>Wilayah (Provinsi, Kabupaten/Kota, Kecamatan, Desa/Kelurahan)</td>
                    <td>
                        <ul class="icons-list">
                            <li class="text-primary-600">
                                <a class="btnShow" href="{{route('copydata.copy',['id'=>1])}}" title="Copy Data">
                                    <i class='icon-copy3'></i>
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>                
            </tbody>
         </table>
    </div>
</div>
