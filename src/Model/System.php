<?php
/**
 * Phire Stats Application
 *
 * @link       https://github.com/phirecms/phire-stats
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Phire\Stats\Model;

use Phire\Stats\Table;

/**
 * Stats System Model class
 *
 * @category   Phire\Stats
 * @package    Phire\Stats
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
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
            'ip'        => $_SERVER['REMOTE_ADDR'],
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
            $windows = Table\System::findBy(['os' => '%win%'])->count();

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
        return (Table\System::findAll()->count() > $limit);
    }

    public function getCount()
    {
        return Table\System::findAll()->count();
    }

}
