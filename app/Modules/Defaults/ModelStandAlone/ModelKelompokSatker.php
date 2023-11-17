<?php

namespace App\Modules\Defaults\ModelStandAlone;

use Phalcon\Mvc\Model as BaseModel;
use Core\Models\Behavior\SoftDelete;
use Phalcon\Mvc\Model\Behavior\Timestampable;

class ModelKelompokSatker extends BaseModel
{
    public function initialize()
    {
        $this->setConnectionService('db');
        $this->setSource('master_kelompok_satuan_kerja');
    }
}