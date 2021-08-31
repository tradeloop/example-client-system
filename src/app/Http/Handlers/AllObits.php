<?php

declare(strict_types=1);

namespace App\Http\Handlers;

use App\Facades\ObadaClient;
use App\Models\Device;
use Obada\Entities\Obit;
use Yajra\DataTables\DataTables;

class AllObits extends Handler {
    public function __invoke(Datatables $datatables) {
        $result = ObadaClient::search();

        $obits = collect($result->getData())
            ->map(function (Obit $o) {
                return (array) $o->jsonSerialize();
            });

        return $datatables->collection($obits)
            ->rawColumns(['id', 'usn', 'serial_number_hash', 'part_number', 'manufacturer', 'owner_did', 'checksum'])
            ->addColumn('local_checksum', function($obit) {

                $device = Device::where(['obit_did' => $obit['obit_did']])->first();

                if($device) {
                    $result = ObadaClient::checksum($device->getLocalObit());

                    return $result->getChecksum();
                } else {
                    return '';
                }
            })
            ->addColumn('obada_checksum', function($obit) {
                $result = ObadaClient::get($obit['obit_did']);

                return $result->getChecksum();
            })
            ->make(true);
    }
}
