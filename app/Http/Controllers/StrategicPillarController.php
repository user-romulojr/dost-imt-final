<?php

namespace App\Http\Controllers;

use App\Models\StrategicPillar;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StrategicPillarController extends Controller
{
    public $formFields = [
        'title' => ['id' => 'title', 'type' => 'text', 'label' => 'Title'],
    ];

    public function index() {
        $pillars = StrategicPillar::all();
        return view('pillars', ['pillars' => $pillars, 'formFields' => $this->formFields]);
    }

    public function store(Request $request): RedirectResponse
    {
        StrategicPillar::create([
            'title' => $request->title,
        ]);

        return redirect(route('pillars.index'));
    }

    public function update($strategicPillarID, Request $request): RedirectResponse
    {
        $strategicPillar = StrategicPillar::findOrFail($strategicPillarID);
        $strategicPillar->update([
            'title' => $request->title,
        ]);

        return redirect(route('pillars.index'));
    }

    public function destroy($strategicPillarID)
    {
        $StrategicPillar = StrategicPillar::findOrFail($strategicPillarID);
        $StrategicPillar->delete();

        return redirect(route('pillars.index'));
    }
}
