<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon">
            <img src="{{ asset(setting('logo') ? '/storage/' . setting('logo') : 'dist/img/logo/pusri.webp') }}">
        </div>
        <div class="sidebar-brand-text mx-3">Monitoring Fast Moving</div>
    </a>
    {{-- <hr class="sidebar-divider my-0">
    <!-- Tombol untuk Menampilkan/Menyembunyikan Nav Link -->
    <button class="btn btn-light mb-3" id="toggleNavButton">
        Monitoring Fast Moving
        <i id="toggleIcon" class="fas fa-chevron-down ml-2"></i>
    </button>
    <!-- Wrapper untuk Nav Links, disembunyikan pada tampilan awal -->
    <div id="navLinksWrapper" class="nav-links-collapsed"> --}}
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
    @endrole
    <hr class="sidebar-divider mb-0">
    {{-- @endcan --}}
    {{-- <x-nav-link text="Laporan" icon="file" url="{{ route('admin.laporan.index') }}"
            active="{{ request()->routeIs('admin.laporan.index') ? ' active' : '' }}" /> --}}
    {{-- </div> --}}
</ul>

<!-- CSS untuk Efek Naik-Turun -->
{{-- <style>
    .nav-links-collapsed {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s ease-out;
    }

    .nav-links-expanded {
        max-height: 500px;
        /* Sesuaikan tinggi maksimal sesuai kebutuhan */
        overflow: hidden;
        transition: max-height 0.5s ease-in;
    }
</style>

<!-- JavaScript untuk Mengubah Tampilan Nav Link -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.getElementById('navLinksWrapper');
        const toggleButton = document.getElementById('toggleNavButton');

        // Set default status untuk pertama kali jika tidak ada di localStorage
        if (!localStorage.getItem('navLinksExpanded')) {
            localStorage.setItem('navLinksExpanded', 'false');
        }

        // Cek status dari localStorage saat memuat halaman
        if (localStorage.getItem('navLinksExpanded') === 'true') {
            navLinks.classList.remove('nav-links-collapsed');
            navLinks.classList.add('nav-links-expanded');
        } else {
            navLinks.classList.remove('nav-links-expanded');
            navLinks.classList.add('nav-links-collapsed');
        }

        toggleButton.addEventListener('click', function() {
            if (navLinks.classList.contains('nav-links-collapsed')) {
                navLinks.classList.remove('nav-links-collapsed');
                navLinks.classList.add('nav-links-expanded');
                localStorage.setItem('navLinksExpanded', 'true'); // Simpan status di localStorage
            } else {
                navLinks.classList.remove('nav-links-expanded');
                navLinks.classList.add('nav-links-collapsed');
                localStorage.setItem('navLinksExpanded', 'false'); // Simpan status di localStorage
            }
        });
    });
</script> --}}
