@extends('layouts.app')

@section('content')
@php $title = 'Complete Your Profile'; @endphp

<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    .mesh-bg {
        position: fixed;
        inset: 0;
        background:
            radial-gradient(ellipse 80% 60% at 20% 10%, rgba(88,56,220,.28) 0%, transparent 60%),
            radial-gradient(ellipse 60% 50% at 80% 80%, rgba(14,165,233,.18) 0%, transparent 55%),
            radial-gradient(ellipse 50% 40% at 60% 30%, rgba(139,92,246,.14) 0%, transparent 50%),
            #06061a;
        z-index: 0;
        pointer-events: none;
    }

    .grid-overlay {
        position: fixed;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
        background-size: 48px 48px;
        z-index: 0;
        pointer-events: none;
    }

    .profile-setup-wrapper {
        position: relative;
        z-index: 1;
        max-width: 900px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .welcome-section {
        text-align: center;
        margin-bottom: 40px;
    }

    .welcome-section h1 {
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 12px;
        letter-spacing: -0.5px;
    }

    .welcome-section p {
        font-size: 16px;
        color: rgba(255,255,255,.5);
        margin-bottom: 24px;
    }

    .progress-container {
        max-width: 400px;
        margin: 0 auto;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 10px;
        height: 8px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .progress-text {
        text-align: center;
        margin-top: 8px;
        font-size: 14px;
        color: rgba(255,255,255,.5);
        font-weight: 600;
    }

    .setup-form {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 24px;
        padding: 40px;
    }

    .form-section {
        margin-bottom: 32px;
    }

    .form-section h3 {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(255,255,255,.1);
        letter-spacing: -0.5px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,.6);
        margin-bottom: 8px;
        letter-spacing: 0.3px;
    }

    .form-group label span.required {
        color: #f87171;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 10px;
        font-size: 14px;
        color: #fff;
        transition: all 0.2s;
        outline: none;
    }

    .form-group input::placeholder,
    .form-group select::placeholder,
    .form-group textarea::placeholder {
        color: rgba(255,255,255,.25);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: rgba(124,58,237,.6);
        background: rgba(124,58,237,.07);
        box-shadow: 0 0 0 3px rgba(124,58,237,.15);
    }

    .form-group select option {
        background: #1a1a2e;
        color: #fff;
        padding: 10px;
    }

    .form-group input:disabled {
        background: rgba(255,255,255,.03);
        border-color: rgba(255,255,255,.08);
        color: rgba(255,255,255,.4);
        cursor: not-allowed;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 80px;
    }

    .form-group .error {
        color: #f87171;
        font-size: 11px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .photo-upload {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .photo-upload input[type="file"] {
        display: none;
    }

    .photo-upload-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: rgba(255,255,255,.06);
        border: 2px dashed rgba(255,255,255,.2);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        color: rgba(255,255,255,.7);
        font-size: 14px;
        font-weight: 600;
    }

    .photo-upload-label:hover {
        background: rgba(124,58,237,.1);
        border-color: rgba(124,58,237,.4);
        color: #fff;
    }

    .photo-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,.1);
    }

    .submit-section {
        text-align: center;
        margin-top: 32px;
        padding-top: 32px;
        border-top: 1px solid rgba(255,255,255,.1);
    }

    .btn-submit {
        padding: 14px 40px;
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 0.2px;
    }

    .btn-submit:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
    }

    .role-badge {
        display: inline-block;
        padding: 6px 14px;
        background: rgba(124,58,237,.2);
        border: 1px solid rgba(124,58,237,.3);
        color: #a78bfa;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    @media (max-width: 768px) {
        .profile-setup-wrapper {
            padding: 20px 16px;
        }

        .welcome-section h1 {
            font-size: 24px;
        }

        .setup-form {
            padding: 24px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .photo-upload {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">

<div class="mesh-bg"></div>
<div class="grid-overlay"></div>

<div class="profile-setup-wrapper">
    <div class="welcome-section">
        <h1>Welcome to Lish AI Check-In System</h1>
        <p>Before accessing your dashboard, please complete your profile setup.</p>
        
        <div class="progress-container">
            <div class="progress-bar" style="width: 0%"></div>
        </div>
        <div class="progress-text">Profile Completion: 0%</div>
        
        <div style="margin-top: 16px;">
            <span class="role-badge">{{ ucfirst($user->role) }}</span>
        </div>
    </div>

    <form class="setup-form" method="POST" action="{{ route('profile.setup.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Personal Information Section -->
        <div class="form-section">
            <h3>Personal Information</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-user" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <input type="text" value="{{ old('name', $user->name) }}" disabled style="padding-left: 40px;">
                    </div>
                </div>

                <div class="form-group">
                    <label>Phone Number <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-phone" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="e.g., +254 712 345 678" required style="padding-left: 40px;">
                    </div>
                    @error('phone_number')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>National ID Number <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-id" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <input type="text" name="national_id" value="{{ old('national_id') }}" placeholder="Enter your national ID" required style="padding-left: 40px;">
                    </div>
                    @error('national_id')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Age <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-calendar-event" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <input type="number" name="age" id="age" value="{{ old('age') }}" placeholder="Auto-calculated" min="18" max="100" readonly style="padding-left: 40px; background: rgba(255,255,255,.03); cursor: not-allowed;">
                    </div>
                    @error('age')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Gender <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-gender" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <select name="gender" required style="padding-left: 40px;">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    @error('gender')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Date of Birth <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-calendar" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" required style="padding-left: 40px;" onchange="calculateAge()">
                    </div>
                    @error('date_of_birth')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>County of Birth <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-map-pin" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <input type="text" name="county_of_birth" value="{{ old('county_of_birth') }}" placeholder="e.g., Nairobi" required style="padding-left: 40px;">
                    </div>
                    @error('county_of_birth')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>County of Residence <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-home" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <input type="text" name="county_of_residence" value="{{ old('county_of_residence') }}" placeholder="e.g., Kiambu" required style="padding-left: 40px;">
                    </div>
                    @error('county_of_residence')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Physical Address <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: flex-start;">
                        <i class="ti ti-building" style="position: absolute; left: 14px; top: 12px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <textarea name="physical_address" placeholder="Enter your full physical address" required style="padding-left: 40px;">{{ old('physical_address') }}</textarea>
                    </div>
                    @error('physical_address')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Emergency Contact <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-phone-call" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="e.g., +254 712 345 678" required style="padding-left: 40px;">
                    </div>
                    @error('emergency_contact')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Profile Photo</label>
                    <div class="photo-upload">
                        <label class="photo-upload-label">
                            <input type="file" name="profile_photo" accept="image/jpeg,image/jpg,image/png">
                            <i class="ti ti-camera" style="font-size: 18px;"></i>
                            <span>Upload Photo</span>
                        </label>
                        @if($user->profile_photo_path)
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" class="photo-preview">
                        @endif
                    </div>
                    <small style="color: rgba(255,255,255,.4); font-size: 12px; margin-top: 6px; display: block;">JPG, JPEG, PNG (Max 2MB)</small>
                    @error('profile_photo')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Attachee-Specific Fields -->
        @if($user->role === 'attachee')
        <div class="form-section">
            <h3>Attachment Information</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Institution/College <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-building-arch" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <input type="text" name="institution" value="{{ old('institution') }}" placeholder="e.g., University of Nairobi" required style="padding-left: 40px;">
                    </div>
                    @error('institution')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Course <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-book" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <input type="text" name="course" value="{{ old('course') }}" placeholder="e.g., Computer Science" required style="padding-left: 40px;">
                    </div>
                    @error('course')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Attachment Start Date <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-calendar-check" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <input type="date" name="attachment_start_date" value="{{ old('attachment_start_date') }}" required style="padding-left: 40px;">
                    </div>
                    @error('attachment_start_date')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Attachment End Date <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-calendar-x" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <input type="date" name="attachment_end_date" value="{{ old('attachment_end_date') }}" required style="padding-left: 40px;">
                    </div>
                    @error('attachment_end_date')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        @endif

        <!-- Staff-Specific Fields -->
        @if($user->role === 'staff')
        <div class="form-section">
            <h3>Staff Information</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Department <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-building-skyscraper" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <select name="department" required style="padding-left: 40px;">
                            <option value="">Select Department</option>
                            <option value="ICT DEPARTMENT" {{ old('department') == 'ICT DEPARTMENT' ? 'selected' : '' }}>ICT DEPARTMENT</option>
                            <option value="RESEARCH" {{ old('department') == 'RESEARCH' ? 'selected' : '' }}>RESEARCH</option>
                            <option value="MARKETING" {{ old('department') == 'MARKETING' ? 'selected' : '' }}>MARKETING</option>
                            <option value="FINANCE" {{ old('department') == 'FINANCE' ? 'selected' : '' }}>FINANCE</option>
                            <option value="EXECUTIVE" {{ old('department') == 'EXECUTIVE' ? 'selected' : '' }}>EXECUTIVE</option>
                            <option value="SECURITY" {{ old('department') == 'SECURITY' ? 'selected' : '' }}>SECURITY</option>
                            <option value="HYGIENE" {{ old('department') == 'HYGIENE' ? 'selected' : '' }}>HYGIENE</option>
                        </select>
                    </div>
                    @error('department')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Position <span class="required">*</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ti ti-briefcase" style="position: absolute; left: 14px; color: rgba(255,255,255,.3); font-size: 16px; pointer-events: none; line-height: 1;"></i>
                        <input type="text" name="position" value="{{ old('position') }}" placeholder="e.g., Software Developer" required style="padding-left: 40px;">
                    </div>
                    @error('position')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        @endif

        <div class="submit-section">
            <button type="submit" class="btn-submit">
                <i class="ti ti-check" style="font-size: 16px;"></i>
                Complete Profile Setup
            </button>
        </div>
    </form>
</div>

<script>
    // Calculate age from date of birth
    function calculateAge() {
        const dateOfBirth = document.getElementById('date_of_birth').value;
        const ageInput = document.getElementById('age');
        
        if (dateOfBirth) {
            const birthDate = new Date(dateOfBirth);
            const today = new Date();
            
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            ageInput.value = age;
        } else {
            ageInput.value = '';
        }
    }

    // Update progress indicator based on form completion
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.setup-form');
        const progressBar = document.querySelector('.progress-bar');
        const progressText = document.querySelector('.progress-text');
        const requiredInputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        
        function updateProgress() {
            let filled = 0;
            requiredInputs.forEach(input => {
                if (input.value.trim() !== '') {
                    filled++;
                }
            });
            
            const percentage = Math.round((filled / requiredInputs.length) * 100);
            progressBar.style.width = percentage + '%';
            progressText.textContent = 'Profile Completion: ' + percentage + '%';
        }
        
        requiredInputs.forEach(input => {
            input.addEventListener('input', updateProgress);
            input.addEventListener('change', updateProgress);
        });
        
        // Initial check
        updateProgress();
    });
</script>
@endsection
