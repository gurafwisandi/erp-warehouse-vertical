<div class="navbar-custom">
    <div class="container-fluid">
        <div id="navigation">
            <ul class="navigation-menu text-center">
                <li class="has-submenu ">
                    <a href="{{ route('dashboard') }}" {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
                        <i class="typcn typcn-home"></i>
                        <span> Dashboard</span>
                    </a>
                </li>
                <li class="has-submenu ">
                    <a href="{{ route('user.index') }}" {{ Request::segment(1) == 'user' ? 'active' : '' }}">
                        <i class="typcn typcn-group"></i>
                        <span> User</span>
                    </a>
                </li>
                {{-- <li class="has-submenu ">
                    <a href="{{ route('supplier.index') }}"
                        {{ Request::segment(1) == 'supplier' ? 'active' : '' }}">
                        <i class="typcn typcn-flag"></i>
                        <span> Vendor</span>
                    </a>
                </li> --}}
                <li class="has-submenu ">
                    <a href="{{ route('item.index') }}" {{ Request::segment(1) == 'item' ? 'active' : '' }}">
                        <i class="typcn typcn-info-large"></i>
                        <span> Item</span>
                    </a>
                </li>
                <li class="has-submenu ">
                    <a href="{{ route('gudang.index') }}" {{ Request::segment(1) == 'gudang' ? 'active' : '' }}">
                        <i class="typcn typcn-flag"></i>
                        <span> Gudang</span>
                    </a>
                </li>
                <li class="has-submenu ">
                    <a href="{{ route('rak.index') }}" {{ Request::segment(1) == 'rak' ? 'active' : '' }}">
                        <i class="typcn typcn-dropbox"></i>
                        <span> Rak</span>
                    </a>
                </li>
                <li class="has-submenu ">
                    <a href="{{ route('receive.index') }}"
                        {{ Request::segment(1) == 'receive' ? 'active' : '' }}">
                        <i class="typcn typcn-download"></i>
                        <span> Penerimaan</span>
                    </a>
                </li>
                <li class="has-submenu ">
                    <a href="{{ route('pengeluaran.index') }}"
                        {{ Request::segment(1) == 'pengeluaran' ? 'active' : '' }}">
                        <i class="typcn typcn-upload"></i>
                        <span> Pengeluaran</span>
                    </a>
                </li>
                <li class="has-submenu ">
                    <a href="#"><i class="typcn typcn-clipboard"></i>Report</a>
                    <ul class="submenu">
                        <li><a href="{{ route('report.penerimaan') }}">Request</a></li>
                        <li><a href="{{ route('report.rep_pengeluaran') }}">Pengeluaran</a></li>
                        <li><a href="{{ route('report.rep_sales') }}">Sales</a></li>
                        <li><a href="{{ route('report.rep_item') }}">Persediaan Item</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>
