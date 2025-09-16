<?php

namespace App\Providers;

use App\Models\Donation;
use App\Policies\DonationPolicy;
use App\Models\AidRequest;
use App\Policies\AidRequestPolicy;
use App\Models\Distribution;
use App\Policies\DistributionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Donation::class => DonationPolicy::class,
        AidRequest::class => AidRequestPolicy::class,
        Distribution::class => DistributionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
