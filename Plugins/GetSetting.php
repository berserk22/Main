<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Plugins;

use Core\Cache\FileCache;
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
        $cacheKey = 'main_setting_' . $key;
        $apcuAvailable = function_exists('apcu_enabled') && apcu_enabled();
        if ($apcuAvailable) {
            $cached = apcu_fetch($cacheKey, $found);
            if ($found) {
                return $cached;
            }
        } else {
            $fileCache = new FileCache(ROOT_DIR . 'cache/settings');
            $cached = $fileCache->get($cacheKey);
            if ($cached !== null) {
                return $cached;
            }
        }
        $setting = $this->getMainManager()->getSettingsEntity()::where('key', '=', $key)->first();
        $value = !is_null($setting) ? $setting->value : "";
        if ($apcuAvailable) {
            apcu_store($cacheKey, $value, 3600);
        } else {
            $fileCache->set($cacheKey, $value, 3600);
        }
        return $value;
    }

}
