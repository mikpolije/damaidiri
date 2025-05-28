@if ($paginator->hasPages())
    <nav class="mx-auto text-center pt-3">
        <ul class="pagination justify-content-center">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Sebelumnya</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($paginator->lastPage() > 5)
                            @if ($page == 1 || $page == $paginator->lastPage() || ($page >= $paginator->currentPage() - 1 && $page <= $paginator->currentPage() + 1))
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $page }} <span class="sr-only">(current)</span></span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @elseif ($page == $paginator->currentPage() - 2 || $page == $paginator->currentPage() + 2)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @else
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }} <span class="sr-only">(current)</span></span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Selanjutnya</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Selanjutnya</span></li>
            @endif
        </ul>
    </nav>
@endif
