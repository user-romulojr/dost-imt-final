<x-app-layout>
    <h1>Users</h1>

    <!-- Add Button -->
    <button id="openCreateDialog" class="btn btn-primary">
        Add New User
    </button>

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
    </table>


    <!-- Add Dialog -->
    <dialog id="createDialog">
        <div class="dialog-container">
            <form id="createForm" action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="form-container">
                    <div class="label-container">
                        <label for="firstName" class="form-label">First Name</label>
                        <label for="lastName" class="form-label">Last Name</label>
                        <label for="agency" class="form-label">Agency</label>
                        <label for="email" class="form-label">Email</label>
                        <label for="contact" class="form-label">Contact</label>
                        <label for="role" class="form-label">Role</label>
                        <label for="password" class="form-label">Password</label>
                    </div>

                    <div class="input-container">
                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                        <input type="text" class="form-control" id="lastName" name="lastName" required>
                        <select id="AgencyID" name="agency_id">
                            <option disabled selected>Select agency designated</option>
                            @foreach ($agencies as $agency)
                                <option value={{ $agency->id }}>{{ $agency->agency }}</option>
                            @endforeach
                        </select>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <input type="text" class="form-control" id="contact" name="contact">
                        <input type="text" class="form-control" id="role" name="role">
                        <input type="password" class="form-control" id="password" name="password" required>
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
                        <label for="editFirstName" class="form-label">First Name</label>
                        <label for="editLastName" class="form-label">Last Name</label>
                        <label for="editAgency" class="form-label">Agency</label>
                        <label for="editEmail" class="form-label">Email</label>
                        <label for="editContact" class="form-label">Contact</label>
                        <label for="editRole" class="form-label">Role</label>
                        <label for="editPassword" class="form-label">Password</label>
                    </div>

                    <div class="input-container">
                        <input type="text" class="form-control" id="editFirstName" name="firstName" required>
                        <input type="text" class="form-control" id="editLastName" name="lastName" required>
                        <select id="editAgencyID" name="agency_id">
                            @foreach ($agencies as $agency)
                                <option value={{ $agency->id }}>{{ $agency->agency }}</option>
                            @endforeach
                        </select>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                        <input type="text" class="form-control" id="editContact" name="contact">
                        <input type="text" class="form-control" id="editRole" name="role">
                        <input type="password" class="form-control" id="editPassword" name="password" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" id="closeEditDialog" class="btn btn-secondary">Close</button>
                </div>
            </form>

            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </dialog>

    @push('script')
        <script>
            function openEditDialog(id, firstName, lastName, agencyID, email, contact, role) {
                const editForm = document.getElementById('editForm');
                editForm.action = `/users/${id}/update`;
                document.getElementById('editFirstName').value = firstName;
                document.getElementById('editLastName').value = lastName;
                document.getElementById('editAgencyID').value = agencyID;
                document.getElementById('editEmail').value = email;
                document.getElementById('editContact').value = contact;
                document.getElementById('editRole').value = role;

                const deleteForm = document.getElementById('deleteUserForm');
                deleteForm.action = `/users/${id}/delete`;

                document.getElementById('editDialog').showModal();
            }
        </script>
    @endpush
</x-app-layout>
