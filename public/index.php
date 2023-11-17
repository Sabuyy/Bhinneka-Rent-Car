<?php

define('BASEPATH', realpath(__DIR__ . '/..'));
// define('ENVIRONMENT', $_SERVER['ENV'] ?? 'production');
define('ENVIRONMENT', 'development');

require_once BASEPATH . '/vendor/autoload.php';
Core\Kernel::boot()->run();
// Core\Kernel::boot()->run();

// $instance = Core\Kernel::boot();


// container('eventsManager')->attach('view:beforeRender', function($ev, $view) {
//     // echo $view->getMainView();
//     /** @var Phalcon\Mvc\View $view */ 
//     // return false;
//     // echo '<br><pre>';
//     // var_dump($view->getControllerName());
//     // var_dump($view->getActionName());
//     // var_dump($view->getMainView());
//     // exit;
// });

// $instance->run();