<?php

namespace App\Providers;

use DutchCodingCompany\FilamentSocialite\Models\SocialiteUser;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use DutchCodingCompany\FilamentSocialite\Facades\FilamentSocialite;
use DutchCodingCompany\FilamentSocialite\FilamentSocialite as OtherFilamentSocialiteIg;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        FilamentSocialite::setCreateUserCallback(fn (SocialiteUserContract $oauthUser, OtherFilamentSocialiteIg $socialite) => $socialite->getUserModelClass()::create([
            'name' => $oauthUser->getNickname(),
            'email' => $oauthUser->getEmail()
        ]));
    }
}
