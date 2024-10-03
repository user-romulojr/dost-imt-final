<x-app-layout>
    <x-title-page>Primary Indicators</x-title-page>

    <x-horizontal-line></x-horizontal-line>

    <div style="display: flex; flex-direction: column; align-content: center; justify-content: center; margin-top: 250px;">
        <div style="text-align: center;">No data to display</div>
        <div style="text-align: center;">Please select your primary indicators</div>
        <div style="display: flex; align-content: center; justify-content: center;">
            <button class="manage-button" onclick="openSelectDialog()">
                <span>Start</span>
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
