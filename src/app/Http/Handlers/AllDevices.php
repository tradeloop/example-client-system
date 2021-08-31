<?php

declare(strict_types=1);

namespace App\Http\Handlers;

use App\Facades\ObadaClient;
use App\Models\Device;
use Yajra\DataTables\DataTables;

class AllDevices extends Handler {
    public function __invoke(Datatables $datatables) {
        return $datatables->eloquent(Device::orderBy('id', 'asc'))
            ->rawColumns(['id', 'manufacturer','part_number','serial_number','owner'])
            ->addColumn('local_checksum', function(Device $device) {
                try {
                    $result = ObadaClient::checksum($device->getLocalObit());
                    return $result->getChecksum();
                } catch(\Exception $e) {
                    return '';
                }
            })
            ->addColumn('obada_checksum', function(Device $device) {
                try {
                    $obit = ObadaClient::get($device->obit_did);
                    return $obit->getChecksum();
                } catch(\Exception $e) {
                    return '';
                }


            })
            ->make(true);
    }
}
