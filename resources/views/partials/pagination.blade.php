{{--
    Pagination situs (album galeri, laporan keuangan). Dipakai otomatis oleh ->links();
    label bisa diganti, mis. ->links(null, ['label' => 'album']).
--}}
@php $label = $label ?? 'data'; @endphp

@if ($paginator->hasPages())
    <nav class="pager" aria-label="Navigasi halaman {{ $label }}">
        <p class="pager-summary">
            Menampilkan <strong>{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</strong>
            dari <strong>{{ $paginator->total() }}</strong> {{ $label }}
        </p>

        <ul class="pager-list">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="pager-link pager-step is-disabled" aria-disabled="true">
                        <span aria-hidden="true">‹</span><span class="pager-step-text">Sebelumnya</span>
                    </span>
                @else
                    <a class="pager-link pager-step" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <span aria-hidden="true">‹</span><span class="pager-step-text">Sebelumnya</span>
                    </a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="pager-link pager-gap" aria-hidden="true">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="pager-link is-active" aria-current="page">
                                    <span class="sr-only">Halaman </span>{{ $page }}
                                </span>
                            @else
                                <a class="pager-link" href="{{ $url }}" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <a class="pager-link pager-step" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <span class="pager-step-text">Berikutnya</span><span aria-hidden="true">›</span>
                    </a>
                @else
                    <span class="pager-link pager-step is-disabled" aria-disabled="true">
                        <span class="pager-step-text">Berikutnya</span><span aria-hidden="true">›</span>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
