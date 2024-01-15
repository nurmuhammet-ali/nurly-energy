<?php

namespace App\Providers;

use App\Nova\Dashboards\Main;
use App\Repos\System\Nova\NovaRepo;
use Eolica\NovaLocaleSwitcher\LocaleSwitcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Menu\Menu;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Nova::initialPath(NovaRepo::initialPath());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        Nova::withBreadcrumbs();
        Nova::footer(NovaRepo::footer());

        $this->setupNavigation();
        $this->setupUserNavigation();
        $this->setupUserSettings();
        $this->setupAssets();
        $this->setupFieldMacros();
    }

    /**
     * Register the Nova routes.
     */
    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewNova', NovaRepo::viewNova());
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     */
    protected function dashboards(): array
    {
        return [
            new Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     */
    public function tools(): array
    {
        return [
            LocaleSwitcher::make()
                ->setLocales(config('app.locales'))
                ->onSwitchLocale(NovaRepo::localeSwitcherSave()),
        ];
    }

    /**
     * Setup navigation
     */
    public function setupNavigation(): void
    {

    }

    /**
     * Setup user navigation (dropdown).
     */
    public function setupUserNavigation(): void
    {
        Nova::userMenu(function (Request $request, Menu $menu) {
            $menu->prepend(MenuItem::make(__('My Profile'), $request->user()->profilePage()));

            return $menu;
        });
    }

    /**
     * Setup user settings
     */
    public function setupUserSettings(): void
    {
        Nova::serving(fn (ServingNova $event) => NovaRepo::serving($event));
    }

    /**
     * Setup Assets
     */
    public function setupAssets(): void
    {
        // Nova::style('additional', resource_path('css/vendor/nova/css/additional.css'));
    }

    /**
     * Setup macros of fields
     */
    public function setupFieldMacros(): void
    {
        Date::macro('toTurkmenFormat', fn () => $this->displayUsing(fn ($value) => $value?->format('d.m.Y')));
    }
}
