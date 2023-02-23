<?php

namespace App\Providers;

use App\TarkovApi\Client\ItemClientInterface;
use App\TarkovApi\Client\TarkovDevItemClient;
use App\TarkovApi\Service\ItemService;
use App\TarkovApi\Service\ItemServiceInterface;
use App\TarkovApi\TarkovApi;
use App\TarkovApi\TarkovApiInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ItemServiceInterface::class, ItemService::class);
        $this->app->bind(ItemClientInterface::class, TarkovDevItemClient::class);
        $this->app->bind(TarkovApiInterface::class, TarkovApi::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::preventSilentlyDiscardingAttributes(!$this->app->isProduction());
    }
}
