<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="{{ asset('css/layout-app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
        <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
        <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

        <script defer src="{{ asset('js/app.js') }}"></script>

        @stack('style')
    </head>
    <body>
        @include('layouts.navigation-draft')


        <div class="main-container">
            @include('layouts.sidebar-draft')

            <div id="main-content-id" class="main-content">
                @if($errors->any())
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif


                {{ $slot }}
            </div>
        </div>

        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset
    </body>

    @stack('script')

    <script>
        const indicatorsDropdown = document.getElementById('indicators-dropdown');
        const indicatorsContent = document.getElementById('indicators-content');
        const indicatorsdropdownIcon = document.getElementById('indicators-dropdown-icon');

        const isIndicatorsDropdownOpen = @json(session('indicators_dropdown_open', false));
        if (isIndicatorsDropdownOpen) {
            indicatorsContent.style.display = 'block';
            indicatorsdropdownIcon.style.transform = 'rotate(180deg)';
        }

        indicatorsDropdown.addEventListener('click', function() {
            const isCurrentlyOpen = indicatorsContent.style.display === 'block';
            indicatorsContent.style.display = isCurrentlyOpen ? 'none' : 'block';
            indicatorsdropdownIcon.style.transform = isCurrentlyOpen ? '' : 'rotate(180deg)';

            fetch('{{ route('store.indicators.dropdown.state') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ open: !isCurrentlyOpen })
            });
        });

        const libraryDropdown = document.getElementById('library-dropdown');
        const libraryContent = document.getElementById('library-content');
        const librarydropdownIcon = document.getElementById('library-dropdown-icon');

        const isLibraryDropdownOpen = @json(session('library_dropdown_open', false));
        if (isLibraryDropdownOpen) {
            libraryContent.style.display = 'block';
            librarydropdownIcon.style.transform = 'rotate(180deg)';
        }

        libraryDropdown.addEventListener('click', function() {
            const isCurrentlyOpen = libraryContent.style.display === 'block';
            libraryContent.style.display = isCurrentlyOpen ? 'none' : 'block';
            librarydropdownIcon.style.transform = isCurrentlyOpen ? '' : 'rotate(180deg)';

            fetch('{{ route('store.library.dropdown.state') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ open: !isCurrentlyOpen })
            });
        });
    </script>
</html>
