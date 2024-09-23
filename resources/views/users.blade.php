<x-app-layout>
    <x-title-page>Users</x-title-page>

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
                            <div class="close-icon-container">@include('svg.close-icon')</div>
                        </div>
                        <form action="{{ route('primaryIndicators.index')}}" method="GET">
                            @csrf
                                <div class="dropdown-main">
                                        <div class="input-container">
                                            <label>Agency</label>
                                            <select class="select-input" id="agency_id" name="agency_id">
                                                <option disabled selected>Select Option</option>
                                                @foreach ($agencies as $agency)
                                                    <option value="{{ $agency->id }}">{{ $agency->acronym }} - {{ $agency->agency }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-container">
                                            <label>Access Level</label>
                                            <select class="select-input">
                                            </select>
                                        </div>
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
                <span>Add User</span>
            </button>
        </div>
    </div>

    <table class="table-content">
        <thead>
            <tr>
                <th>Name</th>
                <th>Agency</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Access Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr onclick="openEditDialog(
                    '{{ $user->id }}',
                    '{{ $user->firstName }}',
                    '{{ $user->lastName}}',
                    '{{ isset($user->agency) ? $user->agency->id : '' }}',
                    '{{ $user->email }}',
                    '{{ $user->contact }}',
                    '{{ $user->role }}',
                )" style="cursor: pointer;">
                    <td>{{ $user->lastName }}, {{ $user->firstName }}</td>
                    <td>{{ isset($user->agency) ? $user->agency->agency : ''}}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->contact }}</td>
                    <td>
                        <span>
                            {{ $accessLevel[$user->role] }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>


    <dialog id="createDialog">
        <div class="modal-content" id="modal-content-id">
            <div class="modal-header">
                <span>Add User</span>
                <div class="close-icon-container" onclick="closeDialog('createDialog')">@include('svg.close-icon')</div>
            </div>
            <form method="POST" action="{{ route('users.store') }}" id="createForm">
                @csrf
                <div class="modal-main">
                    <div class="input-container">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="input-layout" id="firstName" name="firstName" required>
                    </div>

                    <div class="input-container">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="input-layout" id="lastName" name="lastName" required>
                    </div>

                    <div class="input-container">
                        <label for="agency" class="form-label">Agency</label>
                        <select class="select-input" id="AgencyID" name="agency_id">
                            <option disabled selected>Select agency designated</option>
                            @foreach ($agencies as $agency)
                                <option value={{ $agency->id }}>{{ $agency->agency }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-container">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="input-layout" id="email" name="email" required>
                    </div>

                    <div class="input-container">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="text" class="input-layout" id="contact" name="contact">
                    </div>

                    <div class="input-container">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" class="input-layout" id="role" name="role">
                    </div>

                    <div class="input-container">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="input-layout" id="password" name="password" required>
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
                <span>Update User</span>
                <div class="close-icon-container" onclick="closeDialog('editDialog')">@include('svg.close-icon')</div>
            </div>
            <form method="POST" id="editForm">
                <div class="modal-main">
                    @csrf
                    @method('PUT')

                    <div class="input-container">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="input-layout" id="edit_firstName" name="firstName" required>
                    </div>

                    <div class="input-container">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="input-layout" id="edit_lastName" name="lastName" required>
                    </div>

                    <div class="input-container">
                        <label for="agency" class="form-label">Agency</label>
                        <select class="select-input" id="edit_agencyID" name="agency_id">
                            <option disabled selected>Select agency designated</option>
                            @foreach ($agencies as $agency)
                                <option value={{ $agency->id }}>{{ $agency->agency }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-container">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="input-layout" id="edit_email" name="email" required>
                    </div>

                    <div class="input-container">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="text" class="input-layout" id="edit_contact" name="contact">
                    </div>

                    <div class="input-container">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" class="input-layout" id="edit_role" name="role">
                    </div>

                    <div class="input-container">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="input-layout" id="edit_password" name="password" required>
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
            function openEditDialog(id, firstName, lastName, agencyID, email, contact, role) {
                const editForm = document.getElementById('editForm');
                editForm.action = `/users/${id}/update`;
                document.getElementById('edit_firstName').value = firstName;
                document.getElementById('edit_lastName').value = lastName;
                document.getElementById('edit_agencyID').value = agencyID;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_contact').value = contact;
                document.getElementById('edit_role').value = role;

                const deleteForm = document.getElementById('deleteForm');
                deleteForm.action = `/users/${id}/delete`;

                document.getElementById('editDialog').showModal();
            }

            function closeDialog(dialog) {
                event.preventDefault();
                const dialogContainer = document.getElementById(dialog);

                dialogContainer.close();
            }
        </script>
    @endpush
</x-app-layout>
