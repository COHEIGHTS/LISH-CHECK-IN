<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileSetupController extends Controller
{
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if ($user->profile_completed) {
            return $this->redirectToDashboard();
        }

        return view('profile.setup', compact('user'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if ($user->profile_completed) {
            return $this->redirectToDashboard();
        }

        $rules = $this->getValidationRules($user->role);
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        // Update user profile
        $user->update([
            'phone_number' => $request->phone_number,
            'national_id' => $request->national_id,
            'age' => $request->age,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'county_of_birth' => $request->county_of_birth,
            'county_of_residence' => $request->county_of_residence,
            'physical_address' => $request->physical_address,
            'emergency_contact' => $request->emergency_contact,
            'profile_completed' => true,
        ]);

        // Add role-specific fields
        if ($user->role === 'attachee') {
            $user->update([
                'institution' => $request->institution,
                'course' => $request->course,
                'attachment_start_date' => $request->attachment_start_date,
                'attachment_end_date' => $request->attachment_end_date,
            ]);
        } elseif ($user->role === 'staff') {
            $user->update([
                'department' => $request->department,
                'position' => $request->position,
            ]);
        }

        return $this->redirectToDashboard()->with('success', 'Profile completed successfully!');
    }

    private function getValidationRules($role)
    {
        $rules = [
            'phone_number' => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'national_id' => 'required|string|unique:users,national_id,' . Auth::id(),
            'age' => 'required|integer|min:18|max:100',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',
            'county_of_birth' => 'required|string|max:100',
            'county_of_residence' => 'required|string|max:100',
            'physical_address' => 'required|string|max:255',
            'emergency_contact' => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        if ($role === 'attachee') {
            $rules['institution'] = 'required|string|max:255';
            $rules['course'] = 'required|string|max:255';
            $rules['attachment_start_date'] = 'required|date|after_or_equal:today';
            $rules['attachment_end_date'] = 'required|date|after:attachment_start_date';
        } elseif ($role === 'staff') {
            $rules['department'] = 'required|string|max:255';
            $rules['position'] = 'required|string|max:255';
        }

        return $rules;
    }

    private function redirectToDashboard()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'admin':
                return redirect()->route('dashboard.admin');
            case 'staff':
                return redirect()->route('dashboard.staff');
            case 'attachee':
                return redirect()->route('dashboard.attachee');
            default:
                return redirect()->route('dashboard');
        }
    }
}
