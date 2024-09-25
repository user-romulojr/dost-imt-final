<x-app-layout>
    <x-title-page>Primary Indicators</x-title-page>

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
                                                <option value="{{ $category->id }}">{{ $category->title }}</option>
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
            <button class="manage-button" onclick="openSelectDialog()">
                @include('svg.gear-icon')
                <span>Manage Indicator</span>
            </button>
        </div>
    </div>

    <dialog id="selectDialog">
        <div class="modal-content" id="modal-content-id">
            <div class="modal-header">
                <span>DOST Primary Indicators</span>
                <div class="close-icon-container" onclick="closeSelectDialog()">@include('svg.close-icon')</div>
            </div>
            <div class="modal-subheader">
                Choose your primary indicators from the Philippine Development Plan's list of indicators below.
            </div>
            <form action="{{ route('primaryIndicators.select')}}" method="POST">
                <div class="modal-main">
                    @csrf
                    @foreach($unselectedIndicators as $unselectedIndicator)
                        <div>
                            <input type="checkbox" name="items[]" value="{{ $unselectedIndicator->id }}">
                            <label>{{ $unselectedIndicator->indicator }}</label>
                        </div>
                    @endforeach
                    <div class="line-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="primary-button">Save</button>
                    <button onclick="closeSelectDialog()" class="secondary-button">Close</button>
                </div>
            </form>
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
            @foreach ($displayedIndicators as $displayedIndicator)
                @php
                    $mfoCount = $displayedIndicator->majorFinalOutputs()->count();
                    $counter = 0;
                @endphp

                @foreach ($displayedIndicator->majorFinalOutputs as $majorFinalOutput)
                    @php
                        $counter++;
                        $successIndicators = $majorFinalOutput->successIndicators;
                    @endphp
                    <tr
                    >
                    {{-- style="{{ $counter == $mfoCount ? 'border-bottom: 1px solid #CBCBCB;' : '' }}" --}}
                        @if ($counter == 1)
                            <td rowspan={{ $mfoCount }}>
                                {{ $displayedIndicator->indicator }}
                            </td>
                        @endif

                        <td id="mfo-{{ $displayedIndicator->id }}-{{ $counter }}">
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
                            <button class="button-action" onclick="openEditDialog({{ $displayedIndicator->id }}, {{ $majorFinalOutput->id }}, {{ $counter }},
                                {{ $currentYear }}, {{ $displayedIndicator->end_year }},
                                 {
                                    @for ($year = $currentYear; $year <= $displayedIndicator->end_year; $year++)
                                        '_{{ $year }}': '{{ $successIndicators->firstWhere('year', $year)->target }}',
                                    @endfor
                                 })"
                                    style="cursor: pointer;">/</button>
                        </td>
                    </tr>
                @endforeach

                <tr style="border-bottom: 1px solid #CBCBCB;">
                    <td>
                        @if ($mfoCount == 0)
                            {{ $displayedIndicator->indicator }}
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
                        <button class="button-action" onclick="openCreateDialog({{ $displayedIndicator->id }}, {{ $currentYear }}, {{ $displayedIndicator->end_year }})">+</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <dialog id="createDialog">
        <div class="modal-content" id="modal-content-id">
            <div class="modal-header">
                <span>Add Major Final Output</span>
                <div class="close-icon-container" onclick="closeDialog('createDialog', 'create-input-container')">@include('svg.close-icon')</div>
            </div>
            <form method="POST" id="storeForm">
                <div class="modal-main">
                    @csrf
                    <div class="input-container" id="create-input-container">

                    </div>
                    <div class="line-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="primary-button">Save</button>
                    <button class="secondary-button" onclick="closeDialog('createDialog', 'create-input-container')">Close</button>
                </div>
            </form>
            </div>
    </dialog>

    <dialog id="editDialog">
        <div class="modal-content" id="modal-content-id">
            <div class="modal-header">
                <span>Update Major Final Output</span>
                <div class="close-icon-container" onclick="closeDialog('editDialog', 'edit-input-container')">@include('svg.close-icon')</div>
            </div>
            <form method="POST" id="updateForm">
                <div class="modal-main">
                    @csrf
                    @method('PUT')
                    <div class="input-container" id="edit-input-container">

                    </div>
                    <div class="line-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="primary-button">Save</button>
                    <button class="secondary-button" onclick="closeDialog('editDialog', 'edit-input-container')">Close</button>
                </div>
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
