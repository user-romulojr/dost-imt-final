<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Indicator;
use App\Models\MajorFinalOutput;
use App\Models\SuccessIndicator;
use App\Models\Hnrda;
use App\Models\Priority;
use App\Models\Sdg;
use App\Models\StrategicPillar;
use App\Models\ThematicArea;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PrimaryIndicatorController extends Controller
{
    public $selectLabels = [
        'hnrda' => 'HNRDA',
        'priority' => 'Priority',
        'sdg' => 'SDG',
        'strategic_pillar' => 'Strategic Pillar',
        'thematic_area' => 'Thematic Area',
    ];

    public function index(Request $request)
    {
        $user = Auth::user();
        $indicators = Indicator::all();

        $selectFields = [
            'hnrda' => Hnrda::all(),
            'priority' => Priority::all(),
            'sdg' => Sdg::all(),
            'strategic_pillar' => StrategicPillar::all(),
            'thematic_area' => ThematicArea::all(),
        ];

        $selectedIndicators = $user->indicators;
        $unselectedIndicators = array();
        foreach($indicators as $indicator)
        {
            if(!$selectedIndicators->contains($indicator)){
                array_push($unselectedIndicators, $indicator);
            }
        }

        $years = array();
        $endYear = date('Y');
        while($endYear % 6 != 0){
            $endYear++;
        }

        for($i = $endYear - 5; $i <= $endYear; $i++)
        {
            array_push($years, $i);
        }

        $query = Indicator::query();

        $userId = $user->id;
        $query->whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        if($request->has('hnrda_id')){
            $query->where('hnrda_id', $request->input('hnrda_id'));
        }

        if($request->has('priority_id')){
            $query->where('priority_id', $request->input('priority_id'));
        }

        if($request->has('sdg_id')){
            $query->where('sdg_id', $request->input('sdg_id'));
        }

        if($request->has('strategic_pillar_id')){
            $query->where('strategic_pillar_id', $request->input('strategic_pillar_id'));
        }

        if($request->has('thematic_area_id')){
            $query->where('thematic_area_id', $request->input('thematic_area_id'));
        }

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('indicator', 'like', '%' . $search . '%');
            });
        }

        $displayedIndicators = $query->get();

        return view('primary-indicators', ['selectedIndicators' => $selectedIndicators,
                                            'unselectedIndicators' => $unselectedIndicators,
                                            'displayedIndicators' => $displayedIndicators,
                                            'currentYear' => date('Y'),
                                            'years' => $years,
                                            'selectLabels' => $this->selectLabels,
                                            'selectFields' => $selectFields,]);
    }

    public function select(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $user->indicators()->attach($request->items);

        return redirect(route('primaryIndicators.index'));
    }

    public function store(Request $request, $indicatorID): RedirectResponse
    {
        $indicator = Indicator::find($indicatorID);
        $majorFinalOutput = new MajorFinalOutput();
        $majorFinalOutput->major_final_output = $request->major_final_output;

        $indicator->majorFinalOutputs()->save($majorFinalOutput);

        $endYear = date('Y');
        for($year = $endYear; $year <= $indicator->end_year; $year++)
        {
            $majorFinalOutput->successIndicators()->create([
                'year' => $year,
                'target' => $request->$year,
            ]);
        }

        return redirect(route('primaryIndicators.index'));
    }

    public function update($majorFinalOutputID, Request $request): RedirectResponse
    {
        $majorFinalOutput = MajorFinalOutput::findOrFail($majorFinalOutputID);
        $majorFinalOutput->update([
            'major_final_output' => $request->major_final_output,
        ]);

        $successIndicators = $majorFinalOutput->successIndicators()->get();
        foreach($successIndicators as $successIndicator){
            $year = $successIndicator->year;
            $successIndicator->update([
                'target' => $request->$year,
            ]);
        }

        return redirect(route('primaryIndicators.index'));
    }

    public function destroy($majorFinalOutputID): RedirectResponse
    {
        $majorFinalOutput = MajorFinalOutput::findOrFail($majorFinalOutputID);

        $majorFinalOutput->delete();

        return redirect(route('primaryIndicators.index'));
    }
}
