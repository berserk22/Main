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

class GetActiveActions extends AbstractPlugin {

    use MainTrait;

    /**
     * @param bool $sort
     * @return mixed
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function process(bool $sort = true): mixed {
        if ($sort === false){
            return $this->getMainManager()->getActionsEntity()::where('status', '=', 1)->OrderBy('id', 'ASC')->get();
        }
        return $this->getMainManager()->getActionsEntity()::where('status', '=', 1)->OrderBy('id', 'DESC')->get();
    }
}
