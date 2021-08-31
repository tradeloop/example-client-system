<?php

declare(strict_types=1);

namespace App\Services\DeviceService;

use Obada\Entities\Obit;

interface ServiceContract {

    public function save();

    /**
     * @param Obit $obit
     * @return mixed
     */
    public function fromObitToDevice(Obit $obit);
}
