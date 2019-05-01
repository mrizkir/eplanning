<div class="sidebar sidebar-main sidebar-default sidebar-separate" style="width:330px">
    <div class="sidebar-content" style="margin-right:2px">
        <div class="sidebar-category">
            <div class="category-title">
                <span>MENU DETAIL</span>
                <ul class="icons-list">
                    <li><a href="#" data-action="collapse"></a></li>
                </ul>
            </div>
            <div class="category-content no-padding"> 
                <ul class="navigation navigation-main navigation-accordion">
                    <li>
                        <a href="{{route('usulanmusrenkab.show',['id'=>$renja->RenjaID])}}">
                            <i class="icon-info3"></i><span>DETAIL USULAN KEGIATAN</span>
                        </a>
                    </li> 
                    <li{!!Request::route()->getName()=='usulanmusrenkab.create1' ? ' class="active"':""!!}>
                        <a href="{{route('usulanmusrenkab.create1',['id'=>$renja->RenjaID])}}">
                            <i class="icon-info3"></i><span>INDIKATOR KEGIATAN</span>
                        </a>
                    </li>  
                    <li{!!Request::route()->getName()=='usulanmusrenkab.create2' ? ' class="active"':""!!}>
                        <a href="{{route('usulanmusrenkab.create2',['id'=>$renja->RenjaID])}}">
                            <i class="icon-info3"></i><span>RINCIAN KEGIATAN MUSREN KEC.</span>
                        </a>
                    </li>                   
                    <li{!!Request::route()->getName()=='usulanmusrenkab.create3' ? ' class="active"':""!!}>
                        <a href="{{route('usulanmusrenkab.create3',['id'=>$renja->RenjaID])}}">
                            <i class="icon-info3"></i><span>RINCIAN KEGIATAN POKIR.</span>
                        </a>
                    </li>                   
                    <li{!!Request::route()->getName()=='usulanmusrenkab.create4' ? ' class="active"':""!!}>
                        <a href="{{route('usulanmusrenkab.create4',['id'=>$renja->RenjaID])}}">
                            <i class="icon-info3"></i><span>RINCIAN KEGIATAN OPD.</span>
                        </a>
                    </li>                   
                </ul>
            </div>            
        </div>
    </div>
</div>