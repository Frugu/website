<?php

namespace App\Template;

class Paginator
{
    /**
     * @var array
     */
    public $list;

    /**
     * @var int
     */
    public $total;

    /**
     * @var int
     */
    public $offset;

    /**
     * @var int
     */
    public $limit;

    /**
     * TemplatePaginator constructor.
     *
     * @param array $list
     * @param int $total
     * @param int $offset
     * @param int $limit
     */
    public function __construct(array $list, int $total, int $offset, int $limit)
    {
        $this->list = $list;
        $this->total = $total;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * @return bool
     */
    public function needsPagination(): bool
    {
        return $this->total !== $this->limit;
    }

    /**
     * @return bool
     */
    public function hasPrevious(): bool
    {
        return $this->offset > 0;
    }

    /**
     * @return bool
     */
    public function hasNext(): bool
    {
        return $this->offset < $this->total;
    }

    /**
     * @return string
     */
    public function current(): string
    {
        return ($this->offset + $this->limit) / $this->limit;
    }

    /**
     * @return int
     */
    public function pages(): int
    {
        return ceil($this->total / $this->limit);
    }
}