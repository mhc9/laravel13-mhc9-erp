<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\ExternalUser;

class ExternalUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        $externalUser = Session::get('external_user');

        // Fetch your user from an external API or hardcoded config using the ID
        if (!$externalUser || $externalUser->id != $identifier) {
            return null;
        }

        return new ExternalUser($externalUser->id, $externalUser->email, $externalUser->name, $externalUser->role);
    }

    public function retrieveByToken($identifier, $token) {}
    public function updateRememberToken(Authenticatable $user, $token) {}
    public function retrieveByCredentials(array $credentials) {}
    public function validateCredentials(Authenticatable $user, array $credentials) {}
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false) {}
}