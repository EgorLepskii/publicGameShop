<?php

namespace App\Providers;

use App\Models\FrontCategory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class LastNewsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            'layouts.lastNews', function ($view) {
            }
        );
    }
}
