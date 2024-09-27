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
                    <form action="{{ route('agencies.index')}}" method="GET">
                        @csrf
                        <div class="dropdown-main">
                            <div class="input-container" style="margin-bottom: 1px;">
                                <label>Agency Group</label>
                                <select class="select-input" id="filter_agency_group_id" name="filter_agency_group_id">
                                    <option disabled selected>Select Option</option>
                                    @foreach ($agencyGroups as $agencyGroup)
                                        <option value="{{ $agencyGroup->id }}"
                                                {{ session('filter_agency_group_id') == $agencyGroup->id  ? 'selected' : '' }}
                                        >
                                            {{ $agencyGroup->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="filter" value="category">
                            <div class="line-container"></div>
                                <div style="display: flex; justify-content: flex-end;">
                                    <button type="submit" class="primary-button">Filter</button>
                                </div>
                        </div>
                        <div class="dropdown-footer">
                            <button type="submit" onclick="setDefault(['agency_group'])" class="secondary-button">Set to Default</button>
                            <button type="button" class="secondary-button" onclick="toggleContent('dropdown-content-id', 'dropdown-button')">Close</button>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <form action="{{ route('agencies.index')}}" method="GET">
                    @csrf
                    <input type="hidden" name="filter" value="search">
                    <input type="text" class="input-search" name="filter_search" value="{{ session('filter_search') }}" placeholder="Search...">
                </form>
            </div>
        </div>
        <div>
            <button class="manage-button" id="openCreateDialog">
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
                    '{{ isset($agency->agencyGroup) ? $agency->agencyGroup->id : '' }}',
                    '{{ $agency->contact }}',
                    '{{ $agency->website }}',
                )" style="cursor: pointer;">
                    <td>{{ $agency->agency }}</td>
                    <td>{{ $agency->acronym }}</td>
                    <td>{{ isset($agency->agencyGroup) ? $agency->agencyGroup->title : '' }}</td>
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
                        <div>
                            <label for="agency">Agency</label>
                            <input type="text" id="agency" class="input-layout" name="agency">
                        </div>

                        <div>
                            <label for="acronym">Acronym</label>
                            <input type="text" id="acronym" class="input-layout" name="acronym">
                        </div>

                        <div>
                            <label>Agency Group</label>
                            <select class="select-input" id="agency_group_id" name="agency_group_id">
                                <option disabled selected>Select Option</option>
                                @foreach ($agencyGroups as $agencyGroup)
                                    <option value="{{ $agencyGroup->id }}">{{ $agencyGroup->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="contact">Contact</label>
                            <input type="text" id="contact" class="input-layout" name="contact">
                        </div>

                        <div>
                            <label for="website">Website</label>
                            <input type="text" id="website" class="input-layout" name="website">
                        </div>
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
