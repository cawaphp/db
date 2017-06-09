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

namespace Cawa\Db\Exceptions;

use Cawa\Db\AbstractDatabase;

class WarningException extends QueryException
{
    /**
     * WarningException constructor.
     *
     * @param AbstractDatabase $db
     * @param string $query
     * @param string $message
     * @param \Throwable $previous
     */
    public function __construct(AbstractDatabase $db, $query, $message, \Throwable $previous)
    {
        $current = $previous;
        while ($current) {
            $this->count++;
            $this->codes[] = $current->getCode();
            $current = $current->getPrevious();
        }

        parent::__construct($db, $query, $message, 0, $previous);

        $this->message .= sprintf(' [Count: %s] ', $this->count);
    }

    /**
     * @var array
     */
    private $codes = [];

    /**
     * @return array
     */
    public function getCodes() : array
    {
        return $this->codes;
    }

    /**
     * @param int $code
     *
     * @return bool
     */
    public function isCode(int $code) : bool
    {
        return sizeof($this->codes) == 1 && $this->codes[0] == $code;
    }

    /**
     * @var int
     */
    private $count = 0;

    /**
     * @return int
     */
    public function getCount() : int
    {
        return $this->count;
    }
}
