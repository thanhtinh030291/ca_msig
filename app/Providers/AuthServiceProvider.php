<?php

namespace App\Providers;
use App\Claim;
use App\Policies\ClaimPolicy;
use App\Product;
use App\Policies\ProductPolicy;
use App\Term;
use App\Policies\TermPolicy;
use App\ReasonReject;
use App\Policies\ReasonRejectPolicy;
use App\LetterTemplate;
use App\Policies\LetterTemplatePolicy;
use App\Provider;
use App\Policies\ProviderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\JWTGuard;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Claim::class => ClaimPolicy::class,
        Product::class => ProductPolicy::class,
        Term::class => TermPolicy::class,
        ReasonReject::class => ReasonRejectPolicy::class,
        LetterTemplate::class => LetterTemplatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        
        $this->registerPolicies();
        Gate::define('viewLarecipe', function($user, $documentation) {
            return true;
        });
    }
}
