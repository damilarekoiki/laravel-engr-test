<?php

namespace App\Providers;

use App\Contracts\ClaimInterface;
use App\Contracts\InsurerInterface;
use App\Models\Claim;
use App\Repositories\ClaimRepository;
use App\Repositories\InsurerRepository;
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
        $this->app->bind(InsurerInterface::class, InsurerRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
