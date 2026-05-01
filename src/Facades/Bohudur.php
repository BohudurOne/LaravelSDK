<?php
namespace App\Modules\Bohudur\Facades;

use Illuminate\Support\Facades\Facade;

class Bohudur extends Facade {
    protected static ?string $apiKey = null;

    protected static function getFacadeAccessor() {
        return \App\Modules\Bohudur\Services\BohudurService::class;
    }

    public static function init(string $apiKey): void {
        static::$apiKey = $apiKey;
    }

    public static function request() {
        $service = app(static::getFacadeAccessor());

        $request = $service->request(static::$apiKey);

        static::$apiKey = null;

        return $request;
    }

    public static function query(string $paymentKey) {
        $service = app(static::getFacadeAccessor());

        if (static::$apiKey) {
            $service->setApiKey(static::$apiKey);
            static::$apiKey = null;
        }

        return $service->query($paymentKey);
    }

    public static function execute(string $paymentKey) {
        $service = app(static::getFacadeAccessor());

        if (static::$apiKey) {
            $service->setApiKey(static::$apiKey);
            static::$apiKey = null;
        }

        return $service->execute($paymentKey);
    }
}
