<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">       
        <div class="panel-title">
            <h6 class="panel-title">DAFTAR OPD</h6>
        </div>        
    </div>
    @if (count($dataopd) > 0)
    <div class="table-responsive"> 
        <table id="data" class="table table-striped table-hover">
            <thead>
                <tr class="bg-teal-700">
                    <th width="100">                      
                        ID                                     
                    </th>                     
                    <th>                        
                        TA                          
                    </th>
                    <th>                        
                        ORGID                          
                    </th>
                    <th>
                        NAMA OPD / SKPD                        
                    </th>
                    <th>                        
                        SORGID  
                    </th>
                    <th>NAMA UNIT KERJA</th>
                    <th width="100">AKSI</th>
                </tr>
            </thead>
            <tbody>                    
            @foreach ($dataopd as $key=>$item)
                <tr>                   
                    <td>{{$item->useropd}}</td>
                    <td>{{$item->ta}}</td> 
                    <td>{{$item->OrgID}}</td> 
                    <td>{{$item->OrgNm}}</td> 
                    <td>{{$item->SOrgID}}</td> 
                    <td>{{$item->SOrgNm}}</td> 
                    <td>
                        
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
            Belum ada data yang bisa ditampilkan.
        </div>
    </div>   
    @endif            
</div>
