<?php

declare(strict_types=1);

namespace App\Http\Handlers\Obit;

use App\Facades\ObadaClient;
use App\Http\Handlers\Handler;
use App\Http\Requests\Obit\ObitDidRequest;
use Throwable;
use Illuminate\Support\Facades\Log;

class Download extends Handler {
    /**
     * @param ObitDidRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ObitDidRequest $request) {
        try {
            $obit = ObadaClient::get($request->get('obit_did'));

            return response()->json(['obit' => $obit]);
        } catch(Throwable $t) {
            $msg = 'Error Uploading Obit';
            Log::error($msg, [$t]);

            return response()->json(['errorMessage' => $msg], 400);
        }
    }
}
