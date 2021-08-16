@php
  $link_limit = 7;
@endphp
@if ($data->lastPage() > 1)
  <div class="card-footer clearfix">
    <ul class="pagination pagination-sm m-0 float-right">
      <li class="page-item {{ ($data->currentPage() == 1) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ $data->previousPageUrl() }}">«</a>
      </li>
      @for ($i = 1; $i <= $data->lastPage(); $i++)
        @php
          $half_total_links = floor($link_limit / 2);
          $from = $data->currentPage() - $half_total_links;
          $to = $data->currentPage() + $half_total_links;
          if ($data->currentPage() < $half_total_links) {
            $to += $half_total_links - $data->currentPage();
          }
          if ($data->lastPage() - $data->currentPage() < $half_total_links) {
              $from -= $half_total_links - ($data->lastPage() - $data->currentPage()) - 1;
          }
        @endphp
        @if ($from < $i && $i < $to)
          <li class="page-item {{ ($data->currentPage() == $i) ? ' active' : '' }}">
            <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
          </li>
        @endif
      @endfor
      {{-- <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li> --}}

      <li class="page-item {{ ($data->currentPage() == $data->lastPage()) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ $data->nextPageUrl() }}">»</a>
      </li>
    </ul>
  </div>  
@endif