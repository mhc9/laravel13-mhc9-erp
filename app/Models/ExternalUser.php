<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;

class ExternalUser implements Authenticatable
{
    public function __construct(public string $id, public string $email, public string $name, public int $role) {}

    public function getAuthIdentifierName() { return 'id'; }
    public function getAuthIdentifier() { return $this->id; }
    public function getAuthPassword() { return ''; }
    public function getAuthPasswordName() { return ''; }
    public function getRememberToken() { return ''; }
    public function setRememberToken($value) {}
    public function getRememberTokenName() { return ''; }
}