<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link @yield('dashboard')" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Anggota -->
        <li class="nav-item">
            <a class="nav-link @yield('anggota')" href="{{ route('anggota.index') }}">
                <i class="bi bi-people"></i>
                <span>Anggota</span>
            </a>
        </li>

        <!-- Simpanan -->
        <li class="nav-item">
            <a class="nav-link @yield('simpanan')" href="{{ route('simpanan.index') }}">
                <i class="bi bi-piggy-bank"></i>
                <span>Simpanan</span>
            </a>
        </li>

        <!-- Transaksi Simpanan -->
        <li class="nav-item">
            <a class="nav-link @yield('transaksi_simpanan')" href="{{ route('transaksi-simpanan.index') }}">
                <i class="bi bi-cash-coin"></i>
                <span>Transaksi Simpanan</span>
            </a>
        </li>

        <!-- Pinjaman -->
        <li class="nav-item">
            <a class="nav-link @yield('pinjaman')" href="{{ route('pinjaman.index') }}">
                <i class="bi bi-credit-card"></i>
                <span>Pinjaman</span>
            </a>
        </li>

        <!-- Transaksi Pinjaman -->
        <li class="nav-item">
            <a class="nav-link @yield('transaksi')" href="{{ route('transaksi-pinjaman.index') }}">
                <i class="bi bi-arrow-left-right"></i>
                <span>Transaksi Pinjaman</span>
            </a>
        </li>

        <!-- Kas Masuk -->
        <li class="nav-item">
            <a class="nav-link @yield('jurnal_kas_masuk')" href="{{ route('jurnal-kas-masuk.index') }}">
                <i class="bi bi-currency-dollar"></i>
                <span>Kas Masuk</span>
            </a>
        </li>

        <!-- Kas Keluar -->
        <li class="nav-item">
            <a class="nav-link @yield('jurnal_kas_keluar')" href="{{ route('jurnal-kas-keluar.index') }}">
                <i class="bi bi-currency-exchange"></i>
                <span>Kas Keluar</span>
            </a>
        </li>

        @if (auth()->user()->role == 'admin')
        @endif

    </ul>
</aside>
