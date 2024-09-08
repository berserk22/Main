<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Console;

use Core\Console\Command;
use Modules\Main\MainTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DBProcessCheck extends Command {

    use MainTrait;

    public function __construct($application) {
        parent::__construct($application);
    }

    /**
     * @return void
     */
    protected function configure(): void {
        $this->setName('main:db_check');
    }

    protected function execute (InputInterface $input, OutputInterface $output): int {
        return 1;
    }
}
