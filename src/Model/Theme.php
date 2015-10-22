<?php

namespace Phire\Stats\Model;

use Phire\Stats\Table;

class Theme extends AbstractModel
{

    public function getAll($limit = null, $page = null, $sort = null)
    {
        $order = (null !== $sort) ? $this->getSortOrder($sort, $page) : 'id ASC';

        if (null !== $limit) {
            $page = ((null !== $page) && ((int)$page > 1)) ?
                ($page * $limit) - $limit : null;

            return Table\Themes::findAll([
                'offset' => $page,
                'limit' => $limit,
                'order' => $order
            ])->rows();
        } else {
            return Table\Themes::findAll(['order' => $order])->rows();
        }
    }

    public function getById($id)
    {
        $theme = Table\Themes::findById($id);
        if (isset($theme->id)) {
            $data = $theme->getColumns();
            $this->data = array_merge($this->data, $data);
        }
    }

    public function save($post)
    {
        $post = $this->filter($post);

        $fields = [
            'name'      => (isset($post['name']) ? $post['name'] : null),
            'version'   => (isset($post['version']) ? $post['version'] : null),
            'domain'    => (isset($post['domain']) ? $post['domain'] : null),
            'ip'        => (isset($post['ip']) ? $post['ip'] : null),
            'os'        => (isset($post['os']) ? $post['os'] : null),
            'server'    => (isset($post['server']) ? $post['server'] : null),
            'php'       => (isset($post['php']) ? $post['php'] : null),
            'db'        => (isset($post['db']) ? $post['db'] : null),
            'installed' => date('Y-m-d H:i:s')
        ];

        $theme = new Table\Themes($fields);
        $theme->save();
    }

    public function hasPages($limit)
    {
        return (Table\Themes::findAll()->count() > $limit);
    }

    public function getCount()
    {
        return Table\Themes::findAll()->count();
    }

}