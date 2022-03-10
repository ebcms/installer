<?php

declare(strict_types=1);

namespace App\Ebcms\Installer;

use App\Ebcms\Installer\Middleware\JumpInstaller;
use DigPHP\Framework\AppInterface;
use DigPHP\Framework\Framework;

class App implements AppInterface
{
    public static function onExecute()
    {
        Framework::bindMiddleware(JumpInstaller::class);
    }
}
