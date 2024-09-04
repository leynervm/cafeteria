<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Acceso;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::authenticateUsing(function (Request $request) {
            $access = Acceso::orderBy('id', 'asc')->first();

            if ($access && $access->access == 0) {

                $user = User::where('email', $request->email)->first();

                if ($user && $user->status == 0) {
                    if (Hash::check($request->password, $user->password)) {
                        return $user;
                    }
                }

                if ($user && $user->status == 1) {
                    throw ValidationException::withMessages([
                        'email' => __('Tu cuenta está inactiva.'),
                    ]);
                }

                throw ValidationException::withMessages([
                    'email' => __('Estas credenciales no coinciden con nuestros registros.'),
                ]);
            }

            if ($access && $access->access == 1) {
                throw ValidationException::withMessages([
                    'email' => __('El acceso al sistema está suspendido.'),
                ]);
            }

            throw ValidationException::withMessages([
                'email' => __('Los datos de acceso no están configurados, contáctese con su administrador.'),
            ]);
        });
    }
}
