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

class GetPageGroup extends AbstractPlugin {

    use MainTrait;

    /**
     * @param int|string|null $group
     * @return mixed
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function process(int|string $group = null): mixed {
        return $this->getMainModel()->getPageGroup($group);
    }

}
