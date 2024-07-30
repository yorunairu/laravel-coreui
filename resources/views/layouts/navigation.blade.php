<ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            {{ __('Dashboard') }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('sales*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
            <i class="nav-icon fas fa-chart-line"></i>
            {{ __('Sales') }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('procurement*') ? 'active' : '' }}" href="{{ route('procurement.index') }}">
            <i class="nav-icon fas fa-shopping-cart"></i>
            {{ __('Procurement') }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('finance*') ? 'active' : '' }}" href="{{ route('finance.index') }}">
            <i class="nav-icon fas fa-dollar-sign"></i>
            {{ __('Finance') }}
        </a>
    </li>

    <li class="nav-group" aria-expanded="false">
        <a class="nav-link nav-group-toggle" href="#">
            <i class="nav-icon fas fa-file-alt"></i>
            {{ __('Report') }}
        </a>
        <ul class="nav-group-items" style="height: 0px;">
            <li class="nav-item">
                <a class="nav-link" href="#" target="_top">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    {{ __('Tender Margin') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" target="_top">
                    <i class="nav-icon fas fa-credit-card"></i>
                    {{ __('Bank Guarantee') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" target="_top">
                    <i class="nav-icon fas fa-check-circle"></i>
                    {{ __('Payment Status') }}
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-group" aria-expanded="false">
        <a class="nav-link nav-group-toggle" href="#">
            <i class="nav-icon fas fa-database"></i>
            {{ __('Master Data') }}
        </a>
        <ul class="nav-group-items" style="height: 0px;">
            <li class="nav-item">
                <a class="nav-link" href="#" target="_top">
                    <i class="nav-icon fas fa-user"></i>
                    {{ __('Customer') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" target="_top">
                    <i class="nav-icon fas fa-cogs"></i>
                    {{ __('Principle') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" target="_top">
                    <i class="nav-icon fas fa-calculator"></i>
                    {{ __('Unit of Measure') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" target="_top">
                    <i class="nav-icon fas fa-money-bill-wave"></i>
                    {{ __('Currency') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" target="_top">
                    <i class="nav-icon fas fa-map-marker-alt"></i>
                    {{ __('Delivery Point') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" target="_top">
                    <i class="nav-icon fas fa-file-alt"></i>
                    {{ __('Term & Condition') }}
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-group" aria-expanded="false">
        <a class="nav-link nav-group-toggle" href="#">
            <i class="nav-icon fas fa-user-shield"></i>
            {{ __('Administrator') }}
        </a>
        <ul class="nav-group-items" style="height: 0px;">
            <li class="nav-item">
                <a class="nav-link" href="#" target="_top">
                    <i class="nav-icon fas fa-users"></i>
                    {{ __('Users') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" target="_top">
                    <i class="nav-icon fas fa-user-lock"></i>
                    {{ __('Roles') }}
                </a>
            </li>
        </ul>
    </li>
</ul>
