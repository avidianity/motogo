<?php

namespace App\Providers;

use Illuminate\Database\Schema\Builder;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Builder::morphUsingUlids();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
