<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Plugins;

use DI\DependencyException;
use DI\NotFoundException;
use Modules\View\AbstractPlugin;

class GetDomain extends AbstractPlugin {

    /**
     * @return string
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function process(): string {
        $config = $this->getContainer()->get("config")->getSetting("domain");
        return $config["protocol"]."://".$config["name"];
    }
}
