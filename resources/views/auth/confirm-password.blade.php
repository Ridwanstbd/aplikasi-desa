<x-Layouts.AuthLayout >
    <x-Layouts.FormAuthContainer title="Konfirmasi Password">
        <div class="mb-4 text-sm text-gray-600">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf    
            <!-- Password -->
            <x-Fragments.Form.FormGroup label="Password" for="password">
                <x-Fragments.Form.Input id="password" type="password" name="password" required autocomplete="current-password" />
            </x-Fragments.Form.FormGroup>
            <x-Elements.Form.FormSubmit>
                {{ __('Confirm') }}
            </x-Elements.Form.FormSubmit>
        </form>
    </x-Layouts.FormAuthContainer>
</x-Layouts.AuthLayout>
