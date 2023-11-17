<?php

namespace Core\Paginator\DataTables\Adapters;

use Phalcon\Paginator\Adapter\QueryBuilder as PQueryBuilder;
use Phalcon\Mvc\Model\Query\Builder as QBuilder;

class QueryBuilder extends AbstractAdapter
{
    /** @var QBuilder */
    protected $builder;

    public function setBuilder($builder)
    {
        $this->builder = $builder;
    }

    public function getResponse()
    {
        $builder = new PQueryBuilder([
            'builder' => $this->builder,
            'limit'   => 1,
            'page'    => 1,
        ]);

        $total = $builder->paginate();

        $this->bindGlobalSearch();

        $this->bind('column_search', function ($column, $search) {
            $this->builder->andWhere("{$column} LIKE :key_{$column}:", ["key_{$column}" => "%{$search}%"]);
        });

        $this->bind('order', function ($order) {
            if (!empty($order)) {
                $this->builder->orderBy(implode(', ', $order));
            }
        });

        $builder = new PQueryBuilder([
            'builder' => $this->builder,
            'limit'   => $this->parser->getLimit(),
            'page'    => $this->parser->getPage(),
        ]);

        $filtered = $builder->paginate();

        return $this->formResponse([
            'total'    => $total->total_items,
            'filtered' => $filtered->total_items,
            'data'     => $filtered->items->toArray(),
        ]);
    }

    private function bindGlobalSearch()
    {
        $search = $this->parser->getSearchValue();
        if (!mb_strlen($search)) return;

        $orLikes = [];
        $binds = [];
        $search = $this->sanitize($search);
        foreach ($this->parser->getSearchableColumns() as $column)
        {
            if (!$this->columnExists($column)) continue;
            $orLikes[] = "{$column} LIKE :key_{$column}:";
            $binds["key_{$column}"] = "%{$search}%";
        }

        $this->builder->where('(' . join(' OR ', $orLikes) . ')', $binds);
    }
}
