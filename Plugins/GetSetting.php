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

class GetSetting extends AbstractPlugin {

    use MainTrait;

    /**
     * @param string $key
     * @return mixed
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function process(string $key): mixed {
        $setting = $this->getMainManager()->getSettingsEntity()::where('key', '=', $key)->first();
        if (!is_null($setting)) {
            return $setting->value;
        }
        return "";
    }

}
