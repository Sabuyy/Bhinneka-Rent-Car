<?php

namespace App\Modules\Defaults\ModelStandAlone;

use Phalcon\Mvc\Model as BaseModel;
use Core\Models\Behavior\SoftDelete;
use Phalcon\Mvc\Model\Behavior\Timestampable;

class ModelAkunPerkiraan extends BaseModel
{
    public function initialize()
    {
        $this->setConnectionService('db');
        $this->setSource('vw_perkiraan_satker');
    }
}