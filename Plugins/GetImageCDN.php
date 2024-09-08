<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Plugins;

class GetImageCDN {

    /**
     * @param string|null $img
     * @param int $size
     * @return string
     */
    public function process(string|null $img, int $size = 300):string {
        $pathListe = explode("/", $img);
        $filename = $pathListe[array_key_last($pathListe)];
        unset($pathListe[array_key_last($pathListe)]);
        $tmpPath = implode("/", $pathListe).DIRECTORY_SEPARATOR.$size.DIRECTORY_SEPARATOR.$filename;
        if (file_exists(WEB_ROOT_DIR.$tmpPath)){
            return $tmpPath;
        }
        return is_null($img)?"":$img;
    }
}
