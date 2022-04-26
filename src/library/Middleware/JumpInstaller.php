<?php

declare(strict_types=1);

namespace App\Ebcms\Installer\Middleware;

use DiggPHP\Framework\Framework;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JumpInstaller implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        return Framework::execute(function (
            ResponseFactoryInterface $responseFactory,
            $request_app
        ) use ($request, $handler): ResponseInterface {
            if ($request_app != 'ebcms/installer') {
                $response = $responseFactory->createResponse(302);
                return $response->withHeader('Location', $this->getSite() . '/ebcms/installer/index');
            }
            return $handler->handle($request);
        });
    }

    private function getSite(): string
    {
        if (
            (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https')
            || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')
        ) {
            $schema = 'https';
        } else {
            $schema = 'http';
        }

        return $schema . '://' . $_SERVER['HTTP_HOST'] . (function (): string {
            $script_name = '/' . implode('/', array_filter(
                explode('/', $_SERVER['SCRIPT_NAME']),
                function ($val) {
                    return strlen($val) > 0 ? true : false;
                }
            ));
            return $script_name;
        })();
    }
}
