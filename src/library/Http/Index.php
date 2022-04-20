<?php

declare(strict_types=1);

namespace App\Ebcms\Installer\Http;

use App\Ebcms\Admin\Traits\ResponseTrait;
use App\Ebcms\Admin\Traits\RestfulTrait;
use DigPHP\Request\Request;
use DigPHP\Template\Template;
use Ebcms\Framework\Framework;
use PDO;
use Rah\Danpu\Dump;
use Rah\Danpu\Import;
use Throwable;

class Index
{

    use RestfulTrait;
    use ResponseTrait;

    public function get(
        Request $request,
        Template $template
    ) {
        return $template->renderFromFile('step' . $request->get('step', '0') . '@ebcms/installer');
    }

    public function post(
        Template $template,
        Request $request,
        Dump $dump
    ) {
        try {

            $sql_file = Framework::getRoot() . ($request->post('demo') == '1' ? '/install_demo.sql' : '/install.sql');
            if (is_file($sql_file)) {
                $dump
                    ->file($sql_file)
                    ->dsn('mysql:dbname=' . $request->post('database_name') . ';host=' . $request->post('database_server'))
                    ->user($request->post('database_username'))
                    ->pass($request->post('database_password'))
                    ->attributes([
                        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                    ])
                    ->tmp(Framework::getRoot() . '/runtime');
                new Import($dump);
            }

            $databasetpl = <<<'str'
<?php
return [
    'master'=>[
        'database_type' => 'mysql',
        'database_name' => '{database_name}',
        'server' => '{server}',
        'username' => '{username}',
        'password' => '{password}',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_general_ci',
        'port' => '{port}',
        'logging' => false,
        'option' => [
            \PDO::ATTR_CASE => \PDO::CASE_NATURAL,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_STRINGIFY_FETCHES => false,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ],
        'command' => ['SET SQL_MODE=ANSI_QUOTES'],
    ],
];
str;

            $database_file = Framework::getRoot() . '/config/database.php';
            if (!file_exists($database_file)) {
                if (!is_dir(dirname($database_file))) {
                    mkdir(dirname($database_file), 0755, true);
                }
            }
            file_put_contents($database_file, str_replace([
                '{server}',
                '{port}',
                '{database_name}',
                '{username}',
                '{password}',
            ], [
                $request->post('database_server'),
                $request->post('database_port'),
                $request->post('database_name'),
                $request->post('database_username'),
                $request->post('database_password'),
            ], $databasetpl));
            if (!is_dir(Framework::getRoot() . '/config/ebcms/installer/')) {
                mkdir(Framework::getRoot() . '/config/ebcms/installer/', 0755, true);
            }
            file_put_contents(Framework::getRoot() . '/config/ebcms/installer/disabled.lock', date('Y-m-d H:i:s'));
        } catch (Throwable $th) {
            return $this->error('发生错误：' . $th->getMessage());
        }

        return $template->renderFromFile('success@ebcms/installer', [
            'account' => 'admin',
            'password' => '123456',
        ]);
    }
}
