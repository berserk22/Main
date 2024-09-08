<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Plugins;

use DI\DependencyException;
use DI\NotFoundException;
use Modules\Main\MainTrait;
use Modules\View\AbstractPlugin;

class GetSiteSettings extends AbstractPlugin {

    use MainTrait;

    /**
     * @return array
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function process(): array {
        return $this->getMainModel()->getSettings(1);
    }

}
