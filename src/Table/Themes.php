<?php

namespace Phire\Stats\Table;

use Pop\Db\Record;
use Pop\Db\Adapter;

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