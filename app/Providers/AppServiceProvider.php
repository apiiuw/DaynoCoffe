<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Debt;
use App\Models\Bill;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
        $today = Carbon::today();

        $dueDebts = Debt::whereDate('due_date', '<=', $today->copy()->addDays(7))
            ->whereDate('due_date', '>=', $today)
            ->get();

        $dueBills = Bill::whereDate('due_date', '<=', $today->copy()->addDays(7))
            ->whereDate('due_date', '>=', $today)
            ->get();

        $view->with('dueDebts', $dueDebts)
             ->with('dueBills', $dueBills);
    });
    }
}
