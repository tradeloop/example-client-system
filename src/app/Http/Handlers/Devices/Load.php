<?php 

declare(strict_types=1);

namespace App\Http\Handlers\Devices;

use App\Http\Handlers\Handler;
use App\Models\Device;

class Load extends Handler {
    public function __invoke($usn)
    {
        $device = Device::with('documents')
            ->byUsn($usn)
            ->first();

        if (!$device) {
            return response()->json([
                'status' => 1,
                'errorMessage' => 'Unable to find device'
            ], 404);
        }
        try {
           // $result = $this->helperApi->generateRootHash($device->getLocalObit());

            return response()->json([
                'status' => 0,
                'device' => $device,
                'root_hash' => 'd', //$result['rootHash']
            ], 200);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json([
                'status' => 1,
                'errorMessage' => 'Error Generating Device Root Hash'
            ], 400);
        }
    }
}
