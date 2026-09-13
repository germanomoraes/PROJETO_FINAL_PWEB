<?php

namespace App\Providers;

use App\Models\ConfiguracaoTaxa;
use App\Models\Fatura;
use App\Policies\ConfiguracaoTaxaPolicy;
use App\Policies\FaturaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Laravel 11+ não usa mais app/Providers/AuthServiceProvider.php por
     * padrão; as Policies são registradas aqui mesmo, via Gate::policy().
     */
    public function boot(): void
    {
        Gate::policy(ConfiguracaoTaxa::class, ConfiguracaoTaxaPolicy::class);
        Gate::policy(Fatura::class, FaturaPolicy::class);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}