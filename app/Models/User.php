<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class User implements AuthenticatableContract
{
    use Authenticatable;

    public $IdUsuario;
    public $Nombre;
    public $Email;

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function getAuthIdentifierName()
    {
        return 'IdUsuario';
    }

    public function getAuthIdentifier()
    {
        return $this->IdUsuario;
    }

    public function getAuthPassword()
    {
        return ''; // No es necesario en este caso
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // No es necesario en este caso
    }

    public function getRememberTokenName()
    {
        return '';
    }
}

