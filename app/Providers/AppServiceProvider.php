<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Produk;
use App\Enums\Role;

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
        View::composer('components.navbar', function ($view) {
            $user = getActiveUser();
            if ($user && in_array($user->role, [Role::ADMINGUDANG, Role::KASIR])) {
                $produkMenipis = Produk::all()->filter(function ($produk) {
                    return $produk->is_stok_menipis;
                });
                $view->with('produkMenipis', $produkMenipis);
            }
        });
    }
}
