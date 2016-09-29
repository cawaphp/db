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

namespace Cawa\Db\Pdo;

use Cawa\Db\AbstractResult;

class Result extends AbstractResult
{
    /**
     * @var \PDOStatement
     */
    private $result;

    /**
     * @param string $query
     * @param \PDOStatement $result
     * @param bool $isUnbuffered
     * @param int $insertedId
     */
    public function __construct(string $query, \PDOStatement $result, bool $isUnbuffered, int $insertedId = null)
    {
        parent::__construct($query, $isUnbuffered);

        $this->result = $result;
        $this->insertedId = $insertedId;
    }

    /**
     * {@inheritdoc}
     */
    protected function load()
    {
        if (($data = $this->result->fetch(\PDO::FETCH_ASSOC)) === false) {
            return false;
        }

        return $data;
    }

    /**
     * @var int
     */
    private $insertedId;

    /**
     * {@inheritdoc}
     */
    public function insertedId() : int
    {
        return $this->insertedId;
    }

    /**
     * {@inheritdoc}
     */
    public function affectedRows() : int
    {
        return $this->result->rowCount() >= 0 ? $this->result->rowCount() : null;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        if ($this->position > 0) {
            throw new \RuntimeException(
                'This result is a forward only result set, calling rewind() after moving forward is not supported'
            );
        }

        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->result->rowCount();
    }
}
