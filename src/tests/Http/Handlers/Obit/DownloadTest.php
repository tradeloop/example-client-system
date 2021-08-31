<?php

declare(strict_types=1);

namespace Tests\Http\Handlers\Obit;

use App\Facades\ObadaClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tests\TestCase;
use TiMacDonald\Log\LogFake;
use Exception;

class DownloadTest extends TestCase {
    public function testHandlerErrors() {
        $this->json('POST', route('obit.download'))
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'obit_did' => [
                        'The obit did field is required.'
                    ]
                ]
            ]);

        Log::swap(new LogFake);

        $did = 'did:obada:773fc61f84a1b7003c22f41f25896f423a5c0e1d9cb98e087a9e15b9bdad8c92';

        ObadaClient::shouldReceive('get')
            ->with($did)
            ->andThrow(Exception::class, "OBADA client error");

        $this->json('POST', route('obit.download',  ['obit_did' => $did]))
            ->assertStatus(400)
            ->assertJson([
                'errorMessage' => 'Error Uploading Obit',
            ]);

        Log::assertLogged('error', function ($message, $context) {
            return Str::contains($message, 'Error Uploading Obit') && Arr::get($context, 0);
        });
    }
}
