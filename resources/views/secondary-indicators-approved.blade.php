<x-app-layout>
    <x-title-page>Secondary Indicators</x-title-page>

    <x-horizontal-line></x-horizontal-line>

    <div class="options-container">
        <div style="display: flex; gap: 30px;">
            <div style="display: flex; gap: 10px;">
                <div>
                    <form action="{{ auth()->user()->isAdmin() ? route('secondaryIndicators.pendingAdmin') : route('secondaryIndicators.index') }}" method="GET">
                        <button type="submit" class="secondary-button">{{ auth()->user()->isAdmin() ? 'Pending' : 'Draft'}}</button>
                    </form>
                </div>
                <div>
                    <form action="{{ route('secondaryIndicators.approved') }}" method="GET">
                        <button type="submit" class="secondary-button">Approved</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @php
        $counter = 1;
    @endphp


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
        <tbody class="table-body">
            @foreach ($approvedIndicators as $approvedIndicator)
                <tr style="cursor: pointer;" onclick="openApproveDialog('{{ $approvedIndicator->id }}')">
                    <td>{{ $counter }}</td>
                    <td>{{ isset($approvedIndicator->agency->agency) ?  $approvedIndicator->agency->agency : ''}}</td>
                    <td>{{ isset($approvedIndicator->agency->agencyGroup) ? $approvedIndicator->agency->agencyGroup : '' }}</td>
                    <td>{{ $approvedIndicator->created_at->format('j F Y g:i A') }}</td>
                    <td>Approved</td>
                    @php
                        $counter++;
                    @endphp
                </tr>
            @endforeach
        </tbody>
    </table>

    @foreach ($approvedIndicators as $approvedIndicator)
        <dialog id="approveDialog-{{ $approvedIndicator->id }}">
            <div class="modal-content" id="modal-content-id">
                <div class="modal-header">
                    <span>Approve Indicators</span>
                    <div class="close-icon-container" onclick="closeDialog('approveDialog-{{ $approvedIndicator->id }}')">@include('svg.close-icon')</div>
                </div>
                    <div class="modal-main">
                        <table class="table-content">
                            <thead>
                                <tr>
                                    <th rowspan="2">Indicator</th>
                                    <th rowspan="2">Major Final Output</th>
                                    <th colspan="6" style="text-align: center;">Target</th>
                                    <th rowspan="2">Remarks</th>
                                    <th rowspan="2">Comments</th>
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
                                @foreach ($approvedIndicator->indicators as $indicator)
                                    @php
                                        $mfoCount = $indicator->majorFinalOutputs()->count();
                                        $counter = 0;
                                    @endphp

                                    @foreach ($indicator->majorFinalOutputs as $majorFinalOutput)
                                        @php
                                            $counter++;
                                            $successIndicators = $majorFinalOutput->successIndicators;
                                        @endphp
                                        <tr
                                        >
                                        {{-- style="{{ $counter == $mfoCount ? 'border-bottom: 1px solid #CBCBCB;' : '' }}" --}}
                                            @if ($counter == 1)
                                                <td rowspan={{ $mfoCount }}>
                                                    {{ $indicator->indicator }}
                                                </td>
                                            @endif

                                            <td id="mfo-{{ $indicator->id }}-{{ $counter }}">
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
                                        </tr>
                                    @endforeach

                                    <tr style="border-bottom: 1px solid #CBCBCB;">
                                        <td>
                                            @if ($mfoCount == 0)
                                                {{ $indicator->indicator }}
                                            @endif
                                        </td>

                                        <td>

                                        </td>

                                        @foreach ($years as $year)
                                            <td></td>
                                        @endforeach

                                        <td>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button class="secondary-button" onclick="closeDialog('approveDialog-{{ $approvedIndicator->id }}')">Close</button>
                    </div>
                </div>
        </dialog>
    @endforeach


    @push('script')
        <script>
            function openSelectDialog() {
                document.getElementById('selectDialog').showModal();
            }

            function closeSelectDialog() {
                event.preventDefault();
                document.getElementById('selectDialog').close();
            }

            function openApproveDialog(id) {
                document.getElementById('approveDialog-' + id).showModal();
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
        </script>
    @endpush
</x-app-layout>
