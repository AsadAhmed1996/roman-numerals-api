<?php

namespace App\Providers;

use App\Services\IntegerConverterInterface;
use App\Services\RomanNumeralConverter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IntegerConverterInterface::class, RomanNumeralConverter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
