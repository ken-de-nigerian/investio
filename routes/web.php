<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDepositsController;
use App\Http\Controllers\Admin\AdminDomesticTransferController;
use App\Http\Controllers\Admin\AdminEmailNotificationsController;
use App\Http\Controllers\Admin\AdminGoalController;
use App\Http\Controllers\Admin\AdminInterBankTransferController;
use App\Http\Controllers\Admin\AdminInvestmentController;
use App\Http\Controllers\Admin\AdminKycController;
use App\Http\Controllers\Admin\AdminLoanController;
use App\Http\Controllers\Admin\AdminReferralsController;
use App\Http\Controllers\Admin\AdminTransactionsController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminWireTransferController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OnboardingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\User\CalculatorController;
use App\Http\Controllers\User\CardController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\DomesticTransferController;
use App\Http\Controllers\User\GoalController;
use App\Http\Controllers\User\HelpCenterController;
use App\Http\Controllers\User\InterBankTransferController;
use App\Http\Controllers\User\InvestmentController;
use App\Http\Controllers\User\KycController;
use App\Http\Controllers\User\LoanController;
use App\Http\Controllers\User\ReferralsController;
use App\Http\Controllers\User\StatisticsController;
use App\Http\Controllers\User\TransactionsController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\WireTransferController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Homepage Routes
|--------------------------------------------------------------------------
*/
Route::controller(HomeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
    });

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['redirect.authenticated'])->group(function () {
    // Authentication Routes
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'index')->name('login');
        Route::post('/login', 'login')->name('login.store');
    });

    // Registration Routes
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'index')->name('register');
        Route::post('/register', 'register')->name('register.store');
    });

    // Password Reset Routes
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('/password/reset', 'create')->name('password.request');
        Route::post('/password/email', 'store')->name('password.email');
        Route::get('/password/reset/{token}', 'edit')->name('password.reset');
        Route::post('/password/update', 'update')->name('password.update');
    });

    // Social Login
    Route::controller(SocialLoginController::class)->group(function () {
        Route::get('social/{provider}', 'redirectToProvider')->name('social.redirect');
        Route::get('social/callback/{provider}', 'handleProviderCallback')->name('social.callback');
    });
});

/*
|--------------------------------------------------------------------------
| Verify Otp Routes
|--------------------------------------------------------------------------
*/
Route::controller(VerificationController::class)
    ->middleware('auth')
    ->group(function () {
    Route::post('/verify/otp', 'index')->name('verify.otp');
    Route::get('/resend/otp', 'store')->name('resend.otp');
});

/*
|--------------------------------------------------------------------------
| Logout Routes
|--------------------------------------------------------------------------
*/
Route::prefix('logout')
    ->middleware('auth')
    ->controller(SessionController::class)
    ->group(function () {
    Route::post('/', 'destroy')->name('logout');
    Route::post('/current-session/{sessionId}', 'destroySession')->name('logout.current');
    Route::delete('/all-sessions', 'destroyAllSessions')->name('logout.all');
    Route::post('social/{provider}/disconnect', 'invokeAccount')->name('social.disconnect');
});

/*
|--------------------------------------------------------------------------
| Onboarding Routes
|--------------------------------------------------------------------------
*/
Route::controller(OnboardingController::class)
    ->middleware(['auth', 'profile.complete'])
    ->group(function () {
        Route::get('/onboarding', 'index')->name('onboarding');
        Route::post('/onboarding/store', 'store')->name('onboarding.store');
});

/*
|--------------------------------------------------------------------------
| User Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::controller(DashboardController::class)
    ->name('user.')
    ->middleware(['auth', 'onboarding.check', 'can:access-user-dashboard'])
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', 'index')->name('dashboard');

        // Account Profile
        Route::get('/profile', 'profilePage')->name('profile');
        Route::patch('/profile', 'updateProfile')->name('profile.update');
        Route::post('/profile/account-details', 'updateAccountDetails')->name('profile.account.details.update');
        Route::post('/picture/update', 'updateProfilePicture')->name('picture.update');
        Route::delete('/picture/remove', 'removeProfilePicture')->name('picture.remove');
        Route::patch('/password/update', 'updatePassword')->name('password.update');

        // Referrals
        Route::controller(ReferralsController::class)
            ->group(function () {
                Route::get('/referrals', 'index')->name('referrals');
            });

        // Kyc Verification
        Route::controller(KycController::class)
            ->group(function () {
                Route::get('/kyc', 'index')->name('kyc');

                Route::get('/kyc/create', 'create')->name('kyc.create')
                    ->middleware('kyc.check');
                Route::post('/kyc/store', 'store')->name('kyc.store')
                    ->middleware('kyc.check');

                // Upload image
                Route::post('/kyc/process/image', 'processImage')->name('kyc.process.image');
            });

        // Wallet
        Route::controller(WalletController::class)
            ->group(function () {
                Route::get('/wallet', 'index')->name('wallet');
                Route::post('/wallet', 'deposit')->name('wallet.deposit');
                Route::post('/wallet/fetch/converted', 'convert')->name('wallet.fetch.converted.amount');
                Route::get('/chart-data',  'getDailyIncomeExpense')->name('chart.data');
            });

        // Same Bank Transfer
        Route::controller(InterBankTransferController::class)
            ->group(function () {
                Route::post('/fetch/account-details', 'index')->name('fetch.account.details');
                Route::post('/interbank-transfer', 'store')->name('perform.interbank.transfer');
            });

        // Domestic Transfers
        Route::controller(DomesticTransferController::class)
            ->group(function () {
                Route::get('/domestic/transfer', 'index')->name('domestic.transfer');
                Route::post('/domestic/transfer/create', 'create')->name('domestic.transfer.create');
                Route::post('/domestic/transfer/store', 'store')->name('domestic.transfer.store');

                Route::get('/domestic/{domestic}/show', 'show')
                    ->name('domestic.transfer.show')
                    ->middleware('can:view,domestic');
            });

        // Wire Transfers
        Route::controller(WireTransferController::class)
            ->group(function () {
                Route::get('/wire/transfer', 'index')->name('wire.transfer');
                Route::post('/wire/transfer/create', 'create')->name('wire.transfer.create');
                Route::post('/wire-transfer/store', 'store')->name('wire.transfer.store');

                Route::get('/wire/{wire}/show', 'show')
                    ->name('wire.transfer.show')
                    ->middleware('can:view,wire');
            });

        // Loan Financing
        Route::controller(LoanController::class)
            ->group(function () {
                Route::get('/loan', 'index')->name('loan');
                Route::get('/loan/create', 'create')->name('loan.create');
                Route::post('/loan/store', 'store')->name('loan.store');

                Route::get('/loan/{loan}/show', 'show')
                    ->name('loan.show')
                    ->middleware('can:view,loan');

                Route::post('/loan/{loan}/update', 'update')
                    ->name('loan.update')
                    ->middleware('can:update,loan');
            });

        // Goals & Savings
        Route::controller(GoalController::class)
            ->group(function () {
                Route::get('/goal', 'index')->name('goal');
                Route::get('/goal/create', 'create')->name('goal.create');
                Route::post('/goal/store', 'store')->name('goal.store');

                Route::post('/goal/{goal}/fund', 'fund')
                    ->name('goal.fund')
                    ->middleware('can:update,goal');

                Route::post('/goal/{goal}/withdraw', 'withdraw')
                    ->name('goal.withdraw')
                    ->middleware('can:update,goal');

                Route::delete('/goal/{goal}/delete', 'destroy')
                    ->name('goal.delete')
                    ->middleware('can:delete,goal');
            });

        // Virtual Cards
        Route::controller(CardController::class)
            ->group(function () {
                Route::post('/card/store', 'store')->name('card.store');
            });

        // Investments
        Route::controller(InvestmentController::class)
            ->group(function () {
                Route::get('/investment', 'index')->name('investment');
                Route::get('/investment/plans', 'plans')->name('investment.plans');
                Route::get('/investment/list', 'list')->name('investment.list');
                Route::get('/investment/categories/{slug}', 'categories')->name('investment.categories');

                Route::post('/investment/store', 'store')->name('investment.store');

                Route::post('/investment/{investment}/liquidate', 'liquidate')
                    ->name('investment.liquidate')
                    ->middleware('can:liquidate,investment');

                Route::get('/investment/{investment}/show', 'show')
                    ->name('investment.show')
                    ->middleware('can:view,investment');
            });

        // Help Center
        Route::controller(HelpCenterController::class)
            ->group(function () {
                Route::get('/contact', 'index')->name('contact.us');
                Route::post('/contact/store', 'store')->name('contact.store');
            });

        // Statistics
        Route::controller(StatisticsController::class)
            ->group(function () {
                Route::get('/statistics', 'index')->name('statistics');
            });

        // Calculator
        Route::controller(CalculatorController::class)
            ->group(function () {
                Route::get('/calculator', 'index')->name('calculator');
            });

        // Transactions
        Route::controller(TransactionsController::class)
            ->group(function () {
                Route::get('/transactions', 'index')->name('transactions');
            });
    });

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::controller(AdminDashboardController::class)
        ->middleware(['auth', 'can:access-admin-dashboard'])
        ->group(function () {
            // Dashboard home
            Route::get('/dashboard', 'index')->name('dashboard');

            // Admin profile management
            Route::get('/profile', 'profilePage')->name('profile');
            Route::patch('/profile', 'updateProfile')->name('profile.update');
            Route::post('/profile/account-details', 'updateAccountDetails')->name('profile.account.details.update');
            Route::post('/picture/update', 'updateProfilePicture')->name('picture.update');
            Route::delete('/picture/remove', 'removeProfilePicture')->name('picture.remove');
            Route::patch('/password/update', 'updatePassword')->name('password.update');

            // Referrals management
            Route::controller(AdminReferralsController::class)
                ->group(function () {
                    Route::get('/referrals', 'index')->name('referrals');
                });

            // User management
            Route::controller(AdminUserController::class)->group(function () {
                Route::get('/users', 'index')->name('users');
                Route::get('/users/create', 'create')->name('users.create');
                Route::post('/users/store', 'store')->name('users.store');
                Route::get('/users/{user}/show', 'show')->name('users.show');
                Route::get('/users/{user}/edit', 'edit')->name('users.edit');
                Route::patch('/users/{user}/update', 'update')->name('users.update');
                Route::post('/users/{user}/account-details', 'updateAccountDetails')->name('users.account.details.update');
                Route::post('/users/{user}/picture/update', 'updateProfilePicture')->name('users.picture.update');
                Route::delete('/users/{user}/picture/remove', 'removeProfilePicture')->name('users.picture.remove');
                Route::post('/users/{user}/funds', 'manageFunds')->name('users.funds');
                Route::post('/users/{user}/email', 'sendEmail')->name('users.email');
                Route::post('/users/{user}/reset-password', 'resetPassword')->name('users.reset-password');
                Route::post('/users/{user}/block', 'block')->name('users.block');
                Route::delete('/users/{user}/delete', 'delete')->name('users.delete');
                Route::post('/users/{user}/login', 'loginAsUser')->name('users.login');
                Route::post('/users/{user}/card/store', 'cardStore')->name('users.card.store');
            });

            // KYC verification
            Route::controller(AdminKycController::class)
                ->group(function () {
                    Route::get('/kyc', 'index')->name('kyc');
                    Route::post('/kyc/{kyc}/approve', 'approve')->name('kyc.approve');
                    Route::post('/kyc/{kyc}/reject', 'reject')->name('kyc.reject');
                    Route::get('/kyc/modal/{kyc}', 'modal')->name('admin.kyc.modal');
                });

            // Deposit management
            Route::controller(AdminDepositsController::class)
                ->group(function () {
                    Route::get('/deposits', 'index')->name('deposits');
                    Route::get('/deposits/methods', 'methods')->name('deposits.methods');
                    Route::get('/deposits/alert', 'create')->name('deposits.alert');
                    Route::post('/deposits', 'store')->name('deposits.store');
                    Route::get('/deposits/{deposit}/show', 'show')->name('deposits.show');
                    Route::get('/deposits/{deposit}/edit', 'edit')->name('deposits.edit');
                    Route::post('/deposits/{deposit}/update', 'update')->name('deposits.update');
                    Route::delete('/deposits/{deposit}/delete', 'destroy')->name('deposits.delete');
                    Route::patch('/deposits/{deposit}/approve', 'approve')->name('deposits.approve');
                    Route::patch('/deposits/{deposit}/reject', 'reject')->name('deposits.reject');
                });

            // Interbank transfers
            Route::controller(AdminInterBankTransferController::class)
                ->group(function () {
                    Route::get('/interbank', 'index')->name('interbank');
                    Route::get('/interbank/create', 'create')->name('interbank.create');
                    Route::post('/interbank', 'store')->name('interbank.store');
                    Route::get('/interbank/{interbank}/show', 'show')->name('interbank.show');
                    Route::get('/interbank/{interbank}/edit', 'edit')->name('interbank.edit');
                    Route::post('/interbank/{interbank}/update', 'update')->name('interbank.update');
                    Route::delete('/interbank/{interbank}/delete', 'destroy')->name('interbank.delete');
                    Route::post('/interbank/{interbank}/approve', 'approve')->name('interbank.approve');
                    Route::post('/interbank/{interbank}/reject', 'reject')->name('interbank.reject');
                });

            // Domestic transfers
            Route::controller(AdminDomesticTransferController::class)
                ->group(function () {
                    Route::get('/domestic', 'index')->name('domestic');
                    Route::get('/domestic/create', 'create')->name('domestic.create');
                    Route::post('/domestic', 'store')->name('domestic.store');
                    Route::get('/domestic/{domestic}/show', 'show')->name('domestic.show');
                    Route::get('/domestic/{domestic}/edit', 'edit')->name('domestic.edit');
                    Route::post('/domestic/{domestic}/update', 'update')->name('domestic.update');
                    Route::delete('/domestic/{domestic}/delete', 'destroy')->name('domestic.delete');
                    Route::post('/domestic/{domestic}/approve', 'approve')->name('domestic.approve');
                    Route::post('/domestic/{domestic}/reject', 'reject')->name('domestic.reject');
                });

            // Wire transfers
            Route::controller(AdminWireTransferController::class)
                ->group(function () {
                    Route::get('/wire', 'index')->name('wire');
                    Route::get('/wire/create', 'create')->name('wire.create');
                    Route::post('/wire', 'store')->name('wire.store');
                    Route::get('/wire/{wire}/show', 'show')->name('wire.show');
                    Route::get('/wire/{wire}/edit', 'edit')->name('wire.edit');
                    Route::post('/wire/{wire}/update', 'update')->name('wire.update');
                    Route::delete('/wire/{wire}/delete', 'destroy')->name('wire.delete');
                    Route::post('/wire/{wire}/reject', 'approve')->name('wire.approve');
                    Route::post('/wire/{wire}/reject', 'reject')->name('wire.reject');
                });

            // Loan financing
            Route::controller(AdminLoanController::class)
                ->group(function () {
                    Route::get('/loans', 'index')->name('loans');
                    Route::get('/loans/create', 'create')->name('loan.create');
                    Route::post('/loans/store', 'store')->name('loan.store');
                    Route::get('/loans/{loan}/show', 'show')->name('loan.show');
                    Route::get('/loans/{loan}/edit', 'edit')->name('loan.edit');
                    Route::post('/loans/{loan}/update', 'update')->name('loan.update');
                    Route::delete('/loans/{loan}/delete', 'destroy')->name('loan.delete');
                    Route::post('/loans/{loan}/approve', 'approve')->name('loan.approve');
                    Route::post('/loans/{loan}/reject', 'reject')->name('loan.reject');
                });

            // Goals & savings
            Route::controller(AdminGoalController::class)
                ->group(function () {
                    Route::get('/goals', 'index')->name('goals');
                    Route::get('/goals/categories', 'categories')->name('goal.categories');
                    Route::post('/goals/categories/store', 'storeCategories')->name('goals.categories.store');
                    Route::get('/goals/create', 'create')->name('goal.create');
                    Route::post('/goals', 'store')->name('goal.store');
                    Route::get('/goals/{goal}/show', 'show')->name('goal.show');
                    Route::get('/goals/{goal}/edit', 'edit')->name('goal.edit');
                    Route::post('/goals/{goal}/update', 'update')->name('goal.update');
                    Route::delete('/goals/{goal}/delete', 'destroy')->name('goal.delete');
                    Route::post('/goals/{goal}/approve', 'approve')->name('goal.approve');
                    Route::post('/goals/{goal}/reject', 'reject')->name('goal.reject');
                });

            // Investments
            Route::controller(AdminInvestmentController::class)
                ->group(function () {
                    Route::get('/investments', 'index')->name('investments');
                    Route::get('/investments/plans', 'plans')->name('investment.plans');
                    Route::get('/investments/categories', 'planCategories')->name('investment.categories');
                    Route::post('/investments/categories/store', 'storePlanCategories')->name('investment.categories.store');
                    Route::get('/investments/create', 'create')->name('investment.create');
                    Route::post('/investments', 'store')->name('investment.store');
                    Route::get('/investments/{investment}/show', 'show')->name('investment.show');
                    Route::get('/investments/{investment}/edit', 'edit')->name('investment.edit');
                    Route::post('/investments/{investment}/update', 'update')->name('investment.update');
                    Route::delete('/investments/{investment}/delete', 'destroy')->name('investment.delete');
                    Route::post('/investments/{investment}/approve', 'approve')->name('investment.approve');
                    Route::post('/investments/{investment}/reject', 'reject')->name('investment.reject');
                });

            // Transactions
            Route::controller(AdminTransactionsController::class)
                ->group(function () {
                    Route::get('/transactions', 'index')->name('transactions');
                });

            // Email notifications
            Route::controller(AdminEmailNotificationsController::class)
                ->group(function () {
                    Route::get('/email/notifications', 'index')->name('email.notifications');
                    Route::post('email/broadcast', 'broadcast')->name('email.broadcast');
                });
        });
});
