<?php

use Illuminate\Contracts\Console\Kernel;
use Symfony\Component\Console\Output\ConsoleOutput;

require __DIR__.'/../vendor/autoload.php';

$envFile = __DIR__.'/../.env';

if (! is_file($envFile) && ! copy($envFile.'.example', $envFile)) {
    exit(1);
}

$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

if (filled($app['config']->get('app.key'))) {
    exit(0);
}

$status = $kernel->call('key:generate', [], new ConsoleOutput);

exit(filled($app['config']->get('app.key')) ? $status : 1);
