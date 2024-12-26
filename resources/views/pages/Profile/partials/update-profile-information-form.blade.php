<section class="py-4">
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <x-Fragments.Form.FormGroup :message="$errors->get('name')" :label="__('Name')" for="name">
            <x-Elements.Form.Input name="name" id="name" :value="old('name', $user->name)" required autofocus autocomplete="name"/>
        </x-Fragments.Form.FormGroup>

        <x-Fragments.Form.FormGroup :message="$errors->get('email')" :label="__('Email')" for="email">
            <x-Elements.Form.Input id="email" type="email" name="email" :value="old('email', $user->email)" required autofocus autocomplete="username" />
            
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </x-Fragments.Form.FormGroup>

        <div class="flex items-center gap-4">
            <x-Elements.Button variant="primary" type="submit">
                {{ __('Simpan') }}
            </x-Elements.Button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>