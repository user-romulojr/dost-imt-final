<x-app-layout>
    <x-title-page>Primary Indicators</x-title-page>

    <x-horizontal-line></x-horizontal-line>

    <div class="options-container">
        <div style="display: flex; gap: 30px;">
            <div style="display: flex; gap: 10px;">
                <div>
                    <form action="{{ route('primaryIndicators.pendingAdmin') }}" method="GET">
                        <button type="submit" class="secondary-button">Pending</button>
                    </form>
                </div>
                <div>
                    <form action="{{ route('primaryIndicators.approvedAdmin') }}" method="GET">
                        <button type="submit" class="secondary-button">Approved</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @php
        $counter = 1;
    @endphp


    <div>
    <table class="table-content">
        <thead>
            <tr>
                <th>No.</th>
                <th>Agency</th>
                <th>Agency Group</th>
                <th>Date Submitted</th>
                <th>Status</th>
            </tr>
        </thead>
    </table>
    <div class="table-container">
    <table class="table-content">
        <tbody class="table-body">
            @foreach ($pendingIndicators as $pendingIndicator)
                <tr>
                    <td>{{ $counter }}</td>
                    <td>{{ isset($pendingIndicator->agency->agency) ?  $pendingIndicator->agency->agency : ''}}</td>
                    <td>{{ isset($pendingIndicator->agency->agencyGroup) ? $pendingIndicator->agency->agencyGroup : '' }}</td>
                    <td>{{ $pendingIndicator->created_at->format('j F Y g:i A') }}</td>
                    <td>Approved</td>
                    @php
                        $counter++;
                    @endphp
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>


    @push('script')
        <script>
            function openSelectDialog() {
                document.getElementById('selectDialog').showModal();
            }

            function closeSelectDialog() {
                event.preventDefault();
                document.getElementById('selectDialog').close();
            }

            function openCreateDialog(id, current_year, end_year) {
                const storeForm = document.getElementById('storeForm');
                const inputContainer = document.getElementById('create-input-container');
                storeForm.action = `/indicators/primary/${id}/store`;

                const majorFinalOutputLabel = document.createElement('label');
                majorFinalOutputLabel.textContent = 'Major Final Output';

                const majorFinalOutput = document.createElement('input');
                majorFinalOutput.type = 'text';
                majorFinalOutput.name = 'major_final_output';
                majorFinalOutput.id = 'majorFinalOutput';
                majorFinalOutput.className = 'input-layout';

                inputContainer.appendChild(majorFinalOutputLabel);
                inputContainer.appendChild(majorFinalOutput);

                for(let year = current_year; year <= end_year; year++)
                {
                    const inputElement = document.createElement('input');
                    inputElement.type = 'text';
                    inputElement.name = year;
                    inputElement.id = year;
                    inputElement.className = 'input-layout';

                    const labelElement = document.createElement('label');
                    labelElement.textContent = year + " Target";

                    inputContainer.appendChild(labelElement);
                    inputContainer.appendChild(inputElement);
                }

                document.getElementById('createDialog').showModal();
            }

            function openEditDialog(id, mfoID, counter, current_year, end_year, successIndicators) {
                const updateForm = document.getElementById('updateForm');
                updateForm.action = `/indicators/primary/${mfoID}/update`;

                const inputContainer = document.getElementById('edit-input-container');

                const majorFinalOutput = document.createElement('input');
                majorFinalOutput.type = 'text';
                majorFinalOutput.name = 'major_final_output';
                majorFinalOutput.id = 'majorFinalOutput';
                majorFinalOutput.className = 'input-layout';
                majorFinalOutput.value = document.getElementById('mfo-' + id + "-" + counter).innerHTML.trim();

                const majorFinalOutputLabel = document.createElement('label');
                majorFinalOutputLabel.textContent = 'Major Final Output';

                inputContainer.appendChild(majorFinalOutputLabel);
                inputContainer.appendChild(majorFinalOutput);

                for(let year = current_year; year <= end_year; year++)
                {
                    const inputElement = document.createElement('input');
                    inputElement.type = 'text';
                    inputElement.name = year;
                    inputElement.id = "edit" + year;
                    inputElement.className = 'input-layout';
                    inputElement.value = successIndicators["_" + year];


                    const labelElement = document.createElement('label');
                    labelElement.textContent = year + " Target";

                    inputContainer.appendChild(labelElement);
                    inputContainer.appendChild(inputElement);
                }

                document.getElementById('editDialog').showModal();
            }

            function commentSettings(id, commentID) {
                const inputComment = document.getElementById('comment');
                const contentComment = document.getElementById(commentID);

                const updateComment = document.getElementById('updateComment');
                const deleteComment = document.getElementById('deleteComment');

                updateComment.action = `/comment/${id}/update`;
                deleteComment.action = `/comment/${id}/delete`;

                inputComment.value = contentComment.innerHTML;
                document.getElementById('commentSettings').showModal();
            }

            function closeDialog(dialog, input) {
                event.preventDefault();

                const dialogContainer = document.getElementById(dialog);
                const inputContainer = document.getElementById(input);

                dialogContainer.close();

                inputContainer.innerHTML = '';
            }
        </script>
    @endpush
</x-app-layout>
