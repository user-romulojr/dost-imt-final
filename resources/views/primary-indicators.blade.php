<x-app-layout>
    <x-title-page>Primary Indicators</x-title-page>

    <x-horizontal-line></x-horizontal-line>

    <div class="options-container">
        <div style="display: flex; gap: 30px;">
            <div>
                <button class="filter-button">
                    <span>Filter By</span>
                    @include('svg.dropdown-icon')
                </button>
            </div>
            <input type="text" class="input-search" name="search" placeholder="Search...">
        </div>
        <div>
            <button class="manage-button" onclick="openSelectDialog()">
                @include('svg.gear-icon')
                <span>Manage Indicator</span>
            </button>
        </div>
    </div>

    <dialog id="selectDialog">
        <div class="dialog-container">
            <form action="{{ route('primaryIndicators.select')}}" method="POST">
                @csrf

                @foreach($unselectedIndicators as $unselectedIndicator)
                    <div>
                        <input type="checkbox" name="items[]" value="{{ $unselectedIndicator->id }}">
                        <label>{{ $unselectedIndicator->indicator }}</label>
                    </div>
                @endforeach
                <button type="submit">Save</button>
            </form>
            <button onclick="closeSelectDialog()" class="btn btn-secondary">Close</button>
        </div>
    </dialog>

    <table class="table-content">
        <thead>
            <tr>
                <th rowspan="2">Indicator</th>
                <th rowspan="2">Major Final Output</th>
                <th colspan="6" style="text-align: center;">Target</th>
                <th rowspan="2">Comments</th>
                <th rowspan="2">Action</th>
            </tr>
            <tr>
                @foreach ($years as $year)
                    <th>{{ $year }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($selectedIndicators as $selectedIndicator)
                @php
                    $mfoCount = $selectedIndicator->majorFinalOutputs()->count();
                    $counter = 0;
                @endphp

                @foreach ($selectedIndicator->majorFinalOutputs as $majorFinalOutput)
                    @php
                        $counter++;
                        $successIndicators = $majorFinalOutput->successIndicators;
                    @endphp
                    <tr
                    >
                    {{-- style="{{ $counter == $mfoCount ? 'border-bottom: 1px solid #CBCBCB;' : '' }}" --}}
                        @if ($counter == 1)
                            <td rowspan={{ $mfoCount }}>
                                {{ $selectedIndicator->indicator }}
                            </td>
                        @endif

                        <td id="mfo-{{ $selectedIndicator->id }}-{{ $counter }}">
                            {{ $majorFinalOutput->major_final_output }}
                        </td>

                        @foreach ($years as $year)
                            <td id="row-{{ $counter }}-{{ $year }}">
                                @if ($successIndicators->contains('year', $year))
                                    {{ $successIndicators->firstWhere('year', $year)->target }}
                                @endif
                            </td>
                        @endforeach

                        <td>
                            @foreach ($majorFinalOutput->comments as $comment)
                                <div class="comment-container" onclick="commentSettings('{{ $comment->id }}', 'comment-{{ $comment->id }}')" style="cursor: pointer;">
                                    <div class="comment-info">
                                        <div>{{ $comment->user->firstName }} {{ $comment->user->lastName }}</div>
                                        <div>{{ $comment->created_at->format('j F Y g:i A') }}</div>
                                    </div>
                                    <div class="comment-content" id="comment-{{ $comment->id }}">{{ $comment->comment }}</div>
                                </div>
                            @endforeach
                            <form action="{{ route('comment.store', ['id' => $majorFinalOutput->id ]) }}" method="POST">
                                @csrf
                                <input type="text" name="comment" class="input-comment" placeholder="Add comment">
                            </form>
                        </td>

                        <td>
                            <form action="{{ route('primaryIndicators.destroy', ['id' => $majorFinalOutput->id ]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button-action" style="margin-bottom: 5px;">–</button>
                            </form>
                            <button class="button-action" onclick="openEditDialog({{ $selectedIndicator->id }}, {{ $majorFinalOutput->id }}, {{ $counter }},
                                {{ $currentYear }}, {{ $selectedIndicator->end_year }},
                                 {
                                    @for ($year = $currentYear; $year <= $selectedIndicator->end_year; $year++)
                                        _{{ $year }}: '{{ $successIndicators->firstWhere('year', $year)->target }}',
                                    @endfor
                                 })"
                                    style="cursor: pointer;">/</button>
                        </td>
                    </tr>
                @endforeach

                <tr style="border-bottom: 1px solid #CBCBCB;">
                    <td>
                        @if ($mfoCount == 0)
                            {{ $selectedIndicator->indicator }}
                        @endif
                    </td>

                    <td>

                    </td>

                    @foreach ($years as $year)
                        <td></td>
                    @endforeach

                    <td>

                    </td>

                    <td>
                        <button class="button-action" onclick="openCreateDialog({{ $selectedIndicator->id }}, {{ $currentYear }}, {{ $selectedIndicator->end_year }})">+</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <dialog id="createDialog">
        <div class="dialog-container">
            <form method="POST" id="storeForm">
                @csrf
                <div class="form-container">
                    <div class="label-container" id="create-label-container">

                    </div>
                    <div class="input-container" id="create-input-container">

                    </div>
                </div>
                <button type="submit">Save</button>
            </form>
            <button onclick="closeDialog('createDialog', 'create-label-container', 'create-input-container')">Close</button>
        </div>
    </dialog>

    <dialog id="editDialog">
        <div class="dialog-container">
            <form method="POST" id="updateForm">
                @csrf
                @method('PUT')

                <div class="form-container">
                    <div class="label-container" id="edit-label-container">

                    </div>
                    <div class="input-container" id="edit-input-container">

                    </div>
                </div>
                <button type="submit">Update</button>
            </form>
            <button onclick="closeDialog('editDialog', 'edit-label-container', 'edit-input-container')">Close</button>
            </form>
        </div>
    </dialog>

    <dialog id="commentSettings">
        <div class="dialog-container">
            <form method="POST" id="updateComment">
                @csrf
                @method('PUT')

                <div class="form-container">
                    <div class="label-container">
                        <label>Comment</label>
                    </div>
                    <div class="input-container">
                        <input type="text" name="comment" id="comment">
                    </div>
                </div>
                <button type="submit">Update</button>
            </form>
            <form method="POST" id="deleteComment">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
            <button onclick="closeDialog('commentSettings', 'edit-label-container', 'edit-input-container')">Close</button>
        </div>
    </dialog>

    @push('script')
        <script>
            function openSelectDialog() {
                document.getElementById('selectDialog').showModal();
            }

            function closeSelectDialog() {
                document.getElementById('selectDialog').close();
            }

            function openCreateDialog(id, current_year, end_year) {
                const storeForm = document.getElementById('storeForm');
                storeForm.action = `/indicators/primary/${id}/store`;

                const inputContainer = document.getElementById('create-input-container');
                const labelContainer = document.getElementById('create-label-container');

                const majorFinalOutput = document.createElement('input');
                majorFinalOutput.type = 'text';
                majorFinalOutput.name = 'major_final_output';
                majorFinalOutput.id = 'majorFinalOutput';

                const majorFinalOutputLabel = document.createElement('label');
                majorFinalOutputLabel.textContent = 'Major Final Output';

                inputContainer.appendChild(majorFinalOutput);
                labelContainer.appendChild(majorFinalOutputLabel);

                for(let year = current_year; year <= end_year; year++)
                {
                    const inputElement = document.createElement('input');
                    inputElement.type = 'text';
                    inputElement.name = year;
                    inputElement.id = year;

                    const labelElement = document.createElement('label');
                    labelElement.textContent = year;

                    inputContainer.appendChild(inputElement);
                    labelContainer.appendChild(labelElement);
                }

                document.getElementById('createDialog').showModal();
            }

            function openEditDialog(id, mfoID, counter, current_year, end_year, successIndicators) {
                const updateForm = document.getElementById('updateForm');
                updateForm.action = `/indicators/primary/${mfoID}/update`;

                const inputContainer = document.getElementById('edit-input-container');
                const labelContainer = document.getElementById('edit-label-container');

                const majorFinalOutput = document.createElement('input');
                majorFinalOutput.type = 'text';
                majorFinalOutput.name = 'major_final_output';
                majorFinalOutput.id = 'majorFinalOutput';
                majorFinalOutput.value = document.getElementById('mfo-' + id + "-" + counter).innerHTML.trim();

                const majorFinalOutputLabel = document.createElement('label');
                majorFinalOutputLabel.textContent = 'Major Final Output';

                inputContainer.appendChild(majorFinalOutput);
                labelContainer.appendChild(majorFinalOutputLabel);

                for(let year = current_year; year <= end_year; year++)
                {
                    const inputElement = document.createElement('input');
                    inputElement.type = 'text';
                    inputElement.name = year;
                    inputElement.id = year;
                    inputElement.value = successIndicators["_" + year];

                    const labelElement = document.createElement('label');
                    labelElement.textContent = year;

                    inputContainer.appendChild(inputElement);
                    labelContainer.appendChild(labelElement);
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

            function closeDialog(dialog, label, input) {
                const dialogContainer = document.getElementById(dialog);
                const labelContainer = document.getElementById(label);
                const inputContainer = document.getElementById(input);

                dialogContainer.close();

                labelContainer.innerHTML = '';
                inputContainer.innerHTML = '';
            }
        </script>
    @endpush
</x-app-layout>
