<?php

/** @noinspection ALL */

namespace App\Http\Controllers;

use App;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Metadata;
use App\Models\Documents;
use App\Models\StructuredData;
use App\Models\ClientObit;
use App\Models\Schema;
use App\Http\Requests\UsnRequest;
use Obada\Api\ObitApi;
use Obada\Entities\NewObit;
use Obada\Entities\MetaDataRecord;
use Obada\Entities\Obit;
use Obada\ApiException;
use Log;

class ServiceController extends Controller
{
}
