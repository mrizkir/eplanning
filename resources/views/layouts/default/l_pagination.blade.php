@if ($paginator->hasPages())
<div class="row" id="paginations">
    <div class="col-md-5">
        <ul class="pagination">
            <li>{{$paginator->total()}} Total Records  </li>
        </ul>
                              
    </div>
    <div class="col-md-7 text-right">
        <ul class="pagination pagination-separated pagination-rounded">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled">
                    <a href="#">Prev</a>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}">Prev</a>
                </li>
            @endif
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                <li class="disabled">
                    <a href="#">{{ $element }}</a>
                </li>
                @endif
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active">
                                <a href="#">{{ $page }}</a>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach   
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}">Next</a>
                </li>
            @else
                <li class="disabled">
                    <a href="#">Next</a>
                </li>
            @endif                 
        </ul>        
    </div>
</div>   
@else
<div class="row" id="paginations">
    <div class="col-md-5">
        {{$paginator->total()}} Total Records                        
    </div>
    <div class="col-md-7 text-right">
        <ul class="pagination pagination-separated pagination-rounded">
            <li class="disabled">
                <a href="#">Prev</a>
            </li>
            <li class="active">
                <a href="#">1</a>
            </li>
            <li class="disabled">
                <a href="#">Next</a>
            </li>
        </ul>
    </div>
</div>
@endif