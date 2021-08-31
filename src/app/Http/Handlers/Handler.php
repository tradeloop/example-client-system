<?php

declare(strict_types=1);

namespace App\Http\Handlers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class Handler extends Controller {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
