<?php

declare(strict_types=1);

namespace App\Http\Handlers;

use App\Facades\ObadaClient;
use App\Http\Requests\UsnRequest;
use Illuminate\Support\Facades\Log;
use Obada\Entities\RequestObitDID;
use Throwable;
use Obada\ApiException;

class GenerateUsn extends Handler {
    /**
     * @param UsnRequest $request
     * @return \Illuminate\Http\JsonResponse|void
     * @throws ApiException
     */
    public function __invoke(UsnRequest $request) {
        try {
            $request = new RequestObitDID([
                'manufacturer' => $request->get('manufacturer'),
                'partNumber'   => $request->get('part_number'),
                'serialNumber' => $request->get('serial_number'),
            ]);

            $response = ObadaClient::generateDID($request);

            return response()->json(['usn' => $response]);
        } catch (ApiException $e) {
            if ($e->getCode() == 422) {
                return response()->json(['errorMessage' => $e->getMessage()], 422);
            }

            throw $e;
        } catch (Throwable $t) {
            Log::error('Cannot generate USN', [$t]);

            return response()->json(['errorMessage' => 'Internal Server Error'], 500);
        }
    }
}
