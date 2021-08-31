<?php

declare(strict_types=1);

namespace App\Services\DeviceService;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {
    public function register() {
        $this->app->bind(ServiceContract::class, Service::class);
    }
}
