<x-app-layout>
    <button id="openCreateDialog" class="btn btn-primary">
        Add New HNRDA
    </button>

    <table class="table-content">
        <thead>
            <tr>
                <th>Title</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hnrdas as $hnrda)
                <tr onclick="openEditDialog('{{ $hnrda->id }}', '{{ $hnrda->title }}')"
                    style="cursor: pointer;">
                    <td>{{ $hnrda->title }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <dialog id="createDialog">
        <div class="dialog-container">
            <form id="createForm" action="{{ route('hnrdas.store') }}" method="POST">
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
                            <input type="{{ $formField['type']}}" id="edit{{ $formField['id'] }}" name="{{ $key }}">
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit">Update</button>
                    <button type="button" id="closeEditDialog">Close</button>
                </div>
            </form>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </div>
    </dialog>

    @push('script')
        <script>
            function openEditDialog(id, title) {
                const editForm = document.getElementById('editForm');
                editForm.action = `/hnrdas/${id}/update`;
                document.getElementById('edittitle').value = title;

                const deleteForm = document.getElementById('deleteForm');
                deleteForm.action = `/hnrdas/${id}/delete`;

                document.getElementById('editDialog').showModal();
            }
        </script>
    @endpush
</x-app-layout>
