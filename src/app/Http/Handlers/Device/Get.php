<?php

declare(strict_types=1);

namespace App\Http\Handlers\Device;

use App\Facades\ObadaClient;
use App\Http\Handlers\Handler;
use App\Models\Device;

class Get extends Handler {
    public function __invoke($obitDid) {
        $device = Device::with('metadata','metadata.schema','documents','structured_data')
            ->where(['obit_did' => $obitDid])->first();

        if(! $device) {
            return response()->json([
                'status' => 1,
                'errorMessage' => 'Unable to find device'
            ], 404);
        }
        try {
            $checksum = ObadaClient::checksum($device->getLocalObit());

            return response()->json([
                'status'    => 0,
                'device'    => $device,
                'root_hash' => $checksum->getChecksum()
            ], 200);

        } catch(\Exception $e) {
            Log::info($e->getMessage());
            return response()->json([
                'errorMessage' => 'Error Generating Device Root Hash'
            ], 400);
        }
    }
 }
