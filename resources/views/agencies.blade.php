<x-app-layout>
    <x-title-page>DOST Agencies</x-title-page>

    <x-horizontal-line></x-horizontal-line>

    <div class="options-container">
        <div style="display: flex; gap: 30px;">
            <div class="custom-dropdown">
                <div class="dropdown-button" onclick="toggleContent('dropdown-content-id', 'dropdown-button')">
                    <span>Filter By</span>
                    @include('svg.dropdown-icon')
                </div>

                <div class="dropdown-content" id="dropdown-content-id">
                    <div class="dropdown-header">
                        <span>Filter By</span>
                        <div class="close-icon-container" onclick="toggleContent('dropdown-content-id', 'dropdown-button')">@include('svg.close-icon')</div>
                    </div>
                    <form action="{{ route('primaryIndicators.index')}}" method="GET">
                        @csrf
                        <div class="dropdown-main">
                                @foreach ($selectFields as $classification => $allCategories)
                                    <div class="input-container" style="margin-bottom: 1px;">
                                        <label>{{ $selectLabels[$classification] }}</label>
                                        <select class="select-input" id="{{ $classification }}_id" name="{{ $classification }}_id">
                                            <option disabled selected>Select Option</option>
                                            @foreach ($allCategories as $category)
                                                <option value="{{ $category->id }}"><span class="option-span">{{ $category->title }}</span></option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            <div class="line-container"></div>
                        </div>
                        <div class="dropdown-footer">
                            <button type="submit" class="primary-button">Filter</button>
                            <button type="button" class="secondary-button" onclick="toggleContent('dropdown-content-id', 'dropdown-button')">Close</button>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <form action="{{ route('primaryIndicators.index')}}" method="GET">
                    @csrf
                    <input type="text" class="input-search" name="search" placeholder="Search...">
                </form>
            </div>
        </div>
        <div>
            <button class="manage-button" id="openCreateDialog">
                @include('svg.gear-icon')
                <span>Add DOST Agency</span>
            </button>
        </div>
    </div>

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
        <div class="modal-content" id="modal-content-id">
            <div class="modal-header">
                <span>Add DOST Agency</span>
                <div class="close-icon-container" onclick="closeDialog('createDialog')">@include('svg.close-icon')</div>
            </div>
            <form method="POST" action="{{ route('agencies.store') }}" id="createForm">
                <div class="modal-main">
                    @csrf
                    <div class="input-container" id="create-input-container">
                        @foreach ($formFields as $key => $formField)
                            <label for="{{ $formField['id'] }}">{{ $formField['label'] }}</label>
                            <input type="{{ $formField['type']}}" id="{{ $formField['id'] }}" class="input-layout" name="{{ $key }}">
                        @endforeach
                    </div>
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
                <span>Update DOST Agency</span>
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

            function closeDialog(dialog) {
                event.preventDefault();
                const dialogContainer = document.getElementById(dialog);

                dialogContainer.close();
            }
        </script>
    @endpush
</x-app-layout>
