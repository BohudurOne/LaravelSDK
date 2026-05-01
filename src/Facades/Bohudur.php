<?php
namespace App\Modules\Bohudur\Facades;

use Illuminate\Support\Facades\Facade;
use App\Modules\Bohudur\Services\BohudurService;

/**
 * @method static void   init(string $apiKey)
 * @method static \App\Modules\Bohudur\Services\BohudurRequest request()
 * @method static mixed  query(string $paymentKey)
 * @method static mixed  execute(string $paymentKey)
 */
class Bohudur extends Facade {
    protected static function getFacadeAccessor() {
        return BohudurService::class;
    }

    /**
     * Override init() so it:
     *  1. Sets the static runtime key on BohudurService.
     *  2. Clears the singleton from Laravel's container so the next
     *     call to request()/query()/execute() picks up the new key.
     */
    public static function init(string $apiKey): void {
        BohudurService::init($apiKey);
        static::clearResolvedInstance(BohudurService::class);
    }
}
