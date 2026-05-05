<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Authorization Request') }}
        </h2>
        <p class="mt-1">
            {{ $client->name }} {{ __('is requesting permission to access your account.') }}
        </p>
    </div>

    @if (count($scopes) > 0)
        <div class="mb-4">
            <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ __('This application will be able to:') }}</h3>
            <ul class="mt-2 list-disc list-inside text-sm text-gray-600 dark:text-gray-400">
                @foreach ($scopes as $scope)
                    <li>{{ $scope->description }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex items-center justify-end mt-4">
        <!-- Deny Form -->
        <form method="POST" action="{{ route('passport.authorizations.deny') }}">
            @csrf
            @method('DELETE')

            <input type="hidden" name="state" value="{{ $request->state }}">
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">

            <x-secondary-button type="submit">
                {{ __('Deny') }}
            </x-secondary-button>
        </form>

        <!-- Approve Form -->
        <form method="POST" action="{{ route('passport.authorizations.approve') }}" class="ms-3">
            @csrf

            <input type="hidden" name="state" value="{{ $request->state }}">
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">

            <x-primary-button type="submit" name="approve" value="1">
                {{ __('Authorize') }}
            </x-primary-button>
        </form>
    </div>
</x-guest-layout>
