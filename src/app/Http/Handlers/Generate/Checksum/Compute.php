<?php 

declare(strict_types=1);

namespace App\Http\Handlers\Generate\Checksum;

use App\Http\Handlers\Handler;
use App\Http\Requests\ComputeUsnRequest;
use Obada\ClientHelper\GenerateObitDIDRequest;
use Obada\Api\ObitApi;
use Throwable;
use Log;

class Compute extends Handler {
}