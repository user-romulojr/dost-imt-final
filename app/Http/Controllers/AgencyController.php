<?php

namespace App\Http\Controllers;

use App\Models\Agency;

use App\Http\Controllers\Controller;
use App\Models\AgencyGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public $formFields = [
        'agency' => ['id' => 'agency', 'type' => 'text', 'label' => 'Agency'],
        'acronym' => ['id' => 'acronym', 'type' => 'text', 'label' => 'Acronym'],
        'group' => ['id' => 'group', 'type' => 'number', 'label' => 'Agency Group'],
        'contact' => ['id' => 'contact', 'type' => 'text', 'label' => 'Contact'],
        'website' => ['id' => 'website', 'type' => 'text', 'label' => 'Website'],
    ];


    public function index() {
        $agencies = Agency::all();
        $agencyGroups = AgencyGroup::all();

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
