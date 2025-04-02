<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CustomUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {

        // Asegúrate de que el identificador esté en la sesión
        $sessionKey = 'user_' . $identifier;
        $user = session($sessionKey);



        // Retorna el usuario almacenado en la sesión (si existe)
        return $user;
    }

    public function retrieveByToken($identifier, $token)
    {
        Log::info('retrieveByToken called', ['id' => $identifier, 'token' => $token]);
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        Log::info('updateRememberToken called', ['user' => $user, 'token' => $token]);
    }

    public function retrieveByCredentials(array $credentials)
    {
        //Log::info('retrieveByCredentials called', ['credentials' => $credentials]);

        $username = $credentials['username'];
        $password = $credentials['password'];

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->get(\Constants\Constants::API_URL . '/verifica-usuario/' . $username . '/' . $password);
            $result = json_decode($response->getBody(), true);





            //Log::info('API Response', ['response' => $result]);

            if (!empty($result['result']) && is_array($result['result']) && count($result['result']) > 0) {
                $firstResult = $result['result'][0];
                $responseDatos = $client->get(\Constants\Constants::API_URL.'/usuario-datos/' . $firstResult['IdUsuario'] );

                $resultDatos = json_decode($responseDatos->getBody(), true);
                //log::info(print_r($resultDatos, true));
                $email = null;
                if (!empty($resultDatos['result']) && is_array($resultDatos['result']) && count($resultDatos['result']) > 0) {
                    $firstResultDatos = $resultDatos['result'][0];
                    $email = $firstResultDatos['EMail'];
                }
                $user = new User([
                    'IdUsuario' => $firstResult['IdUsuario'],
                    'Nombre' => $firstResult['Nombre'],
                    'Email' => $email,
                ]);
                //Log::info('User found', ['user' => $user]);
                return $user;
            }
        } catch (\Exception $e) {
            Log::error('Error retrieving user by credentials', ['error' => $e->getMessage()]);
        }

        //Log::info('No user found with given credentials');
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        //Log::info('validateCredentials called', ['user' => $user, 'credentials' => $credentials]);
        return $user !== null;
    }
}



