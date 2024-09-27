<?php

namespace App\Http\Controllers;

use App\Models\Sdg;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

class SdgController extends Controller
{
    public $formFields = [
        'title' => ['id' => 'title', 'type' => 'text', 'label' => 'Title'],
        'description' => ['id' => 'description', 'type' => 'text', 'label' => 'Description'],
    ];

    public function index(Request $request) {
        $currentRoute = Route::currentRouteName() ?? $request->path();
        $request->session()->put('previous_route', $currentRoute);
        
        $query = Sdg::query();

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        $sdgs = $query->get();
        return view('sdgs', ['sdgs' => $sdgs, 'formFields' => $this->formFields]);
    }

    public function store(Request $request): RedirectResponse
    {
        Sdg::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect(route('sdgs.index'));
    }

    public function update($sdgID, Request $request): RedirectResponse
    {
        $Sdg = Sdg::findOrFail($sdgID);
        $Sdg->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect(route('sdgs.index'));
    }

    public function destroy($sdgID)
    {
        $Sdg = Sdg::findOrFail($sdgID);
        $Sdg->delete();

        return redirect(route('sdgs.index'));
    }
}
