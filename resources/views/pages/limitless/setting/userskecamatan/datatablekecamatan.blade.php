<div class="panel panel-flat border-top-lg border-top-info border-bottom-info">
    <div class="panel-heading">       
        <div class="panel-title">
            <h6 class="panel-title">DAFTAR KECAMATAN</h6>
        </div>        
    </div>
    @if (count($datakecamatan) > 0)
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
                        NAMA KECAMATAN                     
                    </th>
                    <th width="100">AKSI</th>
                </tr>
            </thead>
            <tbody>                    
            @foreach ($datakecamatan as $key=>$item)
                <tr>                   
                    <td>{{$item->userkecamatan}}</td>
                    <td>{{$item->ta}}</td> 
                    <td>{{$item->Kd_Kecamatan}}</td> 
                    <td>{{$item->Nm_Kecamatan}}</td> 
                    <td>
                        <ul class="icons-list">
                            <li class="text-danger-600">
                                <a class="btnDeleteKecamatan" href="javascript:;" title="Hapus Data User Kecamatan" data-id="{{$item->userkecamatan}}" data-url="{{route('userskecamatan.index')}}">
                                    <i class='icon-trash'></i>
                                </a> 
                            </li>
                        </ul>
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
