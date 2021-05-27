<span class="text-nowrap">
    @if($data->verified_at == null)
        @if($verification > 0)
            <button class="btn btn-ghost-success action-verification" title="Verification">
            <i class="fas fa-check"></i>
        </button>
        @endif
        @if($data->user_id == auth()->id())
            <button class="btn btn-ghost-warning action-edit" title="Edit SR">
                <i class="fas fa-edit"></i>
            </button>
        @endif
    @endif
    <button class="btn btn-ghost-info action-info" title="Info">
        <i class="fas fa-info"></i>
    </button>
</span>
