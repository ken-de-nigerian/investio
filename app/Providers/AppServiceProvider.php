<?php

namespace App\Providers;

use App\Mail\Transports\PhpMailerTransport;
use Illuminate\Mail\MailManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->resolving(MailManager::class, function (MailManager $mailManager) {
            $mailManager->extend('phpmailer', function () {
                return new PhpMailerTransport();
            });
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('truncate', function ($expression) {
            return "<?php echo \Illuminate\Support\Str::limit($expression); ?>";
        });
    }
}
