<div class="panel-heading">
    <div class="panel-title">
        <h5>DAFTAR RINCIAN KEGIATAN</h5>
    </div>
    <div class="heading-elements">        
        @if (Request::route()->getName()=='usulanrakorbidang.show')
        <div class="heading-btn">
            <a href="{!!route('usulanrakorbidang.create2',['id'=>$renja->RenjaID])!!}" class="btn btn-info btn-xs" title="Tambah Rincian Kegiatan dari Musren">
                <i class="icon-googleplus5"></i>
            </a>
            <a href="{!!route('usulanrakorbidang.create3',['id'=>$renja->RenjaID])!!}" class="btn btn-success btn-xs" title="Tambah Rincian Kegiatan dari POKIR">
                <i class="icon-googleplus5"></i>
            </a>
            <a href="{!!route('usulanrakorbidang.create4',['id'=>$renja->RenjaID])!!}" class="btn btn-primary btn-xs" title="Tambah Rincian Kegiatan">
                <i class="icon-googleplus5"></i>
            </a>
        </div>  
        @endif  
    </div>
</div>
@if (count($datarinciankegiatan) > 0)
<div class="table-responsive"> 
    <table id="data" class="table table-striped table-hover">
        <thead>
            <tr class="bg-teal-700">
                <th width="55">NO</th>     
                <th>NAMA URAIAN</th>                
                <th>SASARAN KEGIATAN</th>  
                <th>TARGET (%)</th> 
                <th>NILAI USULAN</th>                
                <th>PRIORITAS</th>                                         
                <th width="120">AKSI</th>
            </tr>
        </thead>
        <tbody>                    
        @foreach ($datarinciankegiatan as $key=>$item)
            <tr>
                <td>
                    {{$item->No}}
                </td>
                <td>
                    {{ucwords($item->Uraian)}}<br />
                    <span class="label label-flat border-grey text-grey-600">                        
                        @if ($item->isSKPD)
                            <a href="#">
                                <strong>Usulan dari: </strong>OPD / SKPD
                            </a> 
                        @elseif($item->isReses)
                            <a href="#">
                                <strong>Usulan dari: </strong>POKIR [{{$item->isReses_Uraian}}]
                            </a>
                        @else
                            <a href="{{route('aspirasimusrenkecamatan.show',['id'=>$item->UsulanKecID])}}">
                                <strong>Usulan dari: MUSREN. KEC. {{$item->Nm_Kecamatan}}
                            </a>
                        @endif
                    </span>
                </td>                
                <td>{{Helper::formatAngka($item->Sasaran_Angka2)}} {{ucwords($item->Sasaran_Uraian2)}}</td>
                <td>{{$item->Target2}}</td>               
                <td>{{Helper::formatUang($item->Jumlah2)}}</td>       
                <td>
                    <span class="label label-flat border-success text-success-600">
                        {{HelperKegiatan::getNamaPrioritas($item->Prioritas)}}
                    </span>
                </td>
                <td>
                    <ul class="icons-list">
                        <li class="text-primary-600">
                            @if ($item->isSKPD)
                                <a class="btnEdit" href="{{route('usulanrakorbidang.edit4',['id'=>$item->RenjaRincID])}}" title="Ubah Data Usulan Rakor Bidang">
                                    <i class='icon-pencil7'></i>
                                </a> 
                            @elseif($item->isReses)
                                <a class="btnEdit" href="{{route('usulanrakorbidang.edit3',['id'=>$item->RenjaRincID])}}" title="Ubah Data Usulan Rakor Bidang">
                                    <i class='icon-pencil7'></i>
                                </a>
                            @else
                                <a class="btnEdit" href="{{route('usulanrakorbidang.edit2',['id'=>$item->RenjaRincID])}}" title="Ubah Data Usulan Rakor Bidang">
                                    <i class='icon-pencil7'></i>
                                </a>
                            @endif
                        </li>
                        <li class="text-danger-600">
                            <a class="btnDelete" href="javascript:;" title="Hapus Data Idikator" data-id="{{$item->RenjaRincID}}" data-url="{{route('usulanrakorbidang.index')}}">
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