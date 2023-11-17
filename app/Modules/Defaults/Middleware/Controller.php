<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Middleware;

use CURLFile;
use Exception;
use Core\Facades\View;
use App\Models\MenuModel;
use WpOrg\Requests\Hooks;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher;
use WpOrg\Requests\Requests;
use Phalcon\Mvc\Controller as BaseController;

class Controller extends BaseController
{
    private $myArray = [];
    private $is_usingRole = 'no';
    // public $sess_pdamid;

    public function setArrayRole($newArray)
    {
        if (is_array($newArray)) {
            $this->myArray = $newArray;
        } else {
            throw new Exception("Invalid argument, must be an array");
        }
    }

    public function getArrayRole()
    {
        return $this->myArray;
    }

    public function setUsingRole($is_using)
    {
        if (is_string($is_using)) {
            $this->is_usingRole = $is_using;
        } else {
            throw new Exception("Invalid argument, must be a string");
        }
    }

    public function getUsingRole()
    {
        return $this->is_usingRole;
    }

    public function initialize()
    {
        // $this->sess_pdamid = $this->session->user['pdam_id'];
        
        if (!$this->session->has('user')) {
            /** @var array */
            $queries = $this->request->getQuery();
            $intended = isset($queries['_url']) == true ? $queries['_url'] : null;
            unset($queries['_url']);

            $intended .= empty($queries) ? '' : ('?' . http_build_query($queries));

            $this->session->set('intended', $intended);

            return $this->response->redirect('/panel/auth/login');
        } else {

            // echo '<pre>';
            // var_dump();
            // echo '</pre>';
            // die();

            if ($this->getUsingRole() == 'yes') {
                if (!in_array($this->session->user['role_akses'], $this->getArrayRole())) {
                    return $this->response->redirect('/panel/auth/unauthorized');
                }
            }
        }
    }
}
