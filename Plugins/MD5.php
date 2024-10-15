<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Plugins;

class MD5 {

    /**
     * @param string|null $str
     * @return string
     */
    public function process(string|null $str = ''): string {
        $hash = "";
        if (!empty($str)){
            $hash = hash("sha512", $str);
        }
        return $hash;
    }

}
