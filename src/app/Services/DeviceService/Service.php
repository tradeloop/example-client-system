<?php

declare(strict_types=1);

namespace App\Services\DeviceService;

use Obada\Entities\Obit;
use App\Models\Device;

class Service implements ServiceContract {

    public function save(){

    }

    /**
     * @param Obit $obit
     * @return mixed|void
     */
    public function fromObitToDevice(Obit $obit){
       $device = Device::where(['obit_did' => $obit->getObitDid()])->first();

        if(!$device) {
            $device = new Device();
        }

        $device->usn           = $obit->getUsn();
        $device->obit_did      = $obit->getObitDid();
        $device->owner         = $obit->getOwnerDid();
        $device->part_number   = $obit->getPartNumber();
        $device->serial_number = $obit->getSerialNumberHash();
        $device->manufacturer  = $obit->getManufacturer();
        $device->save();

        return $device;
    }
}
