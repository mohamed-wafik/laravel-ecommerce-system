<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

/**
 * Standard Laravel scaffolding. If the host app already has
 * tests/TestCase.php and tests/CreatesApplication.php (it almost
 * certainly does), skip copying these two files — everything else in
 * tests/ only depends on Laravel's normal testing base class.
 */
trait CreatesApplication
{
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
