<section>
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="name" class="form-label">Name</label>
            <input id="name" name="name" type="text" class="form-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="text-red-400 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-input" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="text-red-400 text-sm mt-1">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-gray-400">
                    Your email address is unverified.
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-cyan-400 hover:text-cyan-300 underline">
                            Click here to re-send the verification email.
                        </button>
                    </form>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-green-400 text-sm">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary">Save</button>

            @if (session('status') === 'profile-updated')
                <div class="text-green-400 text-sm">Saved.</div>
            @endif
        </div>
    </form>
</section>
