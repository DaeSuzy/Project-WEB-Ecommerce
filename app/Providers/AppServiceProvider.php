<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\CategoryRepositoryInterFace;
use App\Repositories\Contracts\DompetRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PromoCodeRepositoryInterface;
use App\Repositories\DompetRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PromoCodeRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(CategoryRepositoryInterFace::class, CategoryRepository::class);
        $this->app->singleton(DompetRepositoryInterface::class, DompetRepository::class);
        $this->app->singleton(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->singleton(PromoCodeRepositoryInterface::class, PromoCodeRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
