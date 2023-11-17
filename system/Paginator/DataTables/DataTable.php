<?php

namespace Core\Paginator\DataTables;

use Phalcon\Di\Injectable;
use Phalcon\Http\Response;
use Core\Paginator\DataTables\ParamsParser;
use Core\Paginator\DataTables\Adapters\QueryBuilder;

class DataTable extends Injectable
{

    protected $options;
    protected $params;
    protected $response;
    public    $parser;

    public function __construct($options = [])
    {
        $default = [
            'limit'  => 20,
            'length' => 50,
        ];

        $this->options = $options + $default;
        $this->parser = new ParamsParser($this->options['limit']);
    }

    public function getParams()
    {
        return $this->parser->getParams();
    }

    public function getResponse()
    {
        return !empty($this->response) ? $this->response : [];
    }

    public function sendResponse()
    {
        if ($this->di->has('view'))
        {
            $this->di->get('view')->disable();
        }
        
        $this->getHttpResponse()->send();
    }

    public function getHttpResponse()
    {
        $response = new Response();
        $response->setContentType('application/json', 'utf8');
        $response->setJsonContent($this->getResponse());
        return $response;
    }

    public static function fromBuilder($builder, $columns = [], $options = [])
    {
        $dataTable = new self($options);

        if (empty($columns))
        {
            $columns = $builder->getColumns();
            $columns = (is_array($columns)) ? $columns : array_map('trim', explode(',', $columns));
        }

        $adapter = new QueryBuilder($dataTable->options['length']);
        $adapter->setBuilder($builder);
        $adapter->setParser($dataTable->parser);
        $adapter->setColumns($columns);
        $dataTable->response = $adapter->getResponse();

        return $dataTable;
    }
}
