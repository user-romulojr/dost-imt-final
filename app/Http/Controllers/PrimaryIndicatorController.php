<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Indicator;
use App\Models\MajorFinalOutput;
use App\Models\SuccessIndicator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PrimaryIndicatorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $indicators = Indicator::all();

        $selectedIndicators = $user->indicators;
        $unselectedIndicators = array();
        foreach($indicators as $indicator)
        {
            if(!$selectedIndicators->contains($indicator)){
                array_push($unselectedIndicators, $indicator);
            }
        }

        $years = array();
        $currentYear = date('Y');
        for($i = 0; $i < 6; $i++)
        {
            array_push($years, $currentYear + $i);
        }

        return view('primary-indicators', ['selectedIndicators' => $selectedIndicators,
                                            'unselectedIndicators' => $unselectedIndicators,
                                            'currentYear' => $currentYear,
                                            'years' => $years]);
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

        $currentYear = date('Y');
        for($year = $currentYear; $year <= $indicator->end_year; $year++)
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
