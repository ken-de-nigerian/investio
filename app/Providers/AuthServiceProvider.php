<?php

namespace App\Providers;

use App\Models\DomesticTransfer;
use App\Models\Goal;
use App\Models\Loan;
use App\Models\User;
use App\Models\UserInvestment;
use App\Models\WireTransfer;
use App\Policies\DomesticTransferPolicy;
use App\Policies\GoalPolicy;
use App\Policies\LoanPolicy;
use App\Policies\UserInvestmentPolicy;
use App\Policies\UserPolicy;
use App\Policies\WireTransferPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected array $policies = [
        User::class => UserPolicy::class,
        DomesticTransfer::class => DomesticTransferPolicy::class,
        WireTransfer::class => WireTransferPolicy::class,
        Loan::class => LoanPolicy::class,
        Goal::class => GoalPolicy::class,
        UserInvestment::class => UserInvestmentPolicy::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('access-admin-dashboard', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('access-user-dashboard', function (User $user) {
            return $user->role === 'user';
        });
    }
}
