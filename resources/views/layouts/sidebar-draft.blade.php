<div id="sidebar" class="left-bar" style="display: flex;">
    <div class="nav-container">
        <nav class="nav">
            <ul>
                <div class="list-dashboard-container {{ request()->is('dashboard') ? 'active' : '' }}">
                    <div class="dboard-icon-container">
                        @include('svg.dashboard-icon')
                    </div>
                    <div class="action-dashboard-container">
                        <li><a href="/dashboard">Dashboard</a></li>
                    </div>
                </div>

                <button class="button-library" onclick="toggleContent('indicators-content', 'indicators-dropdown')">
                    <div class="list-library-container">
                        <div class="library-icon-container">
                            @include('svg.indicators-icon')
                        </div>
                        <div class="action-library-container">
                            Indicators
                        </div>
                        <div id="indicators-dropdown" class="dropdown-icon-container">
                            @include('svg.dropdown-icon')
                        </div>
                    </div>
                </button>

                @php
                    $indicatorsContent = [
                        'Primary Indicators' => '/indicators/primary',
                        'Secondary Indicators' => '/indicators/secondary',
                    ];
                @endphp

                <div id="indicators-content" class="sublibrary-container">
                    <ul>
                        @foreach ($indicatorsContent as $label => $action)
                        <div class="list-item-container">
                            <div class="hyphen-container">
                                <div class="circle"></div>
                            </div>
                            <li><a href={{ $action }}>{{ $label }}</a></li>
                        </div>
                        @endforeach
                    </ul>
                </div>

                <button class="button-library" onclick="toggleContent('library-content', 'library-dropdown')">
                    <div class="list-library-container">
                        <div class="library-icon-container">
                            @include('svg.library-icon')
                        </div>
                        <div class="action-library-container">
                            Library
                        </div>
                        <div id="library-dropdown" class="dropdown-icon-container">
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
            </ul>
        </nav>
    </div>

    <div class="collapse-container">
        <button class="button-collapse" onclick="collapseSidebar()">
            <div id="collapse-icon-id" class="collapse-icon-container">
                @include('svg.dropleft-icon')
            </div>
        </button>
    </div>
</div>
