<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\Hnrda;
use App\Models\Sdg;

use App\Http\Controllers\Controller;
use App\Models\Priority;
use App\Models\StrategicPillar;
use App\Models\ThematicArea;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class IndicatorController extends Controller
{
    public $formFields = [
        'indicator' => ['id' => 'indicator', 'type' => 'text', 'label' => 'Indicator'],
        'operational_definition' => ['id' => 'operationalDefinition', 'type' => 'text', 'label' => 'Operational Definition'],
        'end_year' => ['id' => 'endYear', 'type' => 'number', 'label' => 'End Year'],
    ];

    public $selectLabels = [
        'hnrda' => 'HNRDA',
        'priority' => 'Priority',
        'sdg' => 'SDG',
        'strategic_pillar' => 'Strategic Pillar',
        'thematic_area' => 'Thematic Area',
    ];

    public $filterFields = [
        'hnrda',
        'priority',
        'sdg',
        'strategic_pillar',
        'thematic_area',
    ];

    public function index(Request $request)
    {
        $selectFields = [
            'hnrda' => Hnrda::all(),
            'priority' => Priority::all(),
            'sdg' => Sdg::all(),
            'strategic_pillar' => StrategicPillar::all(),
            'thematic_area' => ThematicArea::all(),
        ];

        $currentRoute = Route::currentRouteName() ?? $request->path();
        if($request->session()->has("previous_route") && $request->session()->get("previous_route") != $currentRoute){
            foreach($this->filterFields as $filterField){
                session()->forget("filter_" . $filterField . "_id");
            }
            session()->forget(['filter_search', 'filter_sort']);
        }
        $request->session()->put('previous_route', $currentRoute);

        $query = Indicator::query();

        if($request->filter == "category"){
            foreach($this->filterFields as $filterField){
                $request->session()->put("filter_" . $filterField . "_id", $request->input("filter_" . $filterField . "_id"));
            }
        } elseif($request->filter == "search"){
            $request->session()->put('filter_search', $request->input("filter_search"));
        } elseif($request->filter == "sort"){
            $request->session()->put('filter_sort', $request->input("filter_sort"));
        }

        foreach($this->filterFields as $filterField){
            if($request->session()->has("filter_" . $filterField . "_id")){
                $query->where($filterField . "_id", $request->session()->get("filter_" . $filterField . "_id"));
            }
        }

        $search = $request->session()->get('filter_search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('indicator', 'like', '%' . $search . '%');
            });
        }

        if($request->session()->has("filter_sort")){
            $query->orderBy('indicator', $request->session()->get('filter_sort'));
        }

        $indicators = $query->get();

        return view('indicators', ['indicators' => $indicators,
                                    'formFields' => $this->formFields,
                                    'selectFields' => $selectFields,
                                    'selectLabels' => $this->selectLabels,
                                    'filterFields' => $this->filterFields,
                                ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'indicator' => ['required', 'string', 'max:255'],
            'operational_definition' => ['nullable', 'string', 'max:255'],
            'type' => ['required'],
            'end_year' => ['required', 'integer', 'digits:4'],
            'hnrda_id' => ['nullable'],
            'priority_id' => ['nullable'],
            'sdg_id' => ['nullable'],
            'strategic_pillar_id' => ['nullable'],
            'thematic_area_id' => ['nullable'],
        ]);

        Indicator::create([
            'indicator' => $request->indicator,
            'operational_definition' => $request->operational_definition,
            'is_primary' => ($request->type == '1'),
            'end_year' => $request->end_year,
            'hnrda_id' => $request->hnrda_id,
            'priority_id' => $request->priority_id,
            'sdg_id' => $request->sdg_id,
            'strategic_pillar_id' => $request->strategic_pillar_id,
            'thematic_area_id' => $request->thematic_area_id,
        ]);

        return redirect(route('indicators.index'));
    }

    public function update($indicatorID, Request $request): RedirectResponse
    {
        $indicator = Indicator::findOrFail($indicatorID);

        $request->validate([
            'indicator' => ['required', 'string', 'max:255'],
            'operational_definition' => ['nullable', 'string', 'max:255'],
            'type' => ['required'],
            'end_year' => ['required', 'integer', 'digits:4'],
            'hnrda_id' => ['nullable'],
            'priority_id' => ['nullable'],
            'sdg_id' => ['nullable'],
            'strategic_pillar_id' => ['nullable'],
            'thematic_area_id' => ['nullable'],
        ]);

        $indicator->update([
            'indicator' => $request->indicator,
            'operational_definition' => $request->operational_definition,
            'is_primary' => ($request->type == '1'),
            'end_year' => $request->end_year,
            'hnrda_id' => $request->hnrda_id,
            'priority_id' => $request->priority_id,
            'sdg_id' => $request->sdg_id,
            'strategic_pillar_id' => $request->strategic_pillar_id,
            'thematic_area_id' => $request->thematic_area_id,
        ]);

        return redirect(route('indicators.index'));
    }

    public function destroy($indicatorID)
    {
        $indicator = Indicator::findOrFail($indicatorID);

        $indicator->delete();

        return redirect(route('indicators.index'));
    }

    public function agencyIndicators(Request $request)
    {
        $selectFields = [
            'hnrda' => Hnrda::all(),
            'priority' => Priority::all(),
            'sdg' => Sdg::all(),
            'strategic_pillar' => StrategicPillar::all(),
            'thematic_area' => ThematicArea::all(),
        ];

        $currentRoute = Route::currentRouteName() ?? $request->path();
        if($request->session()->has("previous_route") && $request->session()->get("previous_route") != $currentRoute){
            foreach($this->filterFields as $filterField){
                session()->forget("filter_" . $filterField . "_id");
            }
            session()->forget(['filter_search', 'filter_sort']);
        }
        $request->session()->put('previous_route', $currentRoute);

        $user = Auth::user();

        $query = $user->indicators();

        if($request->filter == "category"){
            foreach($this->filterFields as $filterField){
                $request->session()->put("filter_" . $filterField . "_id", $request->input("filter_" . $filterField . "_id"));
            }
        } elseif($request->filter == "search"){
            $request->session()->put('filter_search', $request->input("filter_search"));
        } elseif($request->filter == "sort"){
            $request->session()->put('filter_sort', $request->input("filter_sort"));
        }

        foreach($this->filterFields as $filterField){
            if($request->session()->has("filter_" . $filterField . "_id")){
                $query->where($filterField . "_id", $request->session()->get("filter_" . $filterField . "_id"));
            }
        }

        $search = $request->session()->get('filter_search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('indicator', 'like', '%' . $search . '%');
            });
        }

        if($request->session()->has("filter_sort")){
            $query->orderBy('indicator', $request->session()->get('filter_sort'));
        }

        $indicators = $query->get();

        return view('agency-indicators', ['indicators' => $indicators,
                                    'formFields' => $this->formFields,
                                    'selectFields' => $selectFields,
                                    'selectLabels' => $this->selectLabels,
                                    'filterFields' => $this->filterFields,
                                ]);
    }

    public function agencyIndicatorsStore(Request $request): RedirectResponse
    {
        $request->validate([
            'indicator' => ['required', 'string', 'max:255'],
            'operational_definition' => ['nullable', 'string', 'max:255'],
            'type' => ['required'],
            'end_year' => ['required', 'integer', 'digits:4'],
            'hnrda_id' => ['nullable'],
            'priority_id' => ['nullable'],
            'sdg_id' => ['nullable'],
            'strategic_pillar_id' => ['nullable'],
            'thematic_area_id' => ['nullable'],
        ]);

        $indicator = Indicator::create([
            'indicator' => $request->indicator,
            'operational_definition' => $request->operational_definition,
            'is_primary' => ($request->type == '1'),
            'end_year' => $request->end_year,
            'hnrda_id' => $request->hnrda_id,
            'priority_id' => $request->priority_id,
            'sdg_id' => $request->sdg_id,
            'strategic_pillar_id' => $request->strategic_pillar_id,
            'thematic_area_id' => $request->thematic_area_id,
        ]);

        $user = Auth::user();
        $user->indicators()->attach($indicator);

        return redirect(route('agencyIndicators.index'));
    }

    public function agencyIndicatorsUpdate($indicatorID, Request $request): RedirectResponse
    {
        $indicator = Indicator::findOrFail($indicatorID);

        $request->validate([
            'indicator' => ['required', 'string', 'max:255'],
            'operational_definition' => ['nullable', 'string', 'max:255'],
            'type' => ['required'],
            'end_year' => ['required', 'integer', 'digits:4'],
            'hnrda_id' => ['nullable'],
            'priority_id' => ['nullable'],
            'sdg_id' => ['nullable'],
            'strategic_pillar_id' => ['nullable'],
            'thematic_area_id' => ['nullable'],
        ]);

        $indicator->update([
            'indicator' => $request->indicator,
            'operational_definition' => $request->operational_definition,
            'is_primary' => ($request->type == '1'),
            'end_year' => $request->end_year,
            'hnrda_id' => $request->hnrda_id,
            'priority_id' => $request->priority_id,
            'sdg_id' => $request->sdg_id,
            'strategic_pillar_id' => $request->strategic_pillar_id,
            'thematic_area_id' => $request->thematic_area_id,
        ]);

        return redirect(route('agencyIndicators.index'));
    }

    public function agencyIndicatorsDestroy($indicatorID)
    {
        $indicator = Indicator::findOrFail($indicatorID);

        $indicator->delete();

        return redirect(route('agencyIndicators.index'));
    }
}
