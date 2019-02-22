@if ($paginator->hasPages())
<p class="content-group-sm text-muted"> Total {{$paginator->total()}} Record</p>
<ul class="pagination pagination-separated">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <li class="disabled">
            <a href="#">&lsaquo;</a>
        </li>
    @else
        <li>
            <a href="{{ $paginator->previousPageUrl() }}">&lsaquo;</a>
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
            <a href="{{ $paginator->nextPageUrl() }}">&rsaquo;</a>
        </li>
    @else
        <li class="disabled">
            <a href="#">&rsaquo;</a>
        </li>
    @endif        
</ul>
@else
<p class="content-group-sm text-muted"> Total {{$paginator->total()}} Record</p>
<ul class="pagination pagination-separated">
    <li class="disabled">
        <a href="#">&lsaquo;</a>
    </li>
    <li class="active">
        <a href="#">1</a>
    </li>
    <li class="disabled">
        <a href="#">&rsaquo;</a>
    </li>
</ul>
@endif