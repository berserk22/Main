<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Db\Models;

use Modules\Database\Model;

class Page extends Model {

    /**
     * @var string
     */
    protected $table = "page";

    /**
     * @return object|null
     */
    public function getPageGroup(): null|object {
        return $this->belongsTo('Modules\Main\Db\Models\PageGroup')->first();
    }

}
