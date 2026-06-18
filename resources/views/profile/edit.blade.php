@extends('layouts.app-dashboard')

@section('content')
@php $title = 'Profile'; @endphp

<style>
    .profile-container {
        max-width: 7xl;
        margin: 0 auto;
    }

    .profile-section {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.5px;
        margin-bottom: 24px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: rgba(255,255,255,.7);
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 10px;
        color: #fff;
        font-size: 14px;
        font-family: inherit;
        padding: 12px 16px;
        outline: none;
        transition: border-color .2s, background .2s;
    }

    .form-input:focus {
        border-color: rgba(124,58,237,.6);
        background: rgba(124,58,237,.07);
    }

    .btn-primary {
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: opacity .2s;
    }

    .btn-primary:hover { opacity: .9; }

    .btn-danger {
        background: rgba(248,113,113,.15);
        color: #f87171;
        border: 1px solid rgba(248,113,113,.3);
        border-radius: 8px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-danger:hover {
        background: rgba(248,113,113,.25);
        border-color: rgba(248,113,113,.4);
    }

    .alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 24px;
        font-size: 14px;
        font-weight: 600;
    }

    .alert-success {
        background: rgba(52,211,153,.15);
        border: 1px solid rgba(52,211,153,.3);
        color: #34d399;
    }

    .alert-error {
        background: rgba(248,113,113,.15);
        border: 1px solid rgba(248,113,113,.3);
        color: #f87171;
    }
</style>

<div class="profile-container">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="profile-section">
        <div class="section-title">Edit Profile Details</div>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <div class="form-label">Full Name</div>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                    </div>
                    <div>
                        <div class="form-label">Email</div>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                    </div>
                    <div>
                        <div class="form-label">Phone Number</div>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="form-input" placeholder="e.g., +254 712 345 678">
                    </div>
                    <div>
                        <div class="form-label">National ID</div>
                        <input type="text" name="national_id" value="{{ old('national_id', $user->national_id) }}" class="form-input">
                    </div>
                    <div>
                        <div class="form-label">Age</div>
                        <input type="number" name="age" value="{{ old('age', $user->age) }}" class="form-input" min="18" max="100">
                    </div>
                    <div>
                        <div class="form-label">Gender</div>
                        <select name="gender" class="form-input">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <div class="form-label">Date of Birth</div>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}" class="form-input">
                    </div>
                    <div>
                        <div class="form-label">County of Birth</div>
                        <input type="text" name="county_of_birth" value="{{ old('county_of_birth', $user->county_of_birth) }}" class="form-input">
                    </div>
                    <div>
                        <div class="form-label">County of Residence</div>
                        <input type="text" name="county_of_residence" value="{{ old('county_of_residence', $user->county_of_residence) }}" class="form-input">
                    </div>
                    <div>
                        <div class="form-label">Physical Address</div>
                        <textarea name="physical_address" class="form-input" rows="2">{{ old('physical_address', $user->physical_address) }}</textarea>
                    </div>
                    <div>
                        <div class="form-label">Emergency Contact</div>
                        <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $user->emergency_contact) }}" class="form-input" placeholder="e.g., +254 712 345 678">
                    </div>
                    <div>
                        <div class="form-label">Profile Photo</div>
                        <input type="file" name="profile_photo" class="form-input" accept="image/jpeg,image/jpg,image/png">
                        @if($user->profile_photo_path)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Current Profile Photo" class="w-20 h-20 rounded-full object-cover border-2 border-purple-500">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($user->role === 'attachee')
                <div class="mt-6 pt-6 border-t border-gray-700">
                    <div class="section-title mb-4">Attachment Information</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="form-label">Institution</div>
                            <input type="text" name="institution" value="{{ old('institution', $user->institution) }}" class="form-input">
                        </div>
                        <div>
                            <div class="form-label">Course</div>
                            <input type="text" name="course" value="{{ old('course', $user->course) }}" class="form-input">
                        </div>
                        <div>
                            <div class="form-label">Attachment Start Date</div>
                            <input type="date" name="attachment_start_date" value="{{ old('attachment_start_date', $user->attachment_start_date ? \Carbon\Carbon::parse($user->attachment_start_date)->format('Y-m-d') : '') }}" class="form-input">
                        </div>
                        <div>
                            <div class="form-label">Attachment End Date</div>
                            <input type="date" name="attachment_end_date" value="{{ old('attachment_end_date', $user->attachment_end_date ? \Carbon\Carbon::parse($user->attachment_end_date)->format('Y-m-d') : '') }}" class="form-input">
                        </div>
                    </div>
                </div>
            @endif

            @if($user->role === 'staff')
                <div class="mt-6 pt-6 border-t border-gray-700">
                    <div class="section-title mb-4">Staff Information</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="form-label">Department</div>
                            <input type="text" name="department" value="{{ old('department', $user->department) }}" class="form-input">
                        </div>
                        <div>
                            <div class="form-label">Position</div>
                            <input type="text" name="position" value="{{ old('position', $user->position) }}" class="form-input">
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-6">
                <button type="submit" class="btn-primary">Update Profile</button>
            </div>
        </form>
    </div>

    <div class="profile-section">
        <div class="section-title">Profile Information</div>
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="profile-section">
        <div class="section-title">Update Password</div>
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="profile-section">
        <div class="section-title">Delete Account</div>
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
