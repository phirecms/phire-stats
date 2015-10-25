<?php

namespace Phire\Stats\Model;

use Phire\Stats\Table;

class Theme extends AbstractModel
{

    public function getAll($limit = null, $page = null, $sort = null)
    {
        $this->getAverages($this->getCount());

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

    public function getAverages($count)
    {
        $this->data['php'] = [
            '5.4'   => 0,
            '5.5'   => 0,
            '5.6'   => 0,
            'other' => 0
        ];
        $this->data['db'] = [
            'mysql'  => 0,
            'pgsql'  => 0,
            'sqlite' => 0
        ];
        $this->data['server'] = [
            'apache' => 0,
            'iis'    => 0,
            'other'  => 0
        ];
        $this->data['os'] = [
            'linux'   => 0,
            'unix'    => 0,
            'windows' => 0,
            'other'   => 0
        ];

        if ($count > 0) {
            $php54   = Table\Themes::findBy(['php' => '5.4%'])->count();
            $php55   = Table\Themes::findBy(['php' => '5.5%'])->count();
            $php56   = Table\Themes::findBy(['php' => '5.6%'])->count();
            $mysql   = Table\Themes::findBy(['db' => '%mysql%'])->count();
            $pgsql   = Table\Themes::findBy(['db' => '%pgsql%'])->count();
            $sqlite  = Table\Themes::findBy(['db' => '%sqlite%'])->count();
            $apache  = Table\Themes::findBy(['server' => '%apache%'])->count();
            $iis     = Table\Themes::findBy(['server' => '%iis%'])->count();
            $linux   = Table\Themes::findBy(['os' => '%linux%'])->count();
            $unix    = Table\Themes::findBy(['os' => '%unix%'])->count();
            $windows = Table\Themes::findBy(['os' => '%win%'])->count();

            $this->data['php']['5.4']   = round((($php54 / $count) * 100), 2);
            $this->data['php']['5.5']   = round((($php55 / $count) * 100), 2);
            $this->data['php']['5.6']   = round((($php56 / $count) * 100), 2);
            $this->data['php']['other'] = round(100 - $this->data['php']['5.4'] - $this->data['php']['5.5'] - $this->data['php']['5.6']);

            $this->data['db']['mysql']  = round((($mysql / $count) * 100), 2);
            $this->data['db']['pgsql']  = round((($pgsql / $count) * 100), 2);
            $this->data['db']['sqlite'] = round((($sqlite / $count) * 100), 2);

            $this->data['server']['apache'] = round((($apache / $count) * 100), 2);
            $this->data['server']['iis']    = round((($iis / $count) * 100), 2);
            $this->data['server']['other']  = round(100 - $this->data['server']['apache'] - $this->data['server']['iis']);

            $this->data['os']['linux']   = round((($linux / $count) * 100), 2);
            $this->data['os']['unix']    = round((($unix / $count) * 100), 2);
            $this->data['os']['windows'] = round((($windows / $count) * 100), 2);
            $this->data['os']['other']   = round(100 - $this->data['os']['linux'] - $this->data['os']['unix'] - $this->data['os']['windows']);
        }
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
