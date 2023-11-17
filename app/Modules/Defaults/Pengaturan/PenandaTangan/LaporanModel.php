<?php

declare(strict_types=1);
namespace App\Modules\Defaults\Pengaturan\PenandaTangan;

use Phalcon\Mvc\Model as BaseModel;
use Core\Models\Behavior\SoftDelete;
use Phalcon\Mvc\Model\Behavior\Timestampable;

class LaporanModel extends BaseModel
{
    public function initialize()
    {
        $this->setConnectionService('db');
        $this->setSource('master_laporan');
    }
}