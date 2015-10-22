<?php

namespace Phire\Stats\Model;

use Phire\Stats\Table;

class System extends AbstractModel
{

    public function getAll($limit = null, $page = null, $sort = null)
    {
        $this->getAverages($this->getCount());

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

    public function save($post)
    {
        $post = $this->filter($post);

        $fields = [
            'version'   => (isset($post['version']) ? $post['version'] : null),
            'domain'    => (isset($post['domain']) ? $post['domain'] : null),
            'ip'        => (isset($post['ip']) ? $post['ip'] : null),
            'os'        => (isset($post['os']) ? $post['os'] : null),
            'server'    => (isset($post['server']) ? $post['server'] : null),
            'php'       => (isset($post['php']) ? $post['php'] : null),
            'db'        => (isset($post['db']) ? $post['db'] : null),
            'installed' => date('Y-m-d H:i:s')
        ];

        $system = new Table\System($fields);
        $system->save();
    }

    public function getAverages($count)
    {
        $this->php = [
            '5.4'   => 0,
            '5.5'   => 0,
            '5.6'   => 0,
            'other' => 0
        ];
        $this->db = [
            'mysql'  => 0,
            'pgsql'  => 0,
            'sqlite' => 0
        ];
        $this->server = [
            'apache' => 0,
            'iis'    => 0,
            'other'  => 0
        ];
        $this->os = [
            'linux'   => 0,
            'unix'    => 0,
            'windows' => 0,
            'other'   => 0
        ];

        if ($count > 0) {
            $php54   = Table\System::findBy(['php' => '5.4%'])->count();
            $php55   = Table\System::findBy(['php' => '5.5%'])->count();
            $php56   = Table\System::findBy(['php' => '5.6%'])->count();
            $mysql   = Table\System::findBy(['db' => '%mysql%'])->count();
            $pgsql   = Table\System::findBy(['db' => '%pgsql%'])->count();
            $sqlite  = Table\System::findBy(['db' => '%sqlite%'])->count();
            $apache  = Table\System::findBy(['server' => '%apache%'])->count();
            $iis     = Table\System::findBy(['server' => '%iis%'])->count();
            $linux   = Table\System::findBy(['os' => '%linux%'])->count();
            $unix    = Table\System::findBy(['os' => '%unix%'])->count();
            $windows = Table\System::findBy(['os' => '%windows%'])->count();

            $this->php['5.4']   = round((($php54 / $count) * 100), 2);
            $this->php['5.5']   = round((($php55 / $count) * 100), 2);
            $this->php['5.6']   = round((($php56 / $count) * 100), 2);
            $this->php['other'] = round(100 - $this->php['5.4'] - $this->php['5.5'] - $this->php['5.6']);

            $this->db['mysql']  = round((($mysql / $count) * 100), 2);
            $this->db['pgsql']  = round((($pgsql / $count) * 100), 2);
            $this->db['sqlite'] = round((($sqlite / $count) * 100), 2);

            $this->server['apache'] = round((($apache / $count) * 100), 2);
            $this->server['iis']    = round((($iis / $count) * 100), 2);
            $this->server['other']  = round(100 - $this->server['apache'] - $this->server['iis']);

            $this->os['linux']   = round((($linux / $count) * 100), 2);
            $this->os['unix']    = round((($unix / $count) * 100), 2);
            $this->os['windows'] = round((($windows / $count) * 100), 2);
            $this->os['other']   = round(100 - $this->os['linux'] - $this->os['unix'] - $this->os['windows']);
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