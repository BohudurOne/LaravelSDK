<?php
namespace App\Modules\Bohudur\Facades;

use Illuminate\Support\Facades\Facade;

class Bohudur extends Facade {
    protected static function getFacadeAccessor() {
        return \App\Modules\Bohudur\Services\BohudurService::class;
    }
}