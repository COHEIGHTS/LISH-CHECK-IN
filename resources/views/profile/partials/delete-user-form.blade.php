<section class="space-y-6">
    <div class="text-sm text-gray-400 mb-4">
        Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
    </div>

    <button onclick="document.getElementById('delete-modal').classList.remove('hidden')" class="btn-danger">
        Delete Account
    </button>

    <div id="delete-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 {{ $errors->userDeletion->isNotEmpty() ? '' : 'hidden' }}">
        <div class="bg-gray-900 border border-white/10 rounded-16 p-6 max-w-md w-full mx-4">
            <h2 class="text-lg font-medium text-white mb-2">
                Are you sure you want to delete your account?
            </h2>

            <p class="text-sm text-gray-400 mb-4">
                Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="mb-4">
                    <label for="password" class="sr-only">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-input w-3/4"
                        placeholder="Password"
                    />

                    @error('userDeletion.password')
                        <div class="text-red-400 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white/70 hover:text-white transition-all text-sm font-medium">
                        Cancel
                    </button>

                    <button type="submit" class="btn-danger">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
