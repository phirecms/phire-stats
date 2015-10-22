<?php

namespace Phire\Stats\Model;

use Phire\Stats\Table;

class Module extends AbstractModel
{

    public function getAll($limit = null, $page = null, $sort = null)
    {
        $order = (null !== $sort) ? $this->getSortOrder($sort, $page) : 'id ASC';

        if (null !== $limit) {
            $page = ((null !== $page) && ((int)$page > 1)) ?
                ($page * $limit) - $limit : null;

            return Table\Modules::findAll([
                'offset' => $page,
                'limit' => $limit,
                'order' => $order
            ])->rows();
        } else {
            return Table\Modules::findAll(['order' => $order])->rows();
        }
    }

    public function getById($id)
    {
        $module = Table\Modules::findById($id);
        if (isset($module->id)) {
            $data = $module->getColumns();
            $this->data = array_merge($this->data, $data);
        }
    }

    public function hasPages($limit)
    {
        return (Table\Modules::findAll()->count() > $limit);
    }

    public function getCount()
    {
        return Table\Modules::findAll()->count();
    }

}