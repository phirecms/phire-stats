<?php

namespace Phire\Stats\Model;

use Phire\Stats\Table;

class System extends AbstractModel
{

    public function getAll($limit = null, $page = null, $sort = null)
    {
        $order = (null !== $sort) ? $this->getSortOrder($sort, $page) : 'id ASC';

        if (null !== $limit) {
            $page = ((null !== $page) && ((int)$page > 1)) ?
                ($page * $limit) - $limit : null;

            return Table\System::findAll([
                'offset' => $page,
                'limit' => $limit,
                'order' => $order
            ])->rows();
        } else {
            return Table\System::findAll(['order' => $order])->rows();
        }
    }

    public function getById($id)
    {
        $system = Table\System::findById($id);
        if (isset($system->id)) {
            $data = $system->getColumns();
            $this->data = array_merge($this->data, $data);
        }
    }

    public function hasPages($limit)
    {
        return (Table\System::findAll()->count() > $limit);
    }

    public function getCount()
    {
        return Table\System::findAll()->count();
    }

}