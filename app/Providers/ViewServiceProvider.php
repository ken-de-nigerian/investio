<?php

namespace App\Providers;

use App\Services\MarketPricesService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share with all views
        View::composer('*', function ($view) {
            $this->shareUserData($view);
        });
    }

    /**
     * Share user data with the view.
     *
     * @param \Illuminate\View\View $view
     * @return void
     */
    protected function shareUserData(\Illuminate\View\View $view): void
    {
        $user = auth()->user();
        $avatar = $user && $user->avatar
            ? $user->avatar
            : $this->generateInitialsAvatar(
                $user ? $user->first_name : 'Guest',
                $user ? $user->last_name : ''
            );

        $view->with([
            'auth' => [
                'user' => $user?->load('profile'),
            ],
            'avatar' => $avatar,
            'gateways' => (new MarketPricesService())->getGateways(),
        ]);
    }

    /**
     * Generate an avatar URL with initials.
     *
     * @param string|null $firstname
     * @param string|null $lastname
     * @return string
     */
    protected function generateInitialsAvatar(?string $firstname, ?string $lastname): string
    {
        $placeholder = 'https://placehold.co/124x124/222934/ffffff?text=';
        $initials = substr(ucfirst($firstname ?? 'G'), 0, 1) .
            substr(ucfirst($lastname ?? ''), 0, 1);
        return $placeholder . ($initials ?: 'G');
    }
}
