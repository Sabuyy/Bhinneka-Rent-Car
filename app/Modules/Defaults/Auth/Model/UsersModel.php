<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Auth\Model;

use Phalcon\Mvc\Model;
use Core\Models\Behavior\SoftDelete;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use App\Modules\Defaults\Auth\Model\RolesModel;
use App\Modules\Defaults\ModelStandAlone\CabangModel;

class UsersModel extends Model
{
    public function initialize()
    {
        $this->setConnectionService('db.main');
        $this->setSource('user');
        
        $this->addBehavior(new Timestampable([
            'beforeCreate' => ['field' => 'created_at', 'format' => 'Y-m-d H:i:s'],
            'beforeUpdate' => ['field' => 'updated_at', 'format' => 'Y-m-d H:i:s'],
        ]));
        
        $this->hasOne('id_role', RolesModel::class, 'id', ['alias' => 'role', 'reusable' => true]);
        // $this->hasOne('of_id', CabangModel::class, 'of_id', ['alias' => 'office', 'reusable' => true]);
    }
}