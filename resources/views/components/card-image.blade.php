<div class="col-xl-{{ $size ?? '3' }} col-md-6 mb-4">
    <div class="card h-100">
        <div class="card-body">
            @if (!empty($image))
                <div class="text-center">
                    <img src="{{ asset($image) }}" alt="{{ $alt ?? 'Image' }}" class="img-fluid">
                </div>
            @endif
        </div>
    </div>
</div>
