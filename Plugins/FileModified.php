<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Plugins;

class FileModified {

    /**
     * @param string|null $variable
     * @return string|null
     */
    public function process(string $variable = null): string|null {
        if (!is_null($variable)){
            $path = realpath(__DIR__.'/../../../www').$variable;
            if (file_exists($path)) {
                return $variable.'?ver='.filemtime($path);
            }
        }
        return $variable;
    }

}
