<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Handle basic profile fields
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle additional profile fields
        if ($request->has('phone_number')) {
            $user->phone_number = $request->phone_number;
        }
        if ($request->has('national_id')) {
            $user->national_id = $request->national_id;
        }
        if ($request->has('age')) {
            $user->age = $request->age;
        }
        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }
        if ($request->has('date_of_birth')) {
            $user->date_of_birth = $request->date_of_birth;
        }
        if ($request->has('county_of_birth')) {
            $user->county_of_birth = $request->county_of_birth;
        }
        if ($request->has('county_of_residence')) {
            $user->county_of_residence = $request->county_of_residence;
        }
        if ($request->has('physical_address')) {
            $user->physical_address = $request->physical_address;
        }
        if ($request->has('emergency_contact')) {
            $user->emergency_contact = $request->emergency_contact;
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        // Handle role-specific fields
        if ($user->role === 'attachee') {
            if ($request->has('institution')) {
                $user->institution = $request->institution;
            }
            if ($request->has('course')) {
                $user->course = $request->course;
            }
            if ($request->has('attachment_start_date')) {
                $user->attachment_start_date = $request->attachment_start_date;
            }
            if ($request->has('attachment_end_date')) {
                $user->attachment_end_date = $request->attachment_end_date;
            }
        } elseif ($user->role === 'staff') {
            if ($request->has('department')) {
                $user->department = $request->department;
            }
            if ($request->has('position')) {
                $user->position = $request->position;
            }
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
