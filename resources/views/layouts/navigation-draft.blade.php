<div class="navigation-container">
    <a href="{{ route('dashboard') }}">
        <div class="logo-container">
            <div class="svg-logo-container">
                @include('svg.logo-imt')
            </div>
            <div class="text-system">
                <div class="center-txt-container">
                    <div class="text-indicators">DOST Indicators</div>
                    <div class="text-mgmt-sys">Management Tool</div>
                </div>
            </div>
        </div>
    </a>


    <div class="right-container">
        <div class="notif-container">
            @include('svg.notif-icon')
        </div>

        <div class="vline-container">
            <div class="vertical-line"></div>
        </div>

        <div class="profile-container">
            <button class="button-profile" onclick="toggleProfileDropdown()">
                <div class="image-profile">
                    <img src="{{ asset('image/profile-pic.png') }}" class="image-profile" alt="Profile Image">
                </div>
            </button>
            <div class="content-profile" id="dropdown-profile">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
