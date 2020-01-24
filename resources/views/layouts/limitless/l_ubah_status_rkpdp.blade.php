<li class="dropdown text-teal-600">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="icon-cog7"></i>
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu dropdown-menu-right" style="font-size:8px">
        @if ($item->Privilege==0)        
        <li class="dropdown-header">AKSI</li>        
        @if ($item->Privilege==0)              
        <li class="text-primary-600">        
            <a class="btnEdit" href="{{route($page_active.'.edit',['uuid'=>$item->RKPDRincID])}}" title="Ubah Data Usulan">
                <i class='icon-pencil7'></i> UBAH RINCIAN
            </a>             
        </li> 
        @endif        
        <li>                                    
            @if ($item->Status==1 || $item->Status==2)
                <a href="#" title="TRANSFER KEG. KE {{$label_transfer}} " id="btnTransfer" data-id="{{$item->RKPDRincID}}" title="{{$label_transfer}}">
                    <i class="icon-play4"></i> TRANSFER KE {{$label_transfer}}
                </a>
            @else
                <a href="#" onclick="event.preventDefault()" title="{{$label_transfer}}">
                    <i class="icon-stop"></i> TRANSFER KE {{$label_transfer}}
                </a>
            @endif
        </li>             
        @endif
         <li class="text-primary-600">        
            <a href="{{route($page_active.'.show',['uuid'=>$item->RKPDID])}}" title="Detail Data Usulan">
                <i class='icon-eye'></i> DETAIL KEGIATAN
            </a>             
        </li> 
        <li class="text-primary-600">        
            <a href="{{route($page_active.'.showrincian',['uuid'=>$item->RKPDRincID])}}" title="Detail Data Rincian Usulan">
                <i class='icon-eye'></i> DETAIL RINCIAN
            </a>             
        </li> 
        <li>
            <a href="#"  title="{{$label_transfer}}" class="btnHistoriRKPD" data-url="{{route('historirenja.onlypagu',['uuid'=>$item->RKPDRincID])}}" >                                        
                <i class="icon-history"></i>                                        
                HISTORY
            </a>
        </li>                              
    </ul>
</li>