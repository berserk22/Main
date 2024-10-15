<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Plugins;

class GetPrice {

    /**
     * @var string
     */
    private string $decimalsSeparator = ",";

    /**
     * @param string|null $price
     * @param int $decimals
     * @param string $vendor_number
     * @return string
     */
    public function process(string|null $price, int $decimals = 2, string $vendor_number = ''): string {
        if ($price === null) {
            $price = 0;
        }
        if ($decimals === 0) {
            $this->decimalsSeparator = "";
        }
        $price = number_format((float)$price, $decimals, $this->decimalsSeparator, '');
        if ($price > 0){
            list($main, $sub) = explode(',', $price);
            if(is_null($sub)){
                $summe = sprintf("%s&nbsp;&euro;", $main);
            }
            else {
                $summe = sprintf("%s,%s&nbsp;&euro;", $main, $sub);
            }
            return $summe;
        }
        else {
            if (!empty($vendor_number)){
                return '-,--';
            }
            return '0,00&nbsp;&euro;';
        }
    }

}
