<?php

/*
 * This file is part of the Сáша framework.
 *
 * (c) tchiotludo <http://github.com/tchiotludo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Cawa\Db;

use Cawa\Date\Date;
use Cawa\Date\DateTime;
use Cawa\Date\Time;

abstract class AbstractResult implements \Iterator, \Countable
{
    /**
     * @param string $query
     * @param bool $isUnbuffered
     */
    public function __construct(string $query, bool $isUnbuffered)
    {
        $this->query = $query;
        $this->isUnbuffered = $isUnbuffered;
    }

    /**
     * @var string
     */
    protected $query;

    /**
     * @return string
     */
    public function getQuery() : string
    {
        return $this->query;
    }

    /**
     * @var bool
     */
    protected $isUnbuffered = false;

    /**
     * @return bool
     */
    public function isUnbuffered() : bool
    {
        return $this->isUnbuffered;
    }

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var array
     */
    protected $convert = [];

    /**
     * @param string $data
     *
     * @return DateTime
     */
    protected static function convertDatetime(string $data) : DateTime
    {
        return new DateTime($data, 'UTC');
    }

    /**
     * @param string $data
     *
     * @return DateTime
     */
    protected static function convertTimestamp(string $data) : DateTime
    {
        return DateTime::createFromTimestamp($data);
    }

    /**
     * @param string $data
     *
     * @return Date
     */
    protected static function convertDate(string $data) : Date
    {
        return new Date($data);
    }

    /**
     * @param string $data
     *
     * @return Time
     */
    protected static function convertTime(string $data) : Time
    {
        return new Time($data);
    }

    /**
     * @param string $data
     *
     * @return int
     */
    protected static function convertInt(string $data) : int
    {
        return (int) $data;
    }

    /**
     * @param string $data
     *
     * @return float
     */
    protected static function convertFloat(string $data) : float
    {
        return (float) $data;
    }

    /**
     * @var array
     */
    protected $currentData = [];

    /**
     * {@inheritdoc}
     */
    public function key() : int
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function current() : array
    {
        return $this->currentData;
    }

    /**
     * {@inheritdoc}
     */
    public function valid() : bool
    {
        $this->currentData = null;

        $data = $this->load();

        if (is_bool($data)) {
            return $data;
        }

        // convert resultset & cst
        foreach ($this->convert as $col => $callable) {
            if (!is_null($data[$col])) {
                $data[$col] = $callable($data[$col]);
            }
        }

        $this->position++;
        $this->currentData = $data;

        return true;
    }

    /**
     * @return bool|array
     */
    abstract protected function load();

    /**
     * @return int
     */
    abstract public function insertedId() : int;

    /**
     * @return int
     */
    abstract public function affectedRows() : int;
}
