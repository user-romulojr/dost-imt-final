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

use Illuminate\Support\Facades\Route;


class PrimaryIndicatorController extends Controller
{
    public $selectLabels = [
        'hnrda' => 'HNRDA',
        'priority' => 'Priority',
        'sdg' => 'SDG',
        'strategic_pillar' => 'Strategic Pillar',
        'thematic_area' => 'Thematic Area',
    ];

    public $filterFields = [
        'hnrda',
        'priority',
        'sdg',
        'strategic_pillar',
        'thematic_area',
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

        $currentRoute = Route::currentRouteName() ?? $request->path();
        if($request->session()->has("previous_route") && $request->session()->get("previous_route") != $currentRoute){
            foreach($this->filterFields as $filterField){
                session()->forget("filter_" . $filterField . "_id");
            }
            session()->forget(['filter_search', 'filter_sort']);
        }
        $request->session()->put('previous_route', $currentRoute);

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

        $query->where('indicator_status_id', '1');

        $userId = $user->id;
        $query->whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        if($request->filter == "category"){
            foreach($this->filterFields as $filterField){
                $request->session()->put("filter_" . $filterField . "_id", $request->input("filter_" . $filterField . "_id"));
            }
        } elseif($request->filter == "search"){
            $request->session()->put('filter_search', $request->input("filter_search"));
        } elseif($request->filter == "sort"){
            $request->session()->put('filter_sort', $request->input("filter_sort"));
        }

        foreach($this->filterFields as $filterField){
            if($request->session()->has("filter_" . $filterField . "_id")){
                $query->where($filterField . "_id", $request->session()->get("filter_" . $filterField . "_id"));
            }
        }

        $search = $request->session()->get('filter_search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('indicator', 'like', '%' . $search . '%');
            });
        }

        if($request->session()->has("filter_sort")){
            $query->orderBy('indicator', $request->session()->get('filter_sort'));
        }

        $displayedIndicators = $query->get();

        return view('primary-indicators', ['selectedIndicators' => $selectedIndicators,
                                            'unselectedIndicators' => $unselectedIndicators,
                                            'displayedIndicators' => $displayedIndicators,
                                            'currentYear' => date('Y'),
                                            'years' => $years,
                                            'selectLabels' => $this->selectLabels,
                                            'selectFields' => $selectFields,
                                        ]);
    }

    public function select(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $user->indicators()->attach($request->items);

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

    public function submit(): RedirectResponse
    {
        $user = Auth::user();
        $indicators = $user->indicators;

        foreach($indicators as $indicator){
            if($indicator->indicatorStatus->id == 1){
                $indicator->indicatorStatus()->associate(2);
                $indicator->save();
            }
        }

        return redirect(route('primaryIndicators.index'));
    }

    public function approve($userID): RedirectResponse
    {
        $user = User::findOrFail($userID);
        $indicators = $user->indicators;

        $currentUser = Auth::user();
        foreach($indicators as $indicator){
            if($indicator->indicatorStatus->id == 2){
                $indicator->indicatorStatus()->associate(3);
                $indicator->save();
                $currentUser->indicators()->attach($indicator->id);
            }
        }

        return redirect(route('primaryIndicators.pendingAdmin'));
    }

    public function disapprove($userID): RedirectResponse
    {
        $user = User::findOrFail($userID);
        $indicators = $user->indicators;

        foreach($indicators as $indicator){
            if($indicator->indicatorStatus->id == 2){
                $indicator->indicatorStatus()->associate(1);
                $indicator->save();
            }
        }

        return redirect(route('primaryIndicators.pendingAdmin'));
    }

    public function pending(Request $request, $userID)
    {
        $user = User::findOrFail($userID);
        $indicators = Indicator::all();

        $selectFields = [
            'hnrda' => Hnrda::all(),
            'priority' => Priority::all(),
            'sdg' => Sdg::all(),
            'strategic_pillar' => StrategicPillar::all(),
            'thematic_area' => ThematicArea::all(),
        ];

        $currentRoute = Route::currentRouteName() ?? $request->path();
        if($request->session()->has("previous_route") && $request->session()->get("previous_route") != $currentRoute){
            foreach($this->filterFields as $filterField){
                session()->forget("filter_" . $filterField . "_id");
            }
            session()->forget(['filter_search', 'filter_sort']);
        }
        $request->session()->put('previous_route', $currentRoute);

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

        $query->where('indicator_status_id', '2');

        $userId = $user->id;
        $query->whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        if($request->filter == "category"){
            foreach($this->filterFields as $filterField){
                $request->session()->put("filter_" . $filterField . "_id", $request->input("filter_" . $filterField . "_id"));
            }
        } elseif($request->filter == "search"){
            $request->session()->put('filter_search', $request->input("filter_search"));
        } elseif($request->filter == "sort"){
            $request->session()->put('filter_sort', $request->input("filter_sort"));
        }

        foreach($this->filterFields as $filterField){
            if($request->session()->has("filter_" . $filterField . "_id")){
                $query->where($filterField . "_id", $request->session()->get("filter_" . $filterField . "_id"));
            }
        }

        $search = $request->session()->get('filter_search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('indicator', 'like', '%' . $search . '%');
            });
        }

        if($request->session()->has("filter_sort")){
            $query->orderBy('indicator', $request->session()->get('filter_sort'));
        }

        $displayedIndicators = $query->get();

        $currentUserRole = Auth::user()->accessLevel->id;
        $isAdmin = ($currentUserRole >= 2 && $currentUserRole <= 4);

        return view('primary-indicators-restricted', ['selectedIndicators' => $selectedIndicators,
                                            'unselectedIndicators' => $unselectedIndicators,
                                            'displayedIndicators' => $displayedIndicators,
                                            'currentYear' => date('Y'),
                                            'years' => $years,
                                            'selectLabels' => $this->selectLabels,
                                            'selectFields' => $selectFields,
                                            'isAdmin' => $isAdmin,
                                            'userID' => $userID,
                                        ]);
    }

    public function approved(Request $request)
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

        $currentRoute = Route::currentRouteName() ?? $request->path();
        if($request->session()->has("previous_route") && $request->session()->get("previous_route") != $currentRoute){
            foreach($this->filterFields as $filterField){
                session()->forget("filter_" . $filterField . "_id");
            }
            session()->forget(['filter_search', 'filter_sort']);
        }
        $request->session()->put('previous_route', $currentRoute);

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

        $query->where('indicator_status_id', '3');

        $userId = $user->id;
        $query->whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        if($request->filter == "category"){
            foreach($this->filterFields as $filterField){
                $request->session()->put("filter_" . $filterField . "_id", $request->input("filter_" . $filterField . "_id"));
            }
        } elseif($request->filter == "search"){
            $request->session()->put('filter_search', $request->input("filter_search"));
        } elseif($request->filter == "sort"){
            $request->session()->put('filter_sort', $request->input("filter_sort"));
        }

        foreach($this->filterFields as $filterField){
            if($request->session()->has("filter_" . $filterField . "_id")){
                $query->where($filterField . "_id", $request->session()->get("filter_" . $filterField . "_id"));
            }
        }

        $search = $request->session()->get('filter_search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('indicator', 'like', '%' . $search . '%');
            });
        }

        if($request->session()->has("filter_sort")){
            $query->orderBy('indicator', $request->session()->get('filter_sort'));
        }

        $displayedIndicators = $query->get();

        return view('primary-indicators-approved', ['selectedIndicators' => $selectedIndicators,
                                            'unselectedIndicators' => $unselectedIndicators,
                                            'displayedIndicators' => $displayedIndicators,
                                            'currentYear' => date('Y'),
                                            'years' => $years,
                                            'selectLabels' => $this->selectLabels,
                                            'selectFields' => $selectFields,
                                        ]);
    }

    public function pendingAdmin()
    {
        $users = User::all();

        $pendingIndicators = [ ];
        foreach($users as $user){
            $indicators = [ ];
            foreach($user->indicators as $indicator){
                if($indicator->indicatorStatus->id == 2){
                    array_push($indicators, $indicator);
                }
            }
            if(!empty($indicators)){
                array_push($pendingIndicators, $user);
            }
        }

        return view('pending-admin', ['pendingIndicators' => $pendingIndicators,
                                        ]);
    }
}
