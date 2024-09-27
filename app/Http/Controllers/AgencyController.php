<?php

namespace App\Http\Controllers;

use App\Models\Agency;

use App\Http\Controllers\Controller;
use App\Models\AgencyGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

class AgencyController extends Controller
{
    public $formFields = [
        'agency' => ['id' => 'agency', 'type' => 'text', 'label' => 'Agency'],
        'acronym' => ['id' => 'acronym', 'type' => 'text', 'label' => 'Acronym'],
        'group' => ['id' => 'group', 'type' => 'number', 'label' => 'Agency Group'],
        'contact' => ['id' => 'contact', 'type' => 'text', 'label' => 'Contact'],
        'website' => ['id' => 'website', 'type' => 'text', 'label' => 'Website'],
    ];

    public $filterFields = [
        'agency_group',
    ];


    public function index(Request $request) {
        $agencyGroups = AgencyGroup::all();

        $currentRoute = Route::currentRouteName() ?? $request->path();
        if($request->session()->has("previous_route") && $request->session()->get("previous_route") != $currentRoute){
            foreach($this->filterFields as $filterField){
                session()->forget("filter_" . $filterField . "_id");
            }
            session()->forget(['filter_search', 'filter_sort']);
        }
        $request->session()->put('previous_route', $currentRoute);

        if($request->filter == "category"){
            foreach($this->filterFields as $filterField){
                $request->session()->put("filter_" . $filterField . "_id", $request->input("filter_" . $filterField . "_id"));
            }
        } elseif($request->filter == "search"){
            $request->session()->put('filter_search', $request->input("filter_search"));
        } elseif($request->filter == "sort"){
            $request->session()->put('filter_sort', $request->input("filter_sort"));
        }

        $query = Agency::query();
        foreach($this->filterFields as $filterField){
            if($request->session()->has("filter_" . $filterField . "_id")){
                $query->where($filterField . "_id", $request->session()->get("filter_" . $filterField . "_id"));
            }
        }

        $search = $request->session()->get('filter_search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('agency', 'like', '%' . $search . '%');
            });
        }

        if($request->session()->has("filter_sort")){
            $query->orderBy('agency', $request->session()->get('filter_sort'));
        }

        $agencies = $query->get();


        return view('agencies', ['agencies' => $agencies, 'formFields' => $this->formFields, 'agencyGroups' => $agencyGroups]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'agency' => 'required',
            'acronym' => 'required',
            'agency_group_id' => 'nullable',
            'contact' => 'nullable',
            'website' => 'nullable',
        ]);

        Agency::create([
            'agency' => $request->agency,
            'acronym' => $request->acronym,
            'agency_group_id' => $request->agency_group_id,
            'contact' => $request->contact,
            'website' => $request->website,
        ]);

        return redirect(route('agencies.index'));
    }

    public function update($agencyID, Request $request): RedirectResponse
    {
        $agency = Agency::findOrFail($agencyID);
        $agency->update([
            'agency' => $request->agency,
            'acronym' => $request->acronym,
            'group' => $request->group,
            'contact' => $request->contact,
            'website' => $request->website,
        ]);

        return redirect(route('agencies.index'));
    }

    public function destroy($agencyID)
    {
        $agency = Agency::findOrFail($agencyID);
        $agency->delete();

        return redirect(route('agencies.index'));
    }
}
