<x-app-layout>
    <button id="openCreateDialog" class="btn btn-primary">
        Add New Indicator
    </button>

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
        <div class="dialog-container">
            <form id="createForm" action="{{ route('indicators.store') }}" method="POST">
                @csrf

                <div class="form-container">
                    <div class="label-container">
                        @foreach ($formFields as $formField)
                            <label for="{{ $formField['id'] }}">{{ $formField['label'] }}</label>
                        @endforeach
                        @foreach ($selectLabels as $selectLabel)
                            <label>{{ $selectLabel }}</label>
                        @endforeach
                    </div>

                    <div class="input-container">
                        @foreach ($formFields as $key => $formField)
                            <input type="{{ $formField['type']}}" id="{{ $formField['id'] }}" name="{{ $key }}">
                        @endforeach
                        @foreach ($selectFields as $classification => $allCategories)
                            <select id="{{ $classification }}_id" name="{{ $classification }}_id">
                                <option disabled selected>Select Option</option>
                                @foreach ($allCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
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
                        @foreach ($selectLabels as $selectLabel)
                            <label>{{ $selectLabel }}</label>
                        @endforeach
                    </div>

                    <div class="input-container">
                        @foreach ($formFields as $key => $formField)
                            <input type="{{ $formField['type']}}" id="edit_{{ $formField['id'] }}" name="{{ $key }}">
                        @endforeach
                        @foreach ($selectFields as $classification => $allCategories)
                            <select id="edit_{{ $classification }}" name="{{ $classification }}_id">
                                @foreach ($allCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" id="closeEditDialog" class="btn btn-secondary">Close</button>
                </div>
            </form>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
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
        </script>
    @endpush
</x-app-layout>
