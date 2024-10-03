<div class="left-bar" id="sidebar">
    <div class="nav-container" id="nav-container-id" style="display: flex;">
        <nav class="nav-class">
            <ul>
                <div class="list-dashboard-container {{ request()->is('dashboard') ? 'active' : '' }}">
                    <div class="dboard-icon-container">
                        @include('svg.dashboard-icon')
                    </div>
                    <div class="action-dashboard-container">
                        <li><a href="/dashboard">Dashboard</a></li>
                    </div>
                </div>

                @can('submit', Auth::user())
                    <button class="button-library" id="indicators-dropdown">
                        <div class="list-library-container">
                            <div class="library-icon-container">
                                @include('svg.indicators-icon')
                            </div>
                            <div class="action-library-container">
                                Indicators
                            </div>
                            <div id="indicators-dropdown-icon" class="dropdown-icon-container">
                                @include('svg.dropdown-icon')
                            </div>
                        </div>
                    </button>

                    @php
                        $indicatorsContent = [
                            'Secondary Indicators' => '/indicators/secondary',
                        ];
                    @endphp

                    <div id="indicators-content" class="sublibrary-container">
                        <ul>
                            <div class="list-item-container">
                                <div class="hyphen-container">
                                    <div class="circle"></div>
                                </div>
                                <li><a href={{route('primaryIndicators.index') }}>Primary Indicators</a></li>
                            </div>
                            @foreach ($indicatorsContent as $label => $action)
                            <div class="list-item-container">
                                <div class="hyphen-container">
                                    <div class="circle"></div>
                                </div>
                                <li><a href={{ $action }}>{{ $label }}</a></li>
                            </div>
                            @endforeach
                            @can('library', Auth::user())
                                <div class="list-item-container">
                                    <div class="hyphen-container">
                                        <div class="circle"></div>
                                    </div>
                                    <li><a href={{route('primaryIndicators.index') }}>Monitoring Sheet</a></li>
                                </div>
                            @endcan
                        </ul>
                    </div>
                    <div class="list-dashboard-container {{ request()->is('dashboard') ? 'active' : '' }}">
                        <div class="dboard-icon-container">
                            @include('svg.dashboard-icon')
                        </div>
                        <div class="action-dashboard-container">
                            <li><a href="{{ route('agencyIndicators.index') }}">Agency Indicators</a></li>
                        </div>
                    </div>
                @endcan


                @can('approve', Auth::user())
                    <div class="list-dashboard-container {{ request()->is('primaryIndicators.pendingAdmin') ? 'active' : '' }}">
                        <div class="dboard-icon-container">
                            @include('svg.indicators-icon')
                        </div>
                        <div class="action-dashboard-container">
                            <li><a href="{{ route('primaryIndicators.pendingAdmin') }}">Indicators</a></li>
                        </div>
                    </div>
                @endcan

                @can('library', Auth::user())
                    <button class="button-library" id="library-dropdown">
                        <div class="list-library-container">
                            <div class="library-icon-container">
                                @include('svg.library-icon')
                            </div>
                            <div class="action-library-container">
                                Library
                            </div>
                            <div id="library-dropdown-icon" class="dropdown-icon-container">
                                @include('svg.dropdown-icon')
                            </div>
                        </div>
                    </button>

                    @php
                        $libraryContent = [
                            'Users' => '/users',
                            'DOST Agencies' => '/agencies',
                            'Indicators' => '/indicators',
                            'DOST Strategic Pillar' => '/pillars',
                            'DOST Thematic Area' => '/areas',
                            'DOST Priority' => '/priorities',
                            'SDG' => '/sdgs',
                            'HNRDA' => '/hnrdas',
                        ];
                    @endphp

                    <div id="library-content" class="sublibrary-container">
                        <ul>
                            @foreach ($libraryContent as $label => $action)
                            <div class="list-item-container">
                                <div class="hyphen-container">
                                    <div class="circle"></div>
                                </div>
                                <li><a href={{ $action }}>{{ $label }}</a></li>
                            </div>
                            @endforeach
                        </ul>
                    </div>
                @endcan
            </ul>
        </nav>
    </div>

    <div class="collapse-container" id="collapse-container-id">
        <button class="button-collapse" onclick="collapseSidebar()">
            <div id="collapse-icon-id" class="collapse-icon-container">
                @include('svg.dropleft-icon')
            </div>
        </button>
    </div>
</div>
