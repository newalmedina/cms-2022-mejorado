<section class="page-header page-header-classic">
    <div class="container">
        <div class="row">
            <div class="col">
                <ul class="breadcrumb">
                    <li><a href="{{ url("/") }}">Home</a></li>
                    @section('breadcrumb')
                    @show
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col p-static">
                <h1 data-title-border>{{ $page_title }}</h1>

            </div>
        </div>
    </div>
</section>
