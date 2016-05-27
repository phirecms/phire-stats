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
namespace Phire\Stats\Table;

use Pop\Db\Record;
use Pop\Db\Adapter;

/**
 * Stats Themes Table class
 *
 * @category   Phire\Stats
 * @package    Phire\Stats
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
class Themes extends Record
{

    public function __construct(array $columns = null, $table = null, Adapter\AbstractAdapter $db = null)
    {
        if (null === $db) {
            $db = new Adapter\Sqlite([
                'database' => __DIR__ . '/../../data/stats.sqlite'
            ]);
        }
        parent::__construct($columns, $table, $db);
    }

}