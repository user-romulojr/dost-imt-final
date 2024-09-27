<x-app-layout>
    <x-title-page>DOST Thematic Area</x-title-page>

    <x-horizontal-line></x-horizontal-line>

    <div class="options-container">
        <div>
            <div>
                <form action="{{ route('areas.index')}}" method="GET">
                    @csrf
                    <input type="text" class="input-search" name="search" placeholder="Search...">
                </form>
            </div>
        </div>
        <div>
            <button class="manage-button" id="openCreateDialog">
                @include('svg.gear-icon')
                <span>Add DOST Thematic Area</span>
            </button>
        </div>
    </div>

    <table class="table-content">
        <thead>
            <tr>
                <th>Title</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($areas as $area)
                <tr onclick="openEditDialog(
                    '{{ $area->id }}',
                    '{{ $area->title }}',
                )" style="cursor: pointer;">
                    <td>{{ $area->title }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <dialog id="createDialog">
        <div class="modal-content" id="modal-content-id">
            <div class="modal-header">
                <span>Add DOST Thematic Area</span>
                <div class="close-icon-container" onclick="closeDialog('createDialog')">@include('svg.close-icon')</div>
            </div>
            <form method="POST" action="{{ route('areas.store') }}" id="createForm">
                @csrf
                <div class="modal-main">
                    @foreach ($formFields as $key => $formField)
                        <div class="input-container">
                            <label for="{{ $formField['id'] }}">{{ $formField['label'] }}</label>
                            <input type="{{ $formField['type']}}" id="{{ $formField['id'] }}" class="input-layout" name="{{ $key }}">
                        </div>
                    @endforeach
                    <div class="line-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="primary-button">Save</button>
                    <button class="secondary-button" onclick="closeDialog('createDialog')">Close</button>
                </div>
            </form>
            </div>
    </dialog>

    <dialog id="editDialog">
        <div class="modal-content" id="modal-content-id">
            <div class="modal-header">
                <span>Update DOST Thematic Area</span>
                <div class="close-icon-container" onclick="closeDialog('editDialog')">@include('svg.close-icon')</div>
            </div>
            <form method="POST" id="editForm">
                <div class="modal-main">
                    @csrf
                    @method('PUT')
                    <div class="input-container" id="create-input-container">
                        @foreach ($formFields as $key => $formField)
                            <label for="edit{{ $formField['id'] }}">{{ $formField['label'] }}</label>
                            <input type="{{ $formField['type']}}" class="input-layout" id="edit_{{ $formField['id'] }}" name="{{ $key }}">
                        @endforeach
                    </div>
                    <div class="line-container"></div>
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="primary-button">Save</button>
                    </div>
                </div>
            </form>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="submit" class="primary-button">Delete</button>
                    <button class="secondary-button" onclick="closeDialog('editDialog')">Close</button>
                </div>
            </form>
        </div>
    </dialog>

    @push('script')
        <script>
            function openEditDialog(id, title) {
                const editForm = document.getElementById('editForm');
                editForm.action = `/areas/${id}/update`;
                document.getElementById('edit_title').value = title;
                document.getElementById('editDialog').showModal();

                const deleteForm = document.getElementById('deleteForm');
                deleteForm.action = `/areas/${id}/delete`
            }

            function closeDialog(dialog) {
                event.preventDefault();
                const dialogContainer = document.getElementById(dialog);

                dialogContainer.close();
            }
        </script>
    @endpush
</x-app-layout>
