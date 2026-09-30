<?php

namespace App\Providers;

use App\Models\ContactEnquiry;
use App\Services\SiteData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SiteData::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Surface N+1 queries during development instead of silently lazy-loading.
        Model::preventLazyLoading(! $this->app->isProduction());

        // Settings, contact details and social links are needed by the layouts,
        // header, footer and error pages; SiteData serves them from cache.
        View::composer(['layouts.*', 'components.*', 'frontend.*', 'errors.*', 'auth.*', 'admin.*'], function ($view) {
            $view->with('site', app(SiteData::class));
        });

        // Unread badge for the admin sidebar and top bar (queried once per request).
        View::composer(['components.admin.sidebar', 'components.admin.navbar'], function ($view) {
            $view->with('unreadEnquiries', once(fn () => ContactEnquiry::where('status', 'unread')->count()));
        });
    }
}
