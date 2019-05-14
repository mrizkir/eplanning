<div class="panel-heading">
    <div class="panel-title">
        <h5>DAFTAR RINCIAN KEGIATAN</h5>
    </div>
    <div class="heading-elements">        
        @if (Request::route()->getName()=='usulanforumopd.show')
        <div class="heading-btn">
            <a href="{!!route('usulanforumopd.create2',['id'=>$renja->RenjaID])!!}" class="btn btn-info btn-xs" title="Tambah Rincian Kegiatan dari Musren">
                <i class="icon-googleplus5"></i>
            </a>
            <a href="{!!route('usulanforumopd.create3',['id'=>$renja->RenjaID])!!}" class="btn btn-success btn-xs" title="Tambah Rincian Kegiatan dari POKIR">
                <i class="icon-googleplus5"></i>
            </a>
            <a href="{!!route('usulanforumopd.create4',['id'=>$renja->RenjaID])!!}" class="btn btn-primary btn-xs" title="Tambah Rincian Kegiatan">
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
                <th class="text-right">NILAI USULAN</th>                
                <th>PRIORITAS</th> 
                <th>STATUS</th>                                        
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
                <td>{{Helper::formatAngka($item->Sasaran_Angka3)}} {{ucwords($item->Sasaran_Uraian3)}}</td>
                <td>{{$item->Target3}}</td>               
                <td class="text-right">{{Helper::formatUang($item->Jumlah3)}}</td>       
                <td>
                    <span class="label label-flat border-success text-success-600">
                        {{HelperKegiatan::getNamaPrioritas($item->Prioritas)}}
                    </span>
                </td>
                <td>
                    @include('layouts.limitless.l_status_kegiatan')
                </td>
                <td>
                    @if ($item->Privilege==0)
                    <ul class="icons-list">
                        <li class="text-primary-600">
                            @if ($item->isSKPD)
                                <a class="btnEdit" href="{{route('usulanforumopd.edit4',['id'=>$item->RenjaRincID])}}" title="Ubah Data Usulan Forum OPD / SKPD">
                                    <i class='icon-pencil7'></i>
                                </a> 
                            @elseif($item->isReses)
                                <a class="btnEdit" href="{{route('usulanforumopd.edit3',['id'=>$item->RenjaRincID])}}" title="Ubah Data Usulan Forum OPD / SKPD">
                                    <i class='icon-pencil7'></i>
                                </a>
                            @else
                                <a class="btnEdit" href="{{route('usulanforumopd.edit2',['id'=>$item->RenjaRincID])}}" title="Ubah Data Usulan Forum OPD / SKPD">
                                    <i class='icon-pencil7'></i>
                                </a>
                            @endif
                        </li>
                        <li class="text-danger-600">
                            <a class="btnDelete" href="javascript:;" title="Hapus Data Idikator" data-id="{{$item->RenjaRincID}}" data-url="{{route('usulanforumopd.index')}}">
                                <i class='icon-trash'></i>
                            </a> 
                        </li>
                    </ul>
                    @else
                        <span class="label label-success label-flat text-success-600">
                            TRANSFERED
                        </span>
                    @endif
                </td>
            </tr>
            <tr class="text-center info">
                <td colspan="10">                   
                    <span class="label label-warning label-rounded">
                        <strong>RenjaRincID:</strong>
                        {{$item->RenjaRincID}}
                    </span>
                </td>
            </tr>
        @endforeach                    
        </tbody>
        <tfoot>
            <tr class="bg-grey-300" style="font-weight:bold">
                <td colspan="4" class="text-right">TOTAL</td>
                <td class="text-right">{{Helper::formatUang($datarinciankegiatan->sum('Jumlah3'))}}</td> 
                <td colspan="3"></td>
            </tr>
        </tfoot>
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