<x-app-layout>
    <button id="openCreateDialog">
        Add New DOST SDG
    </button>

    <table class="table-content">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sdgs as $sdg)
                <tr onclick="openEditDialog(
                    '{{ $sdg->id }}',
                    '{{ $sdg->title }}',
                    '{{ $sdg->description }}',
                )" style="cursor: pointer;">
                    <td>{{ $sdg->title }}</td>
                    <td>{{ $sdg->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <dialog id="createDialog">
        <div class="dialog-container">
            <form id="createForm" action="{{ route('sdgs.store') }}" method="POST">
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
            function openEditDialog(id, title, description) {
                const editForm = document.getElementById('editForm');
                editForm.action = `/sdgs/${id}/update`;
                document.getElementById('edit_title').value = title;
                document.getElementById('edit_description').value = description;
                document.getElementById('editDialog').showModal();

                const deleteForm = document.getElementById('deleteForm');
                deleteForm.action = `/sdgs/${id}/delete`
            }
        </script>
    @endpush
</x-app-layout>
