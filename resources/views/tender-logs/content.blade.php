
<div class="modal-header">
    <h5 class="modal-title" id="tenderLogModalLabel">History Tender {{ $return['data']->name }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <ul class="timeline">
    @foreach ($return['data']->tenderLogs as $key => $item)
        <li class="timeline-item">
            <span class="timeline-date">{{ $item->created_at }}
                @if ($loop->first)
                    <span class="blink-dot"></span>
                @endif
            </span>
            <span class="timeline-content">{{ $item->description }}</span>
        </li>
    @endforeach
    </ul>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>