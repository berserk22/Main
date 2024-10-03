<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Console;

use Core\Console\Command;
use Core\Exception;
use DI\DependencyException;
use DI\NotFoundException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection;
use Modules\Main\MainTrait;
use Monolog\Handler\Handler;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DBProcessCheck extends Command {

    use MainTrait;

    private Connection $db;

    public function __construct($application) {
        parent::__construct($application);
    }

    /**
     * @return void
     */
    protected function configure(): void {
        $this->setName('main:db_check');
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    private function getDB(): Connection {
        if (!property_exists($this, 'db')){
            $config = $this->getContainer()->get('config')->getSetting('database');

            $default = $config['main'];
            $default['driver'] = $config['driver'];
            $default['charset'] = $config['charset'];
            $default['collation'] = $config['collation'];

            $capsule = new Manager();
            $capsule->addConnection($default);
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            $capsule->getContainer()->singleton(
                ExceptionHandler::class,
                Handler::class
            );
            $this->db=$capsule->getConnection();
        }
        return $this->db;
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    protected function execute (InputInterface $input, OutputInterface $output): int {
        $processList="SHOW PROCESSLIST";
        var_dump($processList);
        try {
            var_dump($this->getDB()->getRawPdo()->query($processList)->fetchAll());
        } catch (Exception $e) {
            var_dump($e);
        }

        return 1;
    }
}
