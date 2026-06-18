@extends('layouts.app')

@section('content')
@php $title = 'Complete Your Profile'; @endphp

<style>
    .profile-setup-wrapper {
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
        color: #1e293b;
        margin-bottom: 12px;
    }

    .welcome-section p {
        font-size: 16px;
        color: #64748b;
        margin-bottom: 24px;
    }

    .progress-container {
        max-width: 400px;
        margin: 0 auto;
        background: #e2e8f0;
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
        color: #64748b;
        font-weight: 600;
    }

    .setup-form {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        margin-bottom: 32px;
    }

    .form-section h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
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
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-group label span.required {
        color: #ef4444;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        color: #1e293b;
        transition: all 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 80px;
    }

    .form-group .error {
        color: #ef4444;
        font-size: 12px;
        margin-top: 6px;
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
        background: #f1f5f9;
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .photo-upload-label:hover {
        background: #e2e8f0;
        border-color: #94a3b8;
    }

    .photo-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e2e8f0;
    }

    .submit-section {
        text-align: center;
        margin-top: 32px;
        padding-top: 32px;
        border-top: 2px solid #e2e8f0;
    }

    .btn-submit {
        padding: 14px 40px;
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
    }

    .role-badge {
        display: inline-block;
        padding: 4px 12px;
        background: #7c3aed;
        color: white;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    @media (max-width: 768px) {
        .profile-setup-wrapper {
            padding: 20px 16px;
        }

        .welcome-section h1 {
            font-size: 24px;
        }

        .setup-form {
            padding: 20px;
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
                    <input type="text" value="{{ old('name', $user->name) }}" disabled>
                </div>

                <div class="form-group">
                    <label>Phone Number <span class="required">*</span></label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="e.g., +254 712 345 678" required>
                    @error('phone_number')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>National ID Number <span class="required">*</span></label>
                    <input type="text" name="national_id" value="{{ old('national_id') }}" placeholder="Enter your national ID" required>
                    @error('national_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Age <span class="required">*</span></label>
                    <input type="number" name="age" value="{{ old('age') }}" placeholder="e.g., 25" min="18" max="100" required>
                    @error('age')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Gender <span class="required">*</span></label>
                    <select name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Date of Birth <span class="required">*</span></label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                    @error('date_of_birth')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>County of Birth <span class="required">*</span></label>
                    <input type="text" name="county_of_birth" value="{{ old('county_of_birth') }}" placeholder="e.g., Nairobi" required>
                    @error('county_of_birth')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>County of Residence <span class="required">*</span></label>
                    <input type="text" name="county_of_residence" value="{{ old('county_of_residence') }}" placeholder="e.g., Kiambu" required>
                    @error('county_of_residence')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Physical Address <span class="required">*</span></label>
                    <textarea name="physical_address" placeholder="Enter your full physical address" required>{{ old('physical_address') }}</textarea>
                    @error('physical_address')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Emergency Contact <span class="required">*</span></label>
                    <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="e.g., +254 712 345 678" required>
                    @error('emergency_contact')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Profile Photo</label>
                    <div class="photo-upload">
                        <label class="photo-upload-label">
                            <input type="file" name="profile_photo" accept="image/jpeg,image/jpg,image/png">
                            <span>📷 Upload Photo</span>
                        </label>
                        @if($user->profile_photo_path)
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" class="photo-preview">
                        @endif
                    </div>
                    <small style="color: #64748b; font-size: 12px; margin-top: 6px; display: block;">JPG, JPEG, PNG (Max 2MB)</small>
                    @error('profile_photo')
                        <div class="error">{{ $message }}</div>
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
                    <input type="text" name="institution" value="{{ old('institution') }}" placeholder="e.g., University of Nairobi" required>
                    @error('institution')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Course <span class="required">*</span></label>
                    <input type="text" name="course" value="{{ old('course') }}" placeholder="e.g., Computer Science" required>
                    @error('course')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Attachment Start Date <span class="required">*</span></label>
                    <input type="date" name="attachment_start_date" value="{{ old('attachment_start_date') }}" required>
                    @error('attachment_start_date')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Attachment End Date <span class="required">*</span></label>
                    <input type="date" name="attachment_end_date" value="{{ old('attachment_end_date') }}" required>
                    @error('attachment_end_date')
                        <div class="error">{{ $message }}</div>
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
                    <select name="department" required>
                        <option value="">Select Department</option>
                        <option value="ICT DEPARTMENT" {{ old('department') == 'ICT DEPARTMENT' ? 'selected' : '' }}>ICT DEPARTMENT</option>
                        <option value="RESEARCH" {{ old('department') == 'RESEARCH' ? 'selected' : '' }}>RESEARCH</option>
                        <option value="MARKETING" {{ old('department') == 'MARKETING' ? 'selected' : '' }}>MARKETING</option>
                        <option value="FINANCE" {{ old('department') == 'FINANCE' ? 'selected' : '' }}>FINANCE</option>
                        <option value="EXECUTIVE" {{ old('department') == 'EXECUTIVE' ? 'selected' : '' }}>EXECUTIVE</option>
                        <option value="SECURITY" {{ old('department') == 'SECURITY' ? 'selected' : '' }}>SECURITY</option>
                        <option value="HYGIENE" {{ old('department') == 'HYGIENE' ? 'selected' : '' }}>HYGIENE</option>
                    </select>
                    @error('department')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Position <span class="required">*</span></label>
                    <input type="text" name="position" value="{{ old('position') }}" placeholder="e.g., Software Developer" required>
                    @error('position')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        @endif

        <div class="submit-section">
            <button type="submit" class="btn-submit">Complete Profile Setup</button>
        </div>
    </form>
</div>

<script>
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
