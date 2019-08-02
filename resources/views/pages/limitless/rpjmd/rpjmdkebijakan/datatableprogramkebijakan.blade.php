<div class="panel-heading">
    <div class="panel-title">
        <h5>DAFTAR PROGRAM KEBIJAKAN</h5>
    </div>
    <div class="heading-elements">
        
    </div>
</div>
@if (count($dataprogramkebijakan) > 0)
<div class="table-responsive"> 
    <table id="data" class="table table-striped table-hover" style="font-size:11px">
        <thead>
            <tr class="bg-teal-700">
                <th width="55">NO</th>     
                <th>NAMA PROGRAM</th>                
                <th>NAMA URUSAN</th>                                
                <th width="70">TA</th>                                     
                <th width="100">AKSI</th>
            </tr>
        </thead>
        <tbody>                    
        @foreach ($dataprogramkebijakan as $key=>$item)
            <tr>
                <td>
                    {{ $key + 1 }}    
                </td>
                <td>{{$item->PrgNm}}</td>
                <td>{{$item->Nm_Bidang}}</td>                                        
                <td>{{$item->TA}}</td>                                    
                <td>                    
                    <ul class="icons-list">
                        <li class="text-danger-600">
                            <a class="btnDelete" href="javascript:;" title="Hapus Data Indikator" data-id="{{$item->ProgramKebijakanID}}" data-url="{{route(Helper::getNameOfPage('index'))}}">
                                <i class='icon-trash'></i>
                            </a> 
                        </li>
                    </ul>                    
                </td>
            </tr>
            <tr class="text-center info">
                <td colspan="11">
                    <span class="label label-warning label-rounded" style="text-transform: none">
                        <strong>PROGRAMKEBIJAKANID:</strong>
                        {{$item->ProgramKebijakanID}}
                    </span>
                    <span class="label label-warning label-rounded">
                        <strong>KET:</strong>
                        {{empty($item->Descr)?'-':$item->Descr}}
                    </span>
                    <span class="label label-warning label-rounded" style="text-transform: none">
                        <strong>CREATED:</strong>
                        {{Helper::tanggal('d/m/Y H:m',$item->created_at)}}
                    </span>
                    <span class="label label-warning label-rounded" style="text-transform: none">
                        <strong>UPDATED:</strong>
                        {{Helper::tanggal('d/m/Y H:m',$item->updated_at)}}
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
        Belum ada data yang bisa ditampilkan.
    </div>
</div>   
@endif               