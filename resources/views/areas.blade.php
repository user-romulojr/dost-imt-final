<x-app-layout>
    <button id="openCreateDialog">
        Add New DOST Thematic Area
    </button>

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
        <div class="dialog-container">
            <form id="createForm" action="{{ route('areas.store') }}" method="POST">
                @csrf

                <div class="form-container">
                    <div class="label-container">
                        @foreach ($formFields as $formField)
                            <label for="{{ $formField['id'] }}">{{ $formField['label'] }}</label>
                        @endforeach
                    </div>

                    <div class="input-container">
                        @foreach ($formFields as $key => $formField)
                            <input type="{{ $formField['type']}}" id="{{ $formField['id'] }}" name="{{ $key }}">
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create</button>
                    <button type="button" id="closeCreateDialog" class="btn btn-secondary">Close</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Edit Dialog -->
    <dialog id="editDialog">
        <div class="dialog-container">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="form-container">
                    <div class="label-container">
                        @foreach ($formFields as $formField)
                            <label for="edit{{ $formField['id'] }}">{{ $formField['label'] }}</label>
                        @endforeach
                    </div>

                    <div class="input-container">
                        @foreach ($formFields as $key => $formField)
                            <input type="{{ $formField['type']}}" id="edit_{{ $formField['id'] }}" name="{{ $key }}">
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" id="closeEditDialog" class="btn btn-secondary">Close</button>
                </div>
            </form>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
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
        </script>
    @endpush
</x-app-layout>
