@if ($paginator->hasPages())
  <nav class="pagination with-shadow is-rounded is-centered" role="navigation" aria-label="pagination">
  {{-- Previous Page Link --}}
  @if (!$paginator->onFirstPage())
    <a href="{{ $paginator->previousPageUrl() }}" class="pagination-previous">Previous</a>
  @endif

  {{-- Next Page Link --}}
  @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" class="pagination-next">Next page</a>
  @endif

  <ul class="pagination-list">
    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
      {{-- "Three Dots" Separator --}}
      @if (is_string($element))
        <li>
          <span class="pagination-ellipsis">{{$element}}</span>
        </li>
      @endif

      {{-- Array Of Links --}}
      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <li>
              <a class="pagination-link is-current" aria-label="Page {{ $page }}" aria-current="page">{{  $page   }}</a>
            </li>
          @else
            <li>
              <a href="{{ $url }}" class="pagination-link" aria-label="Goto page {{ $page }}">{{ $page }}</a>
            </li>
          @endif
        @endforeach
      @endif
    @endforeach
  </ul>
</nav>
@endif