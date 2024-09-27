<?php

namespace App\Http\Controllers;

use App\Models\ThematicArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

class ThematicAreaController extends Controller
{
    public $formFields = [
        'title' => ['id' => 'title', 'type' => 'text', 'label' => 'Title'],
    ];

    public function index(Request $request) {
        $query = ThematicArea::query();

        $currentRoute = Route::currentRouteName() ?? $request->path();
        $request->session()->put('previous_route', $currentRoute);

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        $areas = $query->get();
        return view('areas', ['areas' => $areas, 'formFields' => $this->formFields]);
    }

    public function store(Request $request): RedirectResponse
    {
        ThematicArea::create([
            'title' => $request->title,
        ]);

        return redirect(route('areas.index'));
    }

    public function update($thematicAreaID, Request $request): RedirectResponse
    {
        $thematicArea = ThematicArea::findOrFail($thematicAreaID);
        $thematicArea->update([
            'title' => $request->title,
        ]);

        return redirect(route('areas.index'));
    }

    public function destroy($thematicAreaID)
    {
        $thematicArea = ThematicArea::findOrFail($thematicAreaID);
        $thematicArea->delete();

        return redirect(route('areas.index'));
    }
}
