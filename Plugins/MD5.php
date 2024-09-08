<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Plugins;

class MD5 {

    /**
     * @param string $str
     * @return string
     */
    public function process(string $str = ''): string {
        $hash = "";
        if (!empty($str)){
            $hash = hash("sha512", $str);
        }
        return $hash;
    }

}
