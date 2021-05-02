<span class="text-nowrap">
    @if($data->completed_at == null)
        <button class="btn btn-ghost-success action-process" title="Invoicing Process">
            <i class="fas fa-truck-loading"></i>
        </button>
    @else
        <button class="btn btn-ghost-info action-detail" title="Detail">
            <i class="fas fa-info"></i>
        </button>
    @endif
</span>
