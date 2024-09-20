<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon">
            <img src="{{ asset(setting('logo') ? '/storage/' . setting('logo') : 'dist/img/logo/pusri.webp') }}">
        </div>
        <div class="sidebar-brand-text mx-3">Monitoring Fast Moving</div>
    </a>
    <hr class="sidebar-divider mb-0">
    <x-nav-link text="Dashboard" icon="tachometer-alt" url="{{ route('admin.dashboard') }}"
        active="{{ request()->routeIs('admin.dashboard') ? ' active' : '' }}" />
    <hr class="sidebar-divider mb-0">
    <x-nav-link text="Status Running Material" icon="box" url="{{ route('admin.status-running-material.index') }}"
        active="{{ request()->routeIs('admin.status-running-material.index') ? ' active' : '' }}" />
    <hr class="sidebar-divider mb-0">
    <x-nav-link text="PR / PO Outstanding" icon="box" url="{{ route('admin.pr-po-outstanding.index') }}"
        active="{{ request()->routeIs('admin.pr-po-outstanding.index') ? ' active' : '' }}" />
    <hr class="sidebar-divider mb-0">
    <x-nav-link text="Kontrak" icon="box" url="{{ route('admin.kontrak.index') }}"
        active="{{ request()->routeIs('admin.kontrak.index') ? ' active' : '' }}" />
    <hr class="sidebar-divider mb-0">
    @role('Admin')
        <x-nav-link text="Daftar Petugas" icon="users" url="{{ route('admin.member') }}"
            active="{{ request()->routeIs('admin.member') ? ' active' : '' }}" />
        <hr class="sidebar-divider mb-0">
    @endrole
</ul>
