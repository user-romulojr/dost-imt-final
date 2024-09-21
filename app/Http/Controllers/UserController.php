<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public $accessLevel = [
        '1' => 'Executive',
        '2' => 'Planning Director',
        '3' => 'Planning Officer',
        '4' => 'Agency Head',
        '5' => 'Agency Focal',
        '6' => 'View Only',
    ];

    public $colorCode = [
        '1' => '#b8860b',
        '2' => '#708090',
        '3' => '#dc143c',
        '4' => '#008080',
        '5' => '#4b0082',
        '6' => 'black',
    ];

    public function index()
    {
        $users = User::all();
        $agencies = Agency::all();

        return view('users', ['users' => $users, 'accessLevel' => $this->accessLevel, 'colorCode' => $this->colorCode, 'agencies' => $agencies]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'agency' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'contact' => ['nullable'],
            'role' => ['nullable'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'agency_id' => $request->agency_id,
            'email' => $request->email,
            'contact' => $request->contact,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect(route('users.index'));
    }

    public function update($userID, Request $request): RedirectResponse
    {
        $user = User::findOrFail($userID);

        $data = $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'agency_id' => ['nullable'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            'contact' => ['nullable'],
            'role' => ['nullable'],
            'password' => ['required', Rules\Password::defaults()],
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
