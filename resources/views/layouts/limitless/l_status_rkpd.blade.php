@switch($item->Status)
    @case(1)
    <span class="label label-success label-flat border-success text-success-600">
        {{HelperKegiatan::getStatusRKPD($item->Status)}}
    </span>
    @break
    @case(2)
    <span class="label label-danger label-flat border-danger text-danger-600">
        {{HelperKegiatan::getStatusRKPD($item->Status)}}
    </span>
    @break    
    @default
        <span class="label label-default label-flat border-default text-info-600">
            N.A
        </span>
@endswitch