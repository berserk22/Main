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

class GetParentPages extends AbstractPlugin {

    use MainTrait;

    /**
     * @param string $page
     * @return mixed
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function process(string $page): mixed {
        $page = $this->getMainManager()->getPageEntity()::select('id', 'name')->where('name', '=', $page)->first();
        return $this->getMainManager()->getPageEntity()::select('id', 'name', 'title')->where('parent_id', '=', $page->id)->get();
    }

}
