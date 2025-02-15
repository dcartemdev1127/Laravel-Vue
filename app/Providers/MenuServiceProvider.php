<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
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
        $this->defineAdminMenu();
    }

    protected function defineAdminMenu() {
        if( request()->is('admin') || request()->is('admin/*')) {
            $adminMenu = json_decode(file_get_contents(storage_path('app/menus/adminMenu.json')));
            View::share('menuData', $adminMenu);
        } else {
            $dashboardMenu = json_decode(file_get_contents(storage_path('app/menus/dashboardMenu.json')));
            View::share('menuData', $dashboardMenu);
        }
    }
}
