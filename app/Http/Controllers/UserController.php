<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Http\Controllers\Controller;
use App\Models\AccessLevel;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    public $formFields = [
        'firstName' => ['id' => 'firstName', 'type' => 'text', 'label' => 'First Name'],
        'lastName' => ['id' => 'lastName', 'type' => 'text', 'label' => 'Last Name'],
        'email' => ['id' => 'email', 'type' => 'email', 'label' => 'Email'],
        'contact' => ['id' => 'contact', 'type' => 'text', 'label' => 'Contact'],
        'role' => ['id' => 'role', 'type' => 'text', 'label' => 'Role'],
        'passowrd' => ['id' => 'password', 'type' => 'text', 'label' => 'Password'],
    ];

    public $filterFields = [
        'agency',
        'access_level'
    ];


    public function index(Request $request)
    {
        $agencies = Agency::all();
        $accessLevels = AccessLevel::all();

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

        $query = User::query();
        foreach($this->filterFields as $filterField){
            if($request->session()->has("filter_" . $filterField . "_id")){
                $query->where($filterField . "_id", $request->session()->get("filter_" . $filterField . "_id"));
            }
        }

        $search = $request->session()->get('filter_search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        }

        if($request->session()->has("filter_sort")){
            $query->orderBy('last_name', $request->session()->get('filter_sort'));
        }

        $users = $query->get();

        return view('users', ['users' => $users,
                                'agencies' => $agencies,
                                'accessLevels' => $accessLevels,
                    ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'agency_id' => ['nullable'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'contact' => ['nullable'],
            'access_level_id' => ['required'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'agency_id' => $request->agency_id,
            'email' => $request->email,
            'contact' => $request->contact,
            'access_level_id' => $request->access_level_id,
            'password' => Hash::make('Pr0jectl0di'),
        ]);

        return redirect(route('users.index'));
    }

    public function update($userID, Request $request): RedirectResponse
    {
        $user = User::findOrFail($userID);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'agency_id' => ['nullable'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            'contact' => ['nullable'],
            'access_level_id' => ['required'],
        ]);

        $user->update($data);

        return redirect(route('users.index'));
    }

    public function destroy($userID)
    {
        $user = User::findOrFail($userID);

        $user->delete();

        return redirect(route('users.index'));
    }
}
