<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Indicator;
use App\Models\MajorFinalOutput;
use App\Models\SuccessIndicator;
use App\Models\Hnrda;
use App\Models\IndicatorsGroup;
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

class SecondaryIndicatorController extends Controller
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

    public $forApproval = [
        User::ROLE_AH => 2,
        User::ROLE_PD => 3,
        User::ROLE_EXEC => 4,
    ];

    public function index(Request $request)
    {
        $user = Auth::user();
        $indicators = Indicator::all()->where('indicator_status_id', '!=', '5')->where('is_primary', false);

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

        $selectedDraftIndicators = [ ];
        $selectedDraftIndicatorIDs = [ ];
        foreach($selectedIndicators as $selectedIndicator)
        {
            if($selectedIndicator->indicator_status_id != '5' && !$selectedIndicator->is_primary){
                array_push($selectedDraftIndicators, $selectedIndicator);
                array_push($selectedDraftIndicatorIDs, $selectedIndicator->id);
            }
        }

        $unselectedIndicators = array();
        foreach($indicators as $indicator)
        {
            if(!in_array($indicator->id, $selectedDraftIndicatorIDs)){
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

        $query->where('is_primary', false);
        $query->where('indicator_status_id', '!=', '5');

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



        if(empty($selectedDraftIndicators)) {
            return view('secondary-indicators-empty', ['selectedDraftIndicators' => $selectedDraftIndicators,
                                            'unselectedIndicators' => $unselectedIndicators,
                                            'displayedIndicators' => $displayedIndicators,
                                            'currentYear' => date('Y'),
                                            'years' => $years,
                                            'selectLabels' => $this->selectLabels,
                                            'selectFields' => $selectFields,
                                            'filterFields' => $this->filterFields,
                                        ]);
        }

        return view('secondary-indicators', ['selectedDraftIndicators' => $selectedDraftIndicators,
                                            'unselectedIndicators' => $unselectedIndicators,
                                            'displayedIndicators' => $displayedIndicators,
                                            'currentYear' => date('Y'),
                                            'years' => $years,
                                            'selectLabels' => $this->selectLabels,
                                            'selectFields' => $selectFields,
                                            'filterFields' => $this->filterFields,
                                        ]);
    }

    public function select(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $user->indicators()->sync($request->items);

        return redirect(route('secondaryIndicators.index'));
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

        return redirect(route('secondaryIndicators.index'));
    }

    public function destroy($majorFinalOutputID): RedirectResponse
    {
        $majorFinalOutput = MajorFinalOutput::findOrFail($majorFinalOutputID);

        $majorFinalOutput->delete();

        return redirect(route('secondaryIndicators.index'));
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

        return redirect(route('secondaryIndicators.index'));
    }

    public function submit(): RedirectResponse
    {
        $user = Auth::user();
        $indicators = $user->indicators;

        $indicatorsGroup = IndicatorsGroup::create([
            'user_id' => $user->id,
            'agency_id' => $user->agency_id,
            'indicator_status_id' => 2,
        ]);

        foreach($indicators as $indicator){
            if($indicator->indicatorStatus->id == 1){
                $indicator->indicatorStatus()->associate(2);
                $indicator->save();
                $indicatorsGroup->indicators()->save($indicator);
            }
        }

        return redirect(route('secondaryIndicators.index'));
    }

    public function approve($indicatorsGroupID): RedirectResponse
    {
        $indicatorsGroup = IndicatorsGroup::findOrFail($indicatorsGroupID);
        $indicators = $indicatorsGroup->indicators;
        $user = Auth::user();

        foreach($indicators as $indicator){
            if($indicator->indicatorStatus->id == $this->forApproval[$user->access_level_id] ){
                $indicator->indicatorStatus()->associate($this->forApproval[$user->access_level_id] + 1);
                $indicator->save();
            }
        }

        if($indicators->first()->indicatorStatus->id == 5 && !$indicator->is_approved){
            foreach($indicators as $indicator){
                $indicator->indicatorStatus()->associate(1);
                $indicator->is_approved = true;
                $indicator->save();
            }
            $indicatorsGroup->is_approved = true;
        }

        $indicatorsGroup->indicatorStatus()->associate($this->forApproval[$user->access_level_id] + 1);

        if($user->access_level_id == User::ROLE_AH) {
            $indicatorsGroup->agency_head_approver_id = $user->id;
        } else if($user->access_level_id == User::ROLE_PD) {
            $indicatorsGroup->planning_director_approver_id = $user->id;
        } else if($user->access_level_id == User::ROLE_EXEC) {
            $indicatorsGroup->executive_approver_id = $user->id;
        }

        $indicatorsGroup->save();

        return redirect(route('secondaryIndicators.pendingAdmin'));
    }

    public function disapprove($indicatorsGroupID): RedirectResponse
    {
        $indicatorsGroup = IndicatorsGroup::findOrFail($indicatorsGroupID);
        $indicators = $indicatorsGroup->indicators;
        $user = Auth::user();

        foreach($indicators as $indicator){
            if($indicator->indicatorStatus->id == $this->forApproval[$user->access_level_id] ){
                $indicator->indicatorStatus()->associate($this->forApproval[$user->access_level_id] + 1);
                $indicator->is_approved = false;
                $indicator->save();
            }
        }

        if($indicators->first()->indicatorStatus->id == 5 && !$indicator->is_approved){
            foreach($indicators as $indicator){
                $indicator->indicatorStatus()->associate(1);
                $indicator->is_approved = true;
                $indicator->save();
            }
            $indicatorsGroup->is_approved = true;
        }

        $indicatorsGroup->indicatorStatus()->associate($this->forApproval[$user->access_level_id] + 1);
        $indicatorsGroup->is_approved = false;
        $indicatorsGroup->save();

        return redirect(route('secondaryIndicators.pendingAdmin'));
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

        return view('secondary-indicators-restricted', ['selectedIndicators' => $selectedIndicators,
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

        $currentRoute = Route::currentRouteName() ?? $request->path();
        if($request->session()->has("previous_route") && $request->session()->get("previous_route") != $currentRoute){
            foreach($this->filterFields as $filterField){
                session()->forget("filter_" . $filterField . "_id");
            }
            session()->forget(['filter_search', 'filter_sort']);
        }

        $request->session()->put('previous_route', $currentRoute);

        $approvedIndicators =  [ ];
        foreach( $user->indicatorsGroups as $indicatorsGroup){
            if($indicatorsGroup->indicator_status_id == 5 && !$indicatorsGroup->indicators()->first()->is_primary){
                array_push($approvedIndicators, $indicatorsGroup);
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

        if(empty($approvedIndicators)){
            return view('secondary-indicators-approved-empty');
        }

        return view('secondary-indicators-approved', [ 'approvedIndicators' => $approvedIndicators,
                                                        'currentYear' => date('Y'),
                                                        'years' => $years,
                                            ]);
    }

    public function pendingAdmin()
    {
        $user = Auth::user();
        $indicatorsGroups = IndicatorsGroup::all();

        $pendingIndicators = [ ];
        foreach($indicatorsGroups as $indicatorsGroup){
            if($indicatorsGroup->indicator_status_id == $this->forApproval[$user->access_level_id]){
                array_push($pendingIndicators, $indicatorsGroup);
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

        return view('pending-admin', ['pendingIndicators' => $pendingIndicators,
                                            'currentYear' => date('Y'),
                                            'years' => $years,
                                        ]);
    }

    public function approvedAdmin(Request $request)
    {
        $user = Auth::user();

        $currentRoute = Route::currentRouteName() ?? $request->path();
        if($request->session()->has("previous_route") && $request->session()->get("previous_route") != $currentRoute){
            foreach($this->filterFields as $filterField){
                session()->forget("filter_" . $filterField . "_id");
            }
            session()->forget(['filter_search', 'filter_sort']);
        }

        $request->session()->put('previous_route', $currentRoute);

        $approvedIndicators =  [ ];
        $indicatorsGroups = IndicatorsGroup::all();
        foreach($indicatorsGroups as $indicatorsGroup){
            if($indicatorsGroup->indicator_status_id == 5 && in_array($user->id, [ $indicatorsGroup->agency_head_approver_id,
                                                                                    $indicatorsGroup->planning_director_approver_id,
                                                                                    $indicatorsGroup->executive_approver_id ]))
            {
                array_push($approvedIndicators, $indicatorsGroup);
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

        if(empty($approvedIndicators)){
            return view('secondary-indicators-approved-empty');
        }

        return view('secondary-indicators-approved', [ 'approvedIndicators' => $approvedIndicators,
                                                        'currentYear' => date('Y'),
                                                        'years' => $years,
                                            ]);
    }
}
