@if ($data->hasPages())
    <nav class="pagination-container">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            <li class="page-item {{ $data->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $data->previousPageUrl() }}" rel="prev">&laquo;</a>
            </li>

            {{-- Pagination Elements --}}
            @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                <li class="page-item {{ $data->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Next Page Link --}}
            <li class="page-item {{ !$data->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $data->nextPageUrl() }}" rel="next">&raquo;</a>
            </li>
        </ul>
    </nav>
@endif