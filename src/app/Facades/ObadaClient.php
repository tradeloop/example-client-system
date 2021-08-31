<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static get(string $did): \Obada\Entities\Obit
 * @method static search($q = null, $offset = 0): \Obada\Entities\Obits
 */
class ObadaClient extends Facade
{
    protected static function getFacadeAccessor() { return 'obada_client'; }
}
