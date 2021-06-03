<span class="text-nowrap">
    @if($data->status == null)
        <button class="btn btn-ghost-warning action-primary" title="Set Primary">
            <i class="fas fa-check"></i>
        </button>
    @endif
    <button class="btn btn-ghost-danger action-delete" title="Delete">
        <i class="fas fa-times"></i>
    </button>
</span>
