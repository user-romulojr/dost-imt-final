<x-app-layout>
    <button id="openCreateDialog" class="btn btn-primary">
        Add New DOST Agency
    </button>

    <table class="table-content">
        <thead>
            <tr>
                <th>Agency</th>
                <th>Acronym</th>
                <th>Agency Group</th>
                <th>Contact</th>
                <th>Website</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agencies as $agency)
                <tr onclick="openEditDialog(
                    '{{ $agency->id }}',
                    '{{ $agency->agency }}',
                    '{{ $agency->acronym }}',
                    '{{ $agency->group }}',
                    '{{ $agency->contact }}',
                    '{{ $agency->website }}',
                )" style="cursor: pointer;">
                    <td>{{ $agency->agency }}</td>
                    <td>{{ $agency->acronym }}</td>
                    <td>{{ $agency->group }}</td>
                    <td>{{ $agency->contact }}</td>
                    <td>{{ $agency->website }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <dialog id="createDialog">
        <div class="dialog-container">
            <form id="createForm" action="{{ route('agencies.store') }}" method="POST">
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
            function openEditDialog(id, agency, acronym, group, contact, website) {
                const editForm = document.getElementById('editForm');
                editForm.action = `/agencies/${id}/update`;
                document.getElementById('edit_agency').value = agency;
                document.getElementById('edit_acronym').value = acronym;
                document.getElementById('edit_group').value = group;
                document.getElementById('edit_contact').value = contact;
                document.getElementById('edit_website').value = website;
                document.getElementById('editDialog').showModal();

                const deleteForm = document.getElementById('deleteForm');
                deleteForm.action = `/agencies/${id}/delete`
            }
        </script>
    @endpush
</x-app-layout>
