<?php

namespace App\Providers;
use App\Models\Banner;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Paginator::useBootstrapThree();
        if(env('APP_URL', 'http://localhost') != 'http://localhost'){
            URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);
        $bannerHeader = Banner::where('is_active', 1)->where('type', 6)->first();
        view()->share('bannerHeader', $bannerHeader);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
