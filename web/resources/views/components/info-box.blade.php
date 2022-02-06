<div class="col-lg-4 col-6">
    <!-- small card -->
    <div class="small-box {{ $color }}">
        <div class="inner">
            <h3 id="{{ $id }}">{{ $value }}</h3>

            <p>{{ $slot }}</p>
        </div>
        <div class="icon">
            <i class="{{ $icon }}"></i>
        </div>
    </div>
</div>
<!-- ./col -->
