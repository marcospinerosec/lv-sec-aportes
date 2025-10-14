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
    public $EsAdministrador = false;
    public $ImprimeSoloBoleta = false;
    public $ImprimeSoloActa = false;
    public $Empresa = 0;

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
    }

    // Métodos del contrato de autenticación
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
        return ''; // No es necesario
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // No es necesario
    }

    public function getRememberTokenName()
    {
        return '';
    }

    // ✅ Métodos de conveniencia para tu lógica
    public function isAdmin()
    {
        return (bool) $this->EsAdministrador;
    }

    public function soloImprimeBoleta()
    {
        return (bool) $this->ImprimeSoloBoleta;
    }

    public function soloImprimeActa()
    {
        return (bool) $this->ImprimeSoloActa;
    }

    public function tieneEmpresa()
    {
        return (int) $this->Empresa > 0;
    }
}
