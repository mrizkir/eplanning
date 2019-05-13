<li class="dropdown text-teal-600">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="icon-cog7"></i>
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu dropdown-menu-right">
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
                SETUJUI
            </a>
        </li>
        @else
        <li>
            <a href="#" class="ubahstatus" data-status="1" data-id="{{$item->RenjaRincID}}">                                        
                <i class="icon-cross"></i>                                        
                SETUJUI
            </a>
        </li>
        @endif

        @if ($item->Status==2)
        <li class="active">
            <a href="#">                                        
                <i class="icon-checkmark"></i>                                        
                SETUJUI CATATAN
            </a>
        </li>
        @else
        <li>
            <a href="#" class="ubahstatus" data-status="2" data-id="{{$item->RenjaRincID}}">                                        
                <i class="icon-cross"></i>                                        
                SETUJUI CATATAN
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
        <li class="dropdown-header">TRANSFER</li>
        <li>                                    
            @if ($item->Status==1 || $item->Status==2)
                <a href="#" title="TRANSFER KEG. KE {{$label_transfer}} " id="btnTransfer" data-id="{{$item->RenjaRincID}}">
                    <i class="icon-play4"></i> {{$label_transfer}}
                </a>
            @else
                <a href="#" onclick="event.preventDefault()">
                    <i class="icon-stop"></i> {{$label_transfer}}
                </a>
            @endif
        </li>         
        <li>
            <a href="#" onclick="event.preventDefault()">                                        
                <i class="icon-history"></i>                                        
                HISTORY
            </a>
        </li> 
        @else 
        <li class="dropdown-header">TRANSFER</li>
        <li>
            <a href="#" onclick="event.preventDefault()">
                <i class="icon-checkbox-checked2"></i> {{$label_transfer}}
            </a>        
        </li>  
        <li>
            <a href="#" onclick="event.preventDefault()">                                        
                <i class="icon-history"></i>                                        
                HISTORY
            </a>
        </li> 
        @endif                              
    </ul>
</li>