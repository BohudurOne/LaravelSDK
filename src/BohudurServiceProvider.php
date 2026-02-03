<?php
namespace App\Modules\Bohudur;

use Illuminate\Support\ServiceProvider;
use App\Modules\Bohudur\Services\BohudurService;

class BohudurServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->singleton(BohudurService::class, function ($app) {
            return new BohudurService();
        });
        $this->mergeConfigFrom(__DIR__ . '/Config/bohudur.php', 'bohudur');
    }
    public function boot() {
        $this->publishes([
            __DIR__ . '/Config/bohudur.php' => config_path('bohudur.php'),
        ], 'config');
    }
}