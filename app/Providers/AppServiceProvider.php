<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Passport\Passport;
// use Laravel\Passport\Contracts\AuthorizationViewResponse as AuthorizationViewResponseContract;
// use App\Http\Responses\PassportCustomResponse;
use App\Models\Passport\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->bind(
        //     AuthorizationViewResponseContract::class,
        //     PassportCustomResponse::class
        // );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /** Enable password grant */
        Passport::enablePasswordGrant();

        /**
         * Set authorization view to the user allowing to approve or deny
         * 1. By providing a view name... 
         */
        Passport::authorizationView('auth.oauth.authorize');

        /** 2. By providing a closure... */
        // Passport::authorizationView(
        //     fn ($parameters) => Inertia::render('Auth/OAuth/Authorize', [
        //         'request'   => $parameters['request'],
        //         'authToken' => $parameters['authToken'],
        //         'client'    => $parameters['client'],
        //         'user'      => $parameters['user'],
        //         'scopes'    => $parameters['scopes'],
        //     ])
        // );

        /** To use extended Passport Client model */
        Passport::useClientModel(Client::class);

        /** Token Lifetimes  */
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}
