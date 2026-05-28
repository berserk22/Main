<?php

/**
 * @author Sergey Tevs
 * @email sergey@tevs.org
 */

namespace Modules\Main\Db\Models;

use Illuminate\Database\Eloquent\Collection;
use Modules\Database\Model;

class PageGroup extends Model {

    /**
     * @var string
     */
    protected $table = "page_group";

    /**
     * @return Collection
     */
    public function getPages(string $status = "publish"): Collection {
        return $this->hasMany('Modules\Main\Db\Models\Page')->where("status", "=", $status)->get();
    }

}
