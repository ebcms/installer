<?php

declare(strict_types=1);

namespace App\Ebcms\Installer;

use App\Ebcms\Installer\Middleware\JumpInstaller;
use Ebcms\Framework\AppInterface;
use Ebcms\Framework\Framework;

class App implements AppInterface
{
    public static function onExecute()
    {
        Framework::bindMiddleware(JumpInstaller::class);
    }
}
