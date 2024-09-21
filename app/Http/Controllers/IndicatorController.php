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

class IndicatorController extends Controller
{
    public $formFields = [
        'indicator' => ['id' => 'indicator', 'type' => 'text', 'label' => 'Indicator'],
        'operational_definition' => ['id' => 'operationalDefinition', 'type' => 'text', 'label' => 'Operational Definition'],
        'end_year' => ['id' => 'endYear', 'type' => 'number', 'label' => 'End Year'],
    ];

    public function index()
    {
        $indicators = Indicator::all();
        $selectFields = [
            'hnrda' => Hnrda::all(),
            'priority' => Priority::all(),
            'sdg' => Sdg::all(),
            'strategic_pillar' => StrategicPillar::all(),
            'thematic_area' => ThematicArea::all(),
        ];
        $selectLabels = [
            'HNRDA',
            'Priority',
            'SDG',
            'Strategic Pillar',
            'Thematic Area',
        ];

        return view('indicators', ['indicators' => $indicators, 'formFields' => $this->formFields, 'selectFields' => $selectFields, 'selectLabels' => $selectLabels]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'indicator' => ['required', 'string', 'max:255'],
            'operational_definition' => ['nullable', 'string', 'max:255'],
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

        $data = $request->validate([
            'indicator' => ['required', 'string', 'max:255'],
            'operational_definition' => ['nullable', 'string', 'max:255'],
            'end_year' => ['required', 'integer', 'digits:4'],
            'hnrda_id' => ['nullable'],
            'priority_id' => ['nullable'],
            'sdg_id' => ['nullable'],
            'strategic_pillar_id' => ['nullable'],
            'thematic_area_id' => ['nullable'],
        ]);

        $indicator->update($data);

        return redirect(route('indicators.index'));
    }

    public function destroy($indicatorID)
    {
        $indicator = Indicator::findOrFail($indicatorID);

        $indicator->delete();

        return redirect(route('indicators.index'));
    }
}
