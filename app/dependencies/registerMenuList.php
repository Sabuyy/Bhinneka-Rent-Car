<?php

use App\Modules\Defaults\Auth\Model\MenuModel;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

$diContainer->setShared('menuModel', function() {
    return new MenuModel();
});

// $diContainer->setShared('dbsecond', function ()  {
// 	return new DbAdapter([
// 		'host' => "43.252.138.89",
// 		'username'     => 'root',
//         'password'     => 'Mpk080194!!!',
// 		'dbname' => 'asasta_accis'
// 	]);
// });