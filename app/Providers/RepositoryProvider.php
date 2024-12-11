<?php

namespace App\Providers;

use App\Contracts\ClaimInterface;
use App\Models\Claim;
use App\Repositories\ClaimRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(ClaimInterface::class, ClaimRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
