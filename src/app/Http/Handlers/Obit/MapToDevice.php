<?php

declare(strict_types=1);

namespace App\Http\Handlers\Obit;

use App\Facades\ObadaClient;
use App\Http\Handlers\Handler;
use App\Http\Requests\Obit\ObitDidRequest;
use App\Services\DeviceService\ServiceContract;
use Throwable;
use Illuminate\Support\Facades\Log;

class MapToDevice extends Handler {

    /**
     * @var ServiceContract
     */
    protected $service;

    /**
     * @param ServiceContract $service
     */
    public function __construct(ServiceContract $service) {
        $this->service = $service;
    }

    /**
     * @param ObitDidRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ObitDidRequest $request) {
        $did = $request->get('obit_did');

        try {
            $obit = ObadaClient::get($did);

            $device = $this->service->fromObitToDevice($obit);

            return response()->json(['device' => $device]);
        } catch(Throwable $t) {
            $msg = 'Error Getting Client Obit';

            Log::error($msg, [$t]);

            return response()->json(['errorMessage' => 'Error Getting Client Obit'], 400);
        }

        return response()->json(['device' => $device]);
    }
}
