<?php

namespace App\Http\Controllers;

use App\Models\StrategicPillar;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

class StrategicPillarController extends Controller
{
    public $formFields = [
        'title' => ['id' => 'title', 'type' => 'text', 'label' => 'Title'],
    ];

    public function index(Request $request) {
        $currentRoute = Route::currentRouteName() ?? $request->path();
        $request->session()->put('previous_route', $currentRoute);
        
        $query = StrategicPillar::query();

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        $pillars = $query->get();
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
