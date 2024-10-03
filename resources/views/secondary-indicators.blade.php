<x-app-layout>
    <x-title-page>Secondary Indicators</x-title-page>

    <x-horizontal-line></x-horizontal-line>

    <div class="options-container">
        <div style="display: flex; gap: 30px;">
            <div style="display: flex; gap: 10px;">
                <div>
                    <form action="{{ route('secondaryIndicators.index') }}" method="GET">
                        <button type="submit" class="secondary-button">Draft</button>
                    </form>
                </div>
                <div>
                    <form action="{{ route('secondaryIndicators.approved') }}" method="GET">
                        <button type="submit" class="secondary-button">Approved</button>
                    </form>
                </div>
            </div>
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
                    <form action="{{ route('secondaryIndicators.index')}}" method="GET">
                        @csrf
                        <div class="dropdown-main">
                                @foreach ($selectFields as $classification => $allCategories)
                                    <div class="input-container" style="margin-bottom: 1px;">
                                        <label>{{ $selectLabels[$classification] }}</label>
                                        <select class="select-input" id="filter_{{ $classification }}_id" name="filter_{{ $classification }}_id">
                                            <option disabled selected>Select Option</option>
                                            @foreach ($allCategories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ session("filter_" . $classification . "_id") == $category->id  ? 'selected' : '' }}
                                                >
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                                <input type="hidden" name="filter" value="category">
                                <div class="line-container"></div>
                                <div style="display: flex; justify-content: flex-end;">
                                    <button type="submit" class="primary-button">Filter</button>
                                </div>
                        </div>
                        <div class="dropdown-footer">
                            <button type="submit" onclick="setDefault([
                                @foreach ($filterFields as $filterField)
                                    '{{ $filterField }}',
                                @endforeach
                            ])" class="secondary-button">
                                Set to Default
                            </button>
                            <button type="button" class="secondary-button" onclick="toggleContent('dropdown-content-id', 'dropdown-button')">Close</button>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <form action="{{ route('secondaryIndicators.index')}}" method="GET">
                    @csrf
                    <input type="hidden" name="filter" value="search">
                    <input type="text" class="input-search" name="filter_search" value="{{ session('filter_search') }}" placeholder="Search...">
                </form>
            </div>
        </div>
        @if ($selectedDraftIndicators[0]->indicator_status_id == '1')
            <div>
                <button class="manage-button" onclick="openSelectDialog()">
                    <span>Manage Indicator</span>
                </button>
            </div>
        @endif

        <div>
        @if ($selectedDraftIndicators[0]->indicator_status_id == '1')
            For Approval
        @elseif ($selectedDraftIndicators[0]->indicator_status_id == '2')
            Focal Head Reviewing
        @elseif ($selectedDraftIndicators[0]->indicator_status_id == '3')
            Planning Director Reviewing
        @elseif ($selectedDraftIndicators[0]->indicator_status_id == '4')
            Executive Final Approval
        @endif
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
        <form action="{{ route('secondaryIndicators.select')}}" method="POST">
        @csrf
            <div class="modal-main" style="display: flex;">
                    <div>
                        <table class="special-table">
                            <thead>
                                <tr>
                                    <th style="width: 20px;"></th>
                                    <th style="width: 250px;">Primary Indicator</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($selectedDraftIndicators as $indicator)
                                    <tr>
                                        <td style="width: 20px;"><input type="checkbox" name="items[]" value="{{ $indicator->id }}" checked></td>
                                        <td style="width: 250px;">{{ $indicator->indicator }}</td>
                                    </tr>
                                @endforeach
                                @foreach ($unselectedIndicators as $indicator)
                                    <tr>
                                        <td style="width: 20px;"><input type="checkbox" name="items[]" value="{{ $indicator->id }}"></td>
                                        <td style="width: 250px;">{{ $indicator->indicator }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="manage-content" style="display: none;">
                        <table class="special-table">
                            <thead>
                                <tr>
                                    <th style="width: 300px;">Operational Definition</th>
                                    <th style="width: 55px;">HNRDA</th>
                                    <th style="width: 65px;">Priority</th>
                                    <th style="width: 60px;">SDG</th>
                                    <th style="width: 120px;">Strategic Pillar</th>
                                    <th style="width: 120px;">Thematic Area</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($selectedDraftIndicators as $indicator)
                                    <tr>
                                        <td style="width: 100px;">{{ $indicator->operational_definition }}</td>
                                        <td style="width: 100px;">{{ isset($indicator->hnrda)  ?  $indicator->hnrda->title :  ''}}</td>
                                        <td style="width: 100px;">{{ isset($indicator->priority)  ?  $indicator->priority->title : '' }}</td>
                                        <td style="width: 100px;">{{ isset($indicator->sdg)  ?  $indicator->sdg->title : '' }}</td>
                                        <td style="width: 100px;">{{ isset($indicator->strategicPillar)  ?  $indicator->strategicPillar->title : '' }}</td>
                                        <td style="width: 100px;">{{ isset($indicator->thematicArea)  ?  $indicator->thematicArea->title : '' }}</td>
                                    </tr>
                                @endforeach
                                @foreach ($unselectedIndicators as $indicator)
                                    <tr>
                                        <td style="width: 100px;">{{ $indicator->operational_definition }}</td>
                                        <td style="width: 100px;">{{ isset($indicator->hnrda)  ?  $indicator->hnrda->title :  ''}}</td>
                                        <td style="width: 100px;">{{ isset($indicator->priority)  ?  $indicator->priority->title : '' }}</td>
                                        <td style="width: 100px;">{{ isset($indicator->sdg)  ?  $indicator->sdg->title : '' }}</td>
                                        <td style="width: 100px;">{{ isset($indicator->strategicPillar)  ?  $indicator->strategicPillar->title : '' }}</td>
                                        <td style="width: 100px;">{{ isset($indicator->thematicArea)  ?  $indicator->thematicArea->title : '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="extend-container">
                        <button class="button-cancel" onclick="toggleManage('manage-content', 'manage-icon')" style="border-radius: 2px; background-color: #e9e9e9;">
                            <div class="extend-arrow-container" id="manage-icon" style="transform: rotate(180deg);">
                                @include('svg.dropleft-icon')
                            </div>
                        </button>
                    </div>
                    <div class="line-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="primary-button">Save</button>
                    <button onclick="closeSelectDialog()" class="secondary-button">Close</button>
                </div>
            </form>
        </div>
    </dialog>


    <div>
    <table class="table-content">
        <thead>
            <tr>
                <th rowspan="2">Indicator</th>
                <th rowspan="2">Major Final Output</th>
                <th colspan="6" style="text-align: center;">Target</th>
                <th rowspan="2">Remarks</th>
                <th rowspan="2">Comments</th>
                @if ($selectedDraftIndicators[0]->indicator_status_id == '1')
                    <th rowspan="2">Action</th>
                @endif
            </tr>
            <tr>
                @foreach ($years as $year)
                    <th>{{ $year }}</th>
                @endforeach
            </tr>
        </thead>
    </table>
    <div class="table-container">
    <table class="table-content">
        <tbody class="table-body">
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
                        </td>

                        <td>
                            <form action="{{ route('comment.store', ['id' => $majorFinalOutput->id ]) }}" method="POST">
                                @csrf
                                <input type="text" name="comment" class="input-comment" placeholder="Add comment">
                            </form>
                        </td>

                        @if ($selectedDraftIndicators[0]->indicator_status_id == '1')
                        <td>
                            <form action="{{ route('secondaryIndicators.destroy', ['id' => $majorFinalOutput->id ]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button-action" style="margin-bottom: 5px;">–</button>
                            </form>
                            <button class="button-action" onclick="openEditDialog({{ $displayedIndicator->id }}, {{ $majorFinalOutput->id }}, {{ $counter }},
                                {{ $currentYear }}, {{ $displayedIndicator->end_year }},
                                 {
                                    @for ($year = $currentYear; $year <= $displayedIndicator->end_year; $year++)
                                       '_{{ $year }}': '{{ $successIndicators->firstWhere('year', $year)->target ??  '' }}',
                                    @endfor
                                 })"
                                    style="cursor: pointer;">/</button>
                        </td>
                        @endif
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

                    @if ($selectedDraftIndicators[0]->indicator_status_id == '1')
                    <td>
                        <button class="button-action" onclick="openCreateDialog({{ $displayedIndicator->id }}, {{ $currentYear }}, {{ $displayedIndicator->end_year }})">+</button>
                    </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    </div>

    @if ($selectedDraftIndicators[0]->indicator_status_id == '1')
        <div style="display: flex; justify-content: flex-end; margin-top: 30px;">
            <form action="{{ route('secondaryIndicators.submit') }}" method="POST">
                @csrf
                <button type="submit" class="primary-button">Submit for Approval</button>
            </form>
        </div>
    @endif


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
                storeForm.action = `/indicators/secondary/${id}/store`;

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
                updateForm.action = `/indicators/secondary/${mfoID}/update`;

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

            function toggleManage(content, dropdown) {
                var content = document.getElementById(content);
                var dropdownIcon = document.getElementById(dropdown);

                if(content.style.display == "none" || content.style.display == "") {
                    content.style.display = "block";
                    dropdownIcon.style.transform = "";
                } else {
                    content.style.display = "none";
                    dropdownIcon.style.transform = "rotate(180deg)";
                }

                event.preventDefault();
            }
        </script>
    @endpush
</x-app-layout>
