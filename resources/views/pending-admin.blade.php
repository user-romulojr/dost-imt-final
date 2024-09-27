<x-app-layout>
    <x-title-page>Primary Indicators</x-title-page>

    <x-horizontal-line></x-horizontal-line>

    <div class="options-container">
        <div style="display: flex; gap: 10px;">
            <div>
                <form action="{{ route('primaryIndicators.pendingAdmin') }}" method="GET">
                    <button type="submit" class="secondary-button">Pending</button>
                </form>
            </div>
            <div>
                <form action="{{ route('primaryIndicators.approved') }}" method="GET">
                    <button type="submit" class="secondary-button">Approved</button>
                </form>
            </div>
        </div>
    </div>

    <div>
        @foreach ($pendingIndicators as $user)
            <form action="{{ route('primaryIndicators.pending', ['id' => $user->id ]) }}">
                <button type="submit" class="button-cancel" style="width: 100%;">
                <div style="padding: 15px; background-color: rgba(13, 78, 134, 0.15); font-size: 12px;">
                    <div>
                        {{ $user->last_name }}, {{ $user->first_name }}
                    </div>
                    <div>
                        {{ $user->agency->agency }}
                    </div>
                    <div>
                        {{ $user->email }}
                    </div>
                </div>
                </button>
            </form>
        @endforeach
    </div>
</x-app-layout>
