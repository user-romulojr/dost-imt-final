<div class="custom-dropdown">
    <div class="dropdown-button" onclick="toggleContent('dropdown-content-id', 'dropdown-button')">
        <span>Filter By</span>
        @include('svg.dropdown-icon')
    </div>
    <div class="dropdown-content" id="dropdown-content-id">
        <form action={{ $route }} method="GET">
            @csrf
            <div class="form-container">
                <div class="label-container" style="width: 120px">
                    @foreach ($selectLabels as $selectLabel)
                        <label>{{ $selectLabel }}</label>
                    @endforeach
                </div>

                <div class="input-container">
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
                <button type="submit" class="btn btn-success">Filter</button>
                <button type="button" onclick="toggleContent('dropdown-content-id', 'dropdown-button')">Close</button>
            </div>
        </form>
    </div>
</div>
