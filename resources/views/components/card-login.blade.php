<div class="col-12 col-md-6 mb-4">
    <div class="card" style="{{ $style ?? '' }}">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ $title ?? '' }}</h6>
            {{ $option ?? '' }}
        </div>
        <div class="card-body">
            {{ $slot }}
        </div>
    </div>
</div>
