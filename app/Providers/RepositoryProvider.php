<?php

namespace App\Providers;

use App\Contracts\ClaimInterface;
use App\Contracts\BatchedClaimInterface;
use App\Repositories\ClaimRepository;
use App\Repositories\BatchedClaimRepository;
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
        $this->app->bind(BatchedClaimInterface::class, BatchedClaimRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
