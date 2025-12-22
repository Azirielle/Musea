<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LegacyEloquentUserProvider extends EloquentUserProvider
{
    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];
        $hash = $user->getAuthPassword();

        // Check for legacy Bcrypt hash ($2a$)
        if (Str::startsWith($hash, '$2a$')) {
            // Convert to $2y$ for compatibility with modern PHP Bcrypt
            $compatibleHash = str_replace('$2a$', '$2y$', $hash);

            if (password_verify($plain, $compatibleHash)) {
                // Determine if the password needs re-hashing to default Modern algorithm
                // Even though we just verified it, it's good practice to upgrade 
                // to the system's current default (which is likely $2y$ or Argon2)
                // and store it cleanly.

                $user->password = Hash::make($plain);

                if ($user instanceof \Illuminate\Database\Eloquent\Model) {
                    $user->saveQuietly();
                } else {
                    $user->save();
                }


                return true;
            }

            // If it looked like a legacy hash but failed verification, 
            // we return false immediately to avoid the standard Hasher 
            // trying to process the incompatible hash and throwing an exception.
            return false;
        }

        return parent::validateCredentials($user, $credentials);
    }
}
