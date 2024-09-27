<?php

namespace App\Http\Controllers;

use App\Models\Priority;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

class PriorityController extends Controller
{
    public $formFields = [
        'title' => ['id' => 'title', 'type' => 'text', 'label' => 'Title'],
    ];

    public function index(Request $request) {
        $currentRoute = Route::currentRouteName() ?? $request->path();
        $request->session()->put('previous_route', $currentRoute);
        
        $query = Priority::query();

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        $priorities = $query->get();
        return view('priorities', ['priorities' => $priorities, 'formFields' => $this->formFields]);
    }

    public function store(Request $request): RedirectResponse
    {
        Priority::create([
            'title' => $request->title,
        ]);

        return redirect(route('priorities.index'));
    }

    public function update($PriorityID, Request $request): RedirectResponse
    {
        $Priority = Priority::findOrFail($PriorityID);
        $Priority->update([
            'title' => $request->title,
        ]);

        return redirect(route('priorities.index'));
    }

    public function destroy($PriorityID)
    {
        $Priority = Priority::findOrFail($PriorityID);
        $Priority->delete();

        return redirect(route('priorities.index'));
    }
}
