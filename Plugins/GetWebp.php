<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Plugins;

class GetWebp {

    /**
     * @param string $source
     * @param int $quality
     * @param bool $removeOld
     * @return string
     */
    public function process(string $source, int $quality = 100, bool $removeOld = false): string {
        $dir = pathinfo($source, PATHINFO_DIRNAME);
        $name = pathinfo($source, PATHINFO_FILENAME);
        $destination = $dir.DIRECTORY_SEPARATOR.$name.'.webp';

        if (!file_exists(ROOT_DIR."www".$destination)){
            $info = getimagesize(ROOT_DIR."www".$source);
            $isAlpha = false;
            if ($info['mime'] == 'image/jpeg') {
                $image = imagecreatefromjpeg(ROOT_DIR . "www" . $source);
            }
            elseif ($isAlpha = $info['mime'] == 'image/gif') {
                $image = imagecreatefromgif(ROOT_DIR."www".$source);
            } elseif ($isAlpha = $info['mime'] == 'image/png') {
                $image = imagecreatefrompng(ROOT_DIR."www".$source);
            } else {
                return $source;
            }
            if ($isAlpha) {
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
            imagewebp($image, ROOT_DIR."www".$destination, $quality);
            if ($removeOld) {
                unlink($source);
            }
        }

        return $destination;
    }

}
