<x-app-layout>
    <x-title-page>Primary Indicators</x-title-page>

    <x-horizontal-line></x-horizontal-line>

    <div class="options-container">
        <div style="display: flex; gap: 30px;">
            <div class="custom-dropdown">
                <div class="dropdown-button" onclick="toggleContent('dropdown-content-id', 'dropdown-button')">
                    <span>Filter By</span>
                    <div>@include('svg.dropdown-icon')</div>
                </div>

                <div class="dropdown-content" id="dropdown-content-id">
                    <div class="dropdown-header">
                        <span>Filter By</span>
                        <div class="close-icon-container" onclick="toggleContent('dropdown-content-id', 'dropdown-button')">@include('svg.close-icon')</div>
                    </div>
                    <form action="{{ route('indicators.index')}}" method="GET">
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
                <form action="{{ route('indicators.index')}}" method="GET">
                    @csrf
                    <input type="text" class="input-search" name="search" placeholder="Search...">
                </form>
            </div>
        </div>
        <div>
            <button class="manage-button" id="openCreateDialog">
                @include('svg.gear-icon')
                <span>Add Primary Indicator</span>
            </button>
        </div>
    </div>

    <table class="table-content">
        <thead>
            <tr>
                <th>Indicator</th>
                <th>Operational Definition</th>
                <th>HNRDA</th>
                <th>Priority</th>
                <th>SDG</th>
                <th>Strategic Pillar</th>
                <th>Thematic Area</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($indicators as $indicator)
                <tr onclick="openEditDialog(
                    '{{ $indicator->id }}',
                    '{{ $indicator->indicator }}',
                    '{{ $indicator->operational_definition }}',
                    '{{ $indicator->end_year }}',
                    '{{ isset($indicator->hnrda)  ?  $indicator->hnrda->id :  ''}}',
                    '{{ isset($indicator->priority)  ?  $indicator->priority->id : '' }}',
                    '{{ isset($indicator->sdg)  ?  $indicator->sdg->id : '' }}',
                    '{{ isset($indicator->strategicPillar)  ?  $indicator->strategicPillar->id : '' }}',
                    '{{ isset($indicator->thematicArea)  ?  $indicator->thematicArea->id : '' }}',
                )" style="cursor: pointer;">
                    <td>{{ $indicator->indicator }}</td>
                    <td>{{ $indicator->operational_definition }}</td>
                    <td>{{ isset($indicator->hnrda)  ?  $indicator->hnrda->title :  ''}}</td>
                    <td>{{ isset($indicator->priority)  ?  $indicator->priority->title : '' }}</td>
                    <td>{{ isset($indicator->sdg)  ?  $indicator->sdg->title : '' }}</td>
                    <td>{{ isset($indicator->strategicPillar)  ?  $indicator->strategicPillar->title : '' }}</td>
                    <td>{{ isset($indicator->thematicArea)  ?  $indicator->thematicArea->title : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <dialog id="createDialog">
        <div class="modal-content" id="modal-content-id">
            <div class="modal-header">
                <span>Add Primary Indicator</span>
                <div class="close-icon-container" onclick="closeDialog('createDialog')">@include('svg.close-icon')</div>
            </div>
            <form method="POST" action="{{ route('indicators.store') }}" id="createForm">
                @csrf
                <div class="modal-main">
                    @foreach ($formFields as $key => $formField)
                        <div class="input-container">
                            <label for="{{ $formField['id'] }}">{{ $formField['label'] }}</label>
                            <input type="{{ $formField['type']}}" id="{{ $formField['id'] }}" class="input-layout" name="{{ $key }}">
                        </div>
                    @endforeach
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
                <span>Update Primary Indicator</span>
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
                        @foreach ($selectFields as $classification => $allCategories)
                            <label>{{ $selectLabels[$classification] }}</label>
                            <select class="select-input" id="edit_{{ $classification }}" name="{{ $classification }}_id">
                                @foreach ($allCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
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
            function openEditDialog(id, indicator, operationalDefinition, endYear, hnrda, priority, sdg, strategicPillar, thematicArea) {
                const editForm = document.getElementById('editForm');
                editForm.action = `/indicators/${id}/update`;
                document.getElementById('edit_indicator').value = indicator;
                document.getElementById('edit_operationalDefinition').value = operationalDefinition;
                document.getElementById('edit_endYear').value = endYear;
                document.getElementById('edit_hnrda').value = hnrda;
                document.getElementById('edit_priority').value = priority;
                document.getElementById('edit_sdg').value = sdg;
                document.getElementById('edit_strategic_pillar').value = strategicPillar;
                document.getElementById('edit_thematic_area').value = thematicArea;
                document.getElementById('editDialog').showModal();

                const deleteForm = document.getElementById('deleteForm');
                deleteForm.action = `/indicators/${id}/delete`
            }

            function closeDialog(dialog) {
                event.preventDefault();
                const dialogContainer = document.getElementById(dialog);

                dialogContainer.close();
            }
        </script>
    @endpush
</x-app-layout>
