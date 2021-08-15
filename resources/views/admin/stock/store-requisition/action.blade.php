<div class="btn-group text-nowrap">
    <button type="button" class="btn btn-info action-info"><i class="fas fa-info mr-2"></i> Info</button>
    @if($data->verified_at == null)
        <button type="button" class="btn btn-outline-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            @if($verification > 0)
                <button class="dropdown-item action-verification"><i class="fas fa-check text-info mr-2"></i> Verifikasi</button>
            @endif
            @if($data->user_id == auth()->id())
                <button class="dropdown-item action-edit"><i class="fas fa-edit text-info mr-2"></i> Edit</button>
            @endif
            <div class="dropdown-divider"></div>
            <button class="dropdown-item action-delete"><i class="fas fa-times text-danger mr-2"></i> Hapus SR</button>
        </div>
    @endif
</div>
