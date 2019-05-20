<li class="dropdown text-teal-600">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="icon-cog7"></i>
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu dropdown-menu-right" style="font-size:8px">
        @if ($item->Privilege==0)
        <li class="dropdown-header">Ubah Status</li>
        @if ($item->Status==0)
        <li class="active">
            <a href="#">                                        
                <i class="icon-checkmark"></i>                                        
                DRAFT
            </a>
        </li>
        @else
        <li>
            <a href="#" class="ubahstatus" data-status="0" data-id="{{$item->RenjaRincID}}">                                        
                <i class="icon-cross"></i>                                        
                DRAFT
            </a>
        </li>
        @endif    

        @if ($item->Status==1)
        <li class="active">
            <a href="#">                                        
                <i class="icon-checkmark"></i>                                        
                SETUJU
            </a>
        </li>
        @else
        <li>
            <a href="#" class="ubahstatus" data-status="1" data-id="{{$item->RenjaRincID}}">                                        
                <i class="icon-cross"></i>                                        
                SETUJU
            </a>
        </li>
        @endif

        @if ($item->Status==2)
        <li class="active">
            <a href="#">                                        
                <i class="icon-checkmark"></i>                                        
                SETUJU CATATAN
            </a>
        </li>
        @else
        <li>
            <a href="#" class="ubahstatus" data-status="2" data-id="{{$item->RenjaRincID}}">                                        
                <i class="icon-cross"></i>                                        
                SETUJU CATATAN
            </a>
        </li>
        @endif

        @if ($item->Status==3)
        <li class="active">
            <a href="#">                                        
                <i class="icon-checkmark"></i>                                        
                PENDING
            </a>
        </li>
        @else
        <li>
            <a href="#" class="ubahstatus" data-status="3" data-id="{{$item->RenjaRincID}}">                                        
                <i class="icon-cross"></i>                                        
                PENDING
            </a>
        </li>
        @endif                                         
        <li class="dropdown-header">AKSI</li>        
        @if ($item->Privilege==0)              
        <li class="text-primary-600">        
            <a class="btnEdit" href="{{route($page_active.'.edit',['id'=>$item->RenjaRincID])}}" title="Ubah Data Usulan">
                <i class='icon-pencil7'></i> UBAH RINCIAN
            </a>             
        </li> 
        @endif        
        <li>                                    
            @if ($item->Status==1 || $item->Status==2)
                <a href="#" title="TRANSFER KEG. KE {{$label_transfer}} " id="btnTransfer" data-id="{{$item->RenjaRincID}}" title="{{$label_transfer}}">
                    <i class="icon-play4"></i> TRANSFER KE {{$label_transfer}}
                </a>
            @else
                <a href="#" onclick="event.preventDefault()" title="{{$label_transfer}}">
                    <i class="icon-stop"></i> TRANSFER KE {{$label_transfer}}
                </a>
            @endif
        </li> 
        @else 
        <li class="active">
            <a href="#" onclick="event.preventDefault()" title="{{$label_transfer}}">
                <i class="icon-checkbox-checked2"></i> TRANSFERED
            </a>        
        </li>          
        @endif 
        <li class="text-primary-600">        
            <a class="btnEdit" href="{{route($page_active.'.show',['id'=>$item->RenjaRincID])}}" title="Detail Data Usulan">
                <i class='icon-eye'></i> DETAIL RINCIAN
            </a>             
        </li> 
        <li>
            <a href="#"  title="{{$label_transfer}}" class="btnHistoriRenja" data-url="{{route('historirenja.onlypagu',['uuid'=>$item->RenjaRincID])}}" >                                        
                <i class="icon-history"></i>                                        
                HISTORY
            </a>
        </li>                              
    </ul>
</li>