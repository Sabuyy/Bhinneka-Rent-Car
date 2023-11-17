<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Auth\Model;
use Core\Facades\DB;
use Phalcon\Mvc\Model;
use Core\Models\Behavior\SoftDelete;
use PDO;
use Phalcon\Mvc\Model\Behavior\Timestampable;

class PdamModel extends Model
{
    public function initialize()
    {
        $this->setSource('pdam');
    }
}