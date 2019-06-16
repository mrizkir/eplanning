<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">       
        <div class="panel-title">
            <h6 class="panel-title">DAFTAR ANGGOTA DEWAN</h6>
        </div>        
    </div>
    @if (count($datadewan) > 0)
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
                        KODE                   
                    </th>
                    <th>
                        NAMA ANGGOTA                     
                    </th>
                    <th width="100">AKSI</th>
                </tr>
            </thead>
            <tbody>                    
            @foreach ($datadewan as $key=>$item)
                <tr>                   
                    <td>{{$item->userdewan}}</td>
                    <td>{{$item->ta}}</td> 
                    <td>{{$item->Kd_PK}}</td> 
                    <td>{{$item->NmPk}}</td> 
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
