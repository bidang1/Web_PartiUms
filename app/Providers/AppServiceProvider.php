<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\SubEvent;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
     */
    public function boot(): void
    {
        // Force HTTPS in production to prevent mixed content issues (CSS/JS blocking)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Dynamically override parti.active_year config from global Setting in database
        $globalActiveYear = Setting::get('active_year', config('parti.active_year', 2026));
        config(['parti.active_year' => (int) $globalActiveYear]);

        // Share sub-events for the active year dynamically to the public layout footer
        View::composer('layouts.public', function ($view) {
            try {
                $year = config('parti.active_year', 2026);
                $subEvents = SubEvent::forYear($year)->published()->notDeleted()->orderBy('order')->take(4)->get();
                $view->with('footerSubEvents', $subEvents);
            } catch (\Throwable $e) {
                $view->with('footerSubEvents', collect());
            }
        });

        // Audit Logging for Authentication Events
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function (\Illuminate\Auth\Events\Login $event) {
            if ($event->user) {
                \App\Models\AuditLog::create([
                    'user_id' => $event->user->id,
                    'action' => 'Berhasil masuk ke panel admin (Login)',
                    'entity_type' => 'User',
                    'entity_id' => $event->user->id,
                ]);
            }
        });

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Logout::class, function (\Illuminate\Auth\Events\Logout $event) {
            if ($event->user) {
                \App\Models\AuditLog::create([
                    'user_id' => $event->user->id,
                    'action' => 'Keluar dari sistem (Logout)',
                    'entity_type' => 'User',
                    'entity_id' => $event->user->id,
                ]);
            }
        });
    }
}

