<?php

namespace App\Repos\System\Nova;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Events\ServingNova;

class NovaRepo
{
    /**
     * Initial path
     */
    protected static string $initialPath = '/dashboards/main';

    /**
     * Serving nova application
     */
    public static function serving(ServingNova $event): void
    {
        static::setLocale($event);
    }

    /**
     * Initial path for nova
     */
    public static function initialPath(): string
    {
        return static::$initialPath;
    }

    /**
     * This gate determines who can access Nova in non-local environments.
     */
    public static function viewNova(): Closure
    {
        return fn ($user) => $user->isSystemUser();
    }

    /**
     * Set locales
     */
    public static function setLocale($event): void
    {
        $user = $event->request->user();

        if (array_key_exists($user?->locale, config('app.locales'))) {
            app()->setLocale($user->locale);
        }
    }

    /**
     * Locale Switcher Save
     */
    public static function localeSwitcherSave(): Closure
    {
        return function (Request $request) {
            $locale = $request->post('locale');

            if (array_key_exists($locale, config('app.locales'))) {
                $request->user()->update(['locale' => $locale]);
            }
        };
    }

    /**
     * Nova Footer
     */
    public static function footer(): Closure
    {
        return fn () => view('vendor.nova.partials.footer')->render();
    }

    /**
     * Check if user is me
     */
    public static function isMe(): Closure
    {
        return fn () => Gate::allows('isMe', auth()->user());
    }

    /**
     * Check if user is admin
     */
    public static function isSuperAdmin(): Closure
    {
        return fn () => Gate::allows('isSuperAdmin', auth()->user());
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin(): Closure
    {
        return fn () => Gate::allows('isAdmin', auth()->user());
    }

    /**
     * Readonly only on "update" pages
     */
    public static function readonlyOnUpdate(): Closure
    {
        return fn ($request) => $request->isUpdateOrUpdateAttachedRequest();
    }
}
