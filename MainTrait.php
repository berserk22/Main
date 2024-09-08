<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main;

use DI\DependencyException;
use DI\NotFoundException;
use Core\Traits\App;
use Modules\I18n\Manager\I18nModel;
use Modules\Main\Manager\MainManager;
use Modules\Main\Manager\MainModel;

trait MainTrait {

    use App;

    /**
     * @return Router|null
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getMainRouter(): ?Router {
        return $this->getContainer()->get('Main\Router');
    }

    /**
     * @return ApiRouter|null
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getMainApiRouter(): ?ApiRouter {
        return $this->getContainer()->get('Main\ApiRouter');
    }

    /**
     * @return MainManager|null
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getMainManager(): ?MainManager {
        return $this->getContainer()->get('Main\Manager');
    }

    /**
     * @return I18nModel
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getI18nModel(): I18nModel {
        return $this->getContainer()->get('I18n\Model');
    }

    /**
     * @return MainModel
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getMainModel():MainModel {
        return $this->getContainer()->get('Main\Model');
    }
}
