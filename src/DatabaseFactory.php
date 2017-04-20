<?php

/*
 * This file is part of the Сáша framework.
 *
 * (c) tchiotludo <http://github.com/tchiotludo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

namespace Cawa\Db;

use Cawa\Core\DI;

trait DatabaseFactory
{
    /**
     * @param string $name config key or class name
     *
     * @return TransactionDatabase
     */
    private static function db(string $name = null) : TransactionDatabase
    {
        list($container, $config, $return) = DI::detect(__METHOD__, 'db', $name);

        if ($return) {
            return $return;
        }

        $db = AbstractDatabase::create($config);

        return DI::set(__METHOD__, $container, $db);
    }
}
