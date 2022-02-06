<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 {{ Skin::get('sidebar') }} ">
    <!-- Brand Logo -->
    <a href="{{ route('admin') }}" class="brand-link {{ Skin::get('brand') }} ">
        <img src="{{ asset('/assets/admin/img/clavel_512.png') }}" alt="{{ config('app.name') }}"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @include('admin.includes.sidebar-user')

        {{-- <!-- SidebarSearch Form -->
        @include('admin.includes.sidebar-search') --}}

        <!-- Sidebar Menu -->
        @include('admin.includes.sidebar-menu')


    </div>
    <!-- /.sidebar -->
</aside>
