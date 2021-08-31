<?php

declare(strict_types=1);

namespace Tests\Http\Handlers;

use App\Facades\ObadaClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Exception;
use Illuminate\Support\Str;
use TiMacDonald\Log\LogFake;

class GenerateUsnTest extends TestCase {
    public function testHandlerErrors() {
        $this->json('POST', route('generate.usn'))
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'part_number' => [
                        'The part number field is required.'
                    ],
                    'manufacturer' => [
                        'The manufacturer field is required.'
                    ],
                    'serial_number' => [
                        'The serial number field is required.'
                    ]
                ]
            ]);

        Log::swap(new LogFake);

        ObadaClient::shouldReceive('generateDID')
            ->andThrow(Exception::class, "OBADA client error");

        $requestBody = [
            'manufacturer'  => 'Sony',
            'part_number'   => 'PN12345',
            'serial_number' => 'SN12345'
        ];

        $this->json('POST', route('generate.usn', $requestBody))
            ->assertStatus(500)
            ->assertJson(['errorMessage' => 'Internal Server Error']);

        Log::assertLogged('error', function ($message, $context) {
            return Str::contains($message, 'Cannot generate USN') && Arr::get($context, 0);
        });
    }
}
